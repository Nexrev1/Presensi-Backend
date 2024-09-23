import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';
import 'package:geocoding/geocoding.dart';
import 'package:syncfusion_flutter_maps/maps.dart';

class LocationPage extends StatefulWidget {
  final String tipe;
  final Future<void> Function()? onSuccess;

  const LocationPage({
    Key? key,
    required this.tipe,
    this.onSuccess,
  }) : super(key: key);

  @override
  _LocationPageState createState() => _LocationPageState();
}

class _LocationPageState extends State<LocationPage> {
  Position? _currentPosition;
  Placemark? _placemark;
  String _address = 'Fetching address...';
  bool _isLoading = true;
  String _errorMessage = '';

  @override
  void initState() {
    super.initState();
    _getLocation();
  }

  Future<void> _getLocation() async {
    setState(() {
      _isLoading = true;
    });

    try {
      // Periksa apakah layanan lokasi aktif
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        serviceEnabled = await Geolocator.openLocationSettings();
        if (!serviceEnabled) {
          setState(() {
            _errorMessage = 'Location services are disabled.';
            _isLoading = false;
          });
          return;
        }
      }

      // Periksa izin lokasi
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission != LocationPermission.whileInUse && permission != LocationPermission.always) {
          setState(() {
            _errorMessage = 'Location permission is denied.';
            _isLoading = false;
          });
          return;
        }
      }

      if (permission == LocationPermission.deniedForever) {
        setState(() {
          _errorMessage = 'Location permission is permanently denied.';
          _isLoading = false;
        });
        return;
      }

      // Ambil posisi saat ini dengan akurasi tinggi
      Position position = await Geolocator.getCurrentPosition(
        desiredAccuracy: LocationAccuracy.high,
      );
      setState(() {
        _currentPosition = position;
        _isLoading = false;
      });

      // Dapatkan alamat dari koordinat menggunakan geocoding
      List<Placemark> placemarks = await placemarkFromCoordinates(
        position.latitude,
        position.longitude,
      );

      if (placemarks.isNotEmpty) {
        setState(() {
          _placemark = placemarks.first;
          _address = '${_placemark!.street}, ${_placemark!.locality}, ${_placemark!.country}';
        });
      } else {
        setState(() {
          _address = 'Address not found';
        });
      }
    } catch (e) {
      print('Error fetching location or address: $e');
      setState(() {
        _errorMessage = 'Failed to get location or address: $e';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: const Color.fromARGB(255, 131, 1, 1),
        title: const Text(
          'Lokasi Saya',
          style: TextStyle(color: Colors.white),
        ),
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Container(
        decoration: const BoxDecoration(
          image: DecorationImage(
            image: AssetImage("assets/image/background.jpg"),
            fit: BoxFit.cover,
          ),
        ),
        child: BackdropFilter(
          filter: ImageFilter.blur(sigmaX: 5, sigmaY: 5),
          child: Container(
            color: const Color.fromARGB(255, 36, 36, 36).withOpacity(0.3),
            child: SafeArea(
              child: Column(
                children: [
                  Expanded(
                    flex: 3,
                    child: _currentPosition == null
                        ? const Center(child: Text('Lokasi tidak tersedia'))
                        : SfMaps(
                      layers: [
                        MapTileLayer(
                          urlTemplate: "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
                          initialFocalLatLng: MapLatLng(
                              _currentPosition!.latitude,
                              _currentPosition!.longitude),
                          initialZoomLevel: 15,
                          markerBuilder: (BuildContext context, int index) {
                            return MapMarker(
                              latitude: _currentPosition!.latitude,
                              longitude: _currentPosition!.longitude,
                              child: const Icon(
                                Icons.location_on,
                                color: Colors.red,
                              ),
                            );
                          },
                        )
                      ],
                    ),
                  ),
                  Padding(
                    padding: const EdgeInsets.all(16.0),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.center,
                      children: [
                        if (_errorMessage.isNotEmpty) ...[
                          Text(_errorMessage, style: const TextStyle(color: Colors.red)),
                          const SizedBox(height: 20),
                        ],
                        const Text(
                          'Anda berada di luar area kampus!',
                          style: TextStyle(
                            fontSize: 24,
                            color: Colors.white,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                        const SizedBox(height: 20),
                        const Text(
                          'Lokasi Anda:',
                          style: TextStyle(
                            fontSize: 18,
                            color: Colors.white,
                          ),
                        ),
                        const SizedBox(height: 10),
                        Text(
                          _address,
                          style: const TextStyle(
                            fontSize: 18,
                            color: Colors.white,
                          ),
                          textAlign: TextAlign.center,
                        ),
                        if (_placemark != null) ...[
                          const SizedBox(height: 20),
                          Text(
                            'Detail Placemark:',
                            style: const TextStyle(
                              fontSize: 18,
                              color: Colors.white,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                          const SizedBox(height: 10),
                          Text(
                            'Street: ${_placemark!.street ?? 'N/A'}',
                            style: const TextStyle(
                              fontSize: 16,
                              color: Colors.white,
                            ),
                          ),
                          Text(
                            'Locality: ${_placemark!.locality ?? 'N/A'}',
                            style: const TextStyle(
                              fontSize: 16,
                              color: Colors.white,
                            ),
                          ),
                          Text(
                            'Country: ${_placemark!.country ?? 'N/A'}',
                            style: const TextStyle(
                              fontSize: 16,
                              color: Colors.white,
                            ),
                          ),
                        ],
                        const SizedBox(height: 20),
                        Text(
                          'Tipe: ${widget.tipe}',
                          style: const TextStyle(
                            fontSize: 18,
                            color: Colors.white,
                          ),
                        ),
                        const SizedBox(height: 40),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}
