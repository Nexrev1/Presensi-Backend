import 'dart:async';
import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:intl/intl.dart';
import 'package:http/http.dart' as http;
import 'package:permission_handler/permission_handler.dart';
import 'package:presensi_app/models/riwayat-presensi-response.dart';
import 'package:presensi_app/pages/riwayat-presensi-page.dart';
import 'package:presensi_app/pages/location-page.dart';
import 'package:presensi_app/pages/izin-page.dart';
import 'package:presensi_app/pages/login-page.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:geolocator/geolocator.dart';

class HomePage extends StatefulWidget {
  const HomePage({Key? key}) : super(key: key);

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  final Future<SharedPreferences> _prefs = SharedPreferences.getInstance();
  late Future<String> _nameFuture;
  late Future<String> _tokenFuture;

  RiwayatPresensiResponse? homeResponse;
  Datum? hariIni;
  List<Datum> riwayat = [];
  bool isLoading = true;
  bool hasIzinData = false;
  String status = ''; // Ubah izinApproved menjadi status

  late Timer _timer;
  String _currentTime = "";

  @override
  void initState() {
    super.initState();
    _nameFuture = _prefs.then((prefs) => prefs.getString("name") ?? "User");
    _tokenFuture = _prefs.then((prefs) => prefs.getString("token") ?? "");

    getData();
    _startClock();
  }

  void _startClock() {
    _timer = Timer.periodic(Duration(seconds: 1), (timer) {
      final now = DateTime.now();
      setState(() {
        _currentTime = DateFormat('HH:mm').format(now);
      });
    });
  }

  Future<void> getData() async {
    setState(() {
      isLoading = true;
    });

    final String token = await _tokenFuture;
    final Map<String, String> headers = {'Authorization': 'Bearer $token'};

    try {
      final response = await http.get(
        Uri.parse('http://10.0.2.2:8000/api/get-presensi'),
        headers: headers,
      );

      if (response.statusCode == 200) {
        homeResponse = RiwayatPresensiResponse.fromJson(json.decode(response.body));
        print('Data: ${homeResponse!.data}'); // Debug: Print data

        riwayat.clear();
        hasIzinData = homeResponse!.data.any((element) => element.isIzinApproved);

        homeResponse!.data.forEach((element) {
          if (element.isHariIni) {
            hariIni = element;
          } else {
            riwayat.add(element);
          }
        });

        // Update status berdasarkan data
        status = homeResponse!.data.any((element) => element.status == 'approved')
            ? 'Izin Anda Disetujui'
            : 'Izin Anda Belum Disetujui';
      } else {
        throw Exception('Gagal memuat data');
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Error: $e")));
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  Future<void> _logout() async {
    final SharedPreferences prefs = await _prefs;
    await prefs.remove('token');
    Navigator.of(context).pushAndRemoveUntil(
      MaterialPageRoute(builder: (context) => LoginPage()),
      (Route<dynamic> route) => false,
    );
  }

  Future<void> _handleAbsensi(String tipe) async {
    if (tipe == 'pulang' && hariIni?.masuk == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Anda harus melakukan absensi masuk terlebih dahulu")),
      );
      return;
    }

    bool isOutsideCampus = await _checkIfOutsideCampus();

    if (isOutsideCampus) {
      await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => LocationPage(
            tipe: tipe,
            onSuccess: () async {
              await _getLocationAndRecordAbsensi(tipe);
            },
          ),
        ),
      );
    } else {
      await _getLocationAndRecordAbsensi(tipe);
    }
  }

  Future<void> _getLocationAndRecordAbsensi(String tipe) async {
    final position = await _getCurrentLocation();
    if (position != null) {
      await _recordAbsensi(tipe, position.latitude, position.longitude);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Gagal mendapatkan lokasi")),
      );
    }
  }

  Future<Position?> _getCurrentLocation() async {
    final permission = await Permission.location.request();
    if (permission.isGranted) {
      return await Geolocator.getCurrentPosition(desiredAccuracy: LocationAccuracy.high);
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Permission denied")),
      );
      return null;
    }
  }

  Future<void> _recordAbsensi(String tipe, double latitude, double longitude) async {
    final String token = await _tokenFuture;
    final Map<String, String> headers = {
      'Authorization': 'Bearer $token',
      'Content-Type': 'application/json',
    };
    final Map<String, dynamic> body = {
      'latitude': latitude.toString(),
      'longitude': longitude.toString(),
    };

    String url = tipe == 'masuk'
        ? 'http://10.0.2.2:8000/api/absen-masuk'
        : 'http://10.0.2.2:8000/api/absen-pulang';

    try {
      final response = await http.post(
        Uri.parse(url),
        headers: headers,
        body: json.encode(body),
      );

      if (response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Absensi $tipe berhasil")));
        getData(); // Refresh data setelah absensi berhasil
      } else {
        final responseData = json.decode(response.body);
        throw Exception(responseData['message'] ?? 'Gagal melakukan absensi $tipe');
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Error: $e")));
    }
  }

  Future<bool> _checkIfOutsideCampus() async {
    final position = await _getCurrentLocation();
    if (position != null) {
      final campusCenters = [
        LatLng(-1.6185483, 103.6255088),
        LatLng(-1.6416138, 103.614928),
      ];
      const radiusInMeters = 500;

      for (var center in campusCenters) {
        final distance = Geolocator.distanceBetween(
          position.latitude,
          position.longitude,
          center.latitude,
          center.longitude,
        );
        if (distance <= radiusInMeters) {
          return false;
        }
      }
      return true;
    }
    return false;
  }

  @override
  void dispose() {
    _timer.cancel();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: PreferredSize(
        preferredSize: Size.fromHeight(60.0),
        child: AppBar(
          backgroundColor: Color(0xFFB71C1C),
          title: Text(
            'Homepage',
            style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold),
          ),
          actions: [
            IconButton(
              icon: Icon(Icons.exit_to_app, color: Colors.white),
              onPressed: _logout,
            ),
          ],
        ),
      ),
      drawer: _buildDrawer(),
      body: Container(
        decoration: BoxDecoration(
          image: DecorationImage(
            image: AssetImage('assets/image/background.jpg'),
            fit: BoxFit.cover,
          ),
        ),
        child: Padding(
          padding: const EdgeInsets.all(10.0),
          child: isLoading
              ? Center(child: CircularProgressIndicator())
              : Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    _buildGreeting(),
                    SizedBox(height: 8),
                    _buildLogo(),
                    SizedBox(height: 10),
                    _buildCurrentTime(),
                    SizedBox(height: 15),
                    Expanded(child: _buildPresensiCard()),
                  ],
                ),
        ),
      ),
    );
  }

  Widget _buildDrawer() {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(color: Color(0xFFB71C1C)),
            child: Text('Menu', style: TextStyle(color: Colors.white, fontSize: 24)),
          ),
          ListTile(
            leading: Icon(Icons.history, color: Color(0xFFB71C1C)),
            title: Text('Riwayat Presensi', style: TextStyle(color: Color(0xFFB71C1C))),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => RiwayatPresensiPage(riwayat: riwayat)),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.cancel, color: Color(0xFFB71C1C)),
            title: Text('Izin', style: TextStyle(color: Color(0xFFB71C1C))),
            onTap: () {
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => IzinPage()),
              );
            },
          ),
          ListTile(
            leading: Icon(Icons.exit_to_app, color: Colors.red),
            title: Text('Logout', style: TextStyle(color: Colors.red)),
            onTap: _logout,
          ),
        ],
      ),
    );
  }

  Widget _buildLogo() {
    return Center(
      child: Image.asset(
        'assets/image/logo.png',
        height: 120,
      ),
    );
  }

  Widget _buildGreeting() {
    return FutureBuilder<String>(
      future: _nameFuture,
      builder: (BuildContext context, AsyncSnapshot<String> snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return Center(child: CircularProgressIndicator());
        } else if (snapshot.hasData) {
          return Center(
            child: Text(
              'Hi, ${snapshot.data}!',
              style: TextStyle(fontSize: 20, color: Colors.white, fontWeight: FontWeight.bold),
              textAlign: TextAlign.center,
            ),
          );
        } else {
          return Center(
            child: Text(
              "Hi, User!",
              style: TextStyle(fontSize: 20, color: Colors.white),
              textAlign: TextAlign.center,
            ),
          );
        }
      },
    );
  }

  Widget _buildCurrentTime() {
    return Center(
      child: Column(
        children: [
          Text(
            _currentTime,
            style: TextStyle(
              fontSize: 50,
              color: Colors.white,
              fontWeight: FontWeight.bold,
              letterSpacing: 2,
            ),
          ),
          Text(
            DateFormat('dd MMMM yyyy').format(DateTime.now()),
            style: TextStyle(
              fontSize: 16,
              color: Colors.white,
              fontWeight: FontWeight.w400,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPresensiCard() {
    return Card(
      color: Colors.white,
      elevation: 8,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: Padding(
        padding: const EdgeInsets.all(10),
        child: Column(
          mainAxisSize: MainAxisSize.min, // Menyusut sesuai konten
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Center(
              child: Text(
                'Presensi Karyawan',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
            ),
            SizedBox(height: 10),
            _buildPresensiTimes(),
            SizedBox(height: 15),
            _buildAbsensiButtons(),
            SizedBox(height: 15),
            _buildAjukanIzinButton(),
            SizedBox(height: 10),
            if (hasIzinData) _buildIzinStatus(), // Ganti izinApproved dengan status
          ],
        ),
      ),
    );
  }

  Widget _buildAjukanIzinButton() {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => IzinPage()),
          );
        },
        style: ButtonStyle(
          backgroundColor: MaterialStateProperty.all<Color>(Color(0xFF003366)),
          foregroundColor: MaterialStateProperty.all<Color>(Colors.white),
          padding: MaterialStateProperty.all<EdgeInsetsGeometry>(EdgeInsets.symmetric(horizontal: 15, vertical: 10)),
          elevation: MaterialStateProperty.all<double>(6),
          shape: MaterialStateProperty.all<RoundedRectangleBorder>(
            RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12.0),
            ),
          ),
        ),
        child: Text('Ajukan Izin', style: TextStyle(fontSize: 16)),
      ),
    );
  }

  Widget _buildPresensiTimes() {
    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Expanded(
              child: _buildPresensiTime('Jam Masuk', hariIni?.masuk ?? '-'),
            ),
            SizedBox(width: 10),
            Expanded(
              child: _buildPresensiTime('Jam Pulang', hariIni?.pulang ?? '-'),
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildPresensiTime(String title, String time) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Text(
          title,
          style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18, color: Colors.black54),
        ),
        Text(
          time,
          style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.black87),
        ),
      ],
    );
  }

  Widget _buildAbsensiButtons() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Expanded(
          child: _buildAbsensiButton(
            'Masuk',
            'masuk',
            hariIni?.masuk == null, // Enable jika belum presensi masuk
          ),
        ),
        SizedBox(width: 10),
        Expanded(
          child: _buildAbsensiButton(
            'Pulang',
            'pulang',
            true, // Selalu aktif
          ),
        ),
      ],
    );
  }

  Widget _buildAbsensiButton(String label, String tipe, bool isEnabled) {
    return SizedBox(
      width: double.infinity,
      child: ElevatedButton(
        onPressed: isEnabled ? () => _handleAbsensi(tipe) : null,
        style: ButtonStyle(
          backgroundColor: MaterialStateProperty.all<Color>(Color(0xFF003366)),
          foregroundColor: MaterialStateProperty.all<Color>(Colors.white),
          padding: MaterialStateProperty.all<EdgeInsetsGeometry>(EdgeInsets.symmetric(horizontal: 15, vertical: 10)),
          elevation: MaterialStateProperty.all<double>(6),
          shape: MaterialStateProperty.all<RoundedRectangleBorder>(
            RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(12.0),
            ),
          ),
        ),
        child: Text(label, style: TextStyle(fontSize: 16)),
      ),
    );
  }

  Widget _buildIzinStatus() {
    print('Status: $status');
    return Center(
      child: Text(
        status,
        style: TextStyle(
          fontSize: 16,
          color: status == 'Izin Anda Disetujui' ? Colors.green : Colors.red,
          fontWeight: FontWeight.bold,
        ),
      ),
    );
  }
}
