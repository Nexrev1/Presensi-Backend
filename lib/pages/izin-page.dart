import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import 'package:intl/intl.dart';
import 'package:presensi_app/models/izin-response.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;

class IzinPage extends StatefulWidget {
  @override
  _IzinPageState createState() => _IzinPageState();
}

class _IzinPageState extends State<IzinPage> {
  final _formKey = GlobalKey<FormState>();
  String _jenisIzin = 'Sakit';
  DateTime _tanggalMulai = DateTime.now();
  DateTime _tanggalSelesai = DateTime.now();
  File? _dokumen;
  final _picker = ImagePicker();
  late Future<String> _token;

  late TextEditingController _tanggalMulaiController;
  late TextEditingController _tanggalSelesaiController;

  @override
  void initState() {
    super.initState();
    _token = SharedPreferences.getInstance().then((prefs) {
      return prefs.getString('token') ?? '';
    });

    _tanggalMulaiController = TextEditingController(
      text: DateFormat('yyyy-MM-dd').format(_tanggalMulai),
    );

    _tanggalSelesaiController = TextEditingController(
      text: DateFormat('yyyy-MM-dd').format(_tanggalSelesai),
    );
  }

  @override
  void dispose() {
    _tanggalMulaiController.dispose();
    _tanggalSelesaiController.dispose();
    super.dispose();
  }

  Future<void> _pickDokumen() async {
    final pickedFile = await _picker.pickImage(source: ImageSource.gallery);
    setState(() {
      _dokumen = pickedFile != null ? File(pickedFile.path) : null;
    });
  }

  Future<void> _ajukanIzin() async {
  if (_formKey.currentState!.validate()) {
    final String token = await _token;

    final request = http.MultipartRequest(
      'POST',
      Uri.parse('http://10.0.2.2:8000/api/izin/store'), // Ganti URL sesuai API Anda
    );

    request.headers['Authorization'] = 'Bearer $token';
    request.fields['jenis_izin'] = _jenisIzin;
    request.fields['tanggal_mulai'] = DateFormat('yyyy-MM-dd').format(_tanggalMulai);
    request.fields['tanggal_selesai'] = DateFormat('yyyy-MM-dd').format(_tanggalSelesai);

    if (_dokumen != null) {
      final bytes = await _dokumen!.readAsBytes();
      request.files.add(
        http.MultipartFile.fromBytes(
          'dokumen',
          bytes,
          filename: _dokumen!.path.split('/').last,
        ),
      );
    }

    try {
      final response = await request.send();
      final responseBody = await response.stream.bytesToString();
      final responseJson = jsonDecode(responseBody);

      // Debugging: Print status code and response body for verification
      print('Response Status Code: ${response.statusCode}');
      print('Response JSON: $responseJson');

      if (response.statusCode == 201 && responseJson['message'] == 'Izin berhasil diajukan') {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Izin berhasil diajukan.')),
        );
        setState(() {
          _jenisIzin = 'Sakit';
          _tanggalMulai = DateTime.now();
          _tanggalSelesai = DateTime.now();
          _dokumen = null;
          _tanggalMulaiController.text = DateFormat('yyyy-MM-dd').format(_tanggalMulai);
          _tanggalSelesaiController.text = DateFormat('yyyy-MM-dd').format(_tanggalSelesai);
        });

        // Navigasi ke HomePage
        Navigator.pushNamedAndRemoveUntil(context, '/home', (route) => false);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal mengajukan izin: ${responseJson['message'] ?? 'Terjadi kesalahan'}')),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: ${e.toString()}')),
      );
    }
  }
}


  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 131, 1, 1),
        title: Text(
          'Ajukan Izin',
          style: TextStyle(color: Colors.white),
        ),
      ),
      body: Container(
        constraints: BoxConstraints.expand(),
        decoration: BoxDecoration(
          image: DecorationImage(
            image: AssetImage('assets/image/background2.jpg'),
            fit: BoxFit.cover,
          ),
        ),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: SingleChildScrollView(
            child: Form(
              key: _formKey,
              child: Card(
                elevation: 5,
                margin: EdgeInsets.zero,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Padding(
                  padding: const EdgeInsets.all(16.0),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      DropdownButtonFormField<String>(
                        value: _jenisIzin,
                        items: ['Sakit', 'Cuti', 'Izin'].map((String jenis) {
                          return DropdownMenuItem<String>(
                            value: jenis,
                            child: Text(jenis),
                          );
                        }).toList(),
                        onChanged: (value) {
                          setState(() {
                            _jenisIzin = value!;
                          });
                        },
                        decoration: InputDecoration(
                          labelText: 'Jenis Izin',
                          border: OutlineInputBorder(),
                        ),
                        validator: (value) => value == null ? 'Pilih jenis izin' : null,
                      ),
                      SizedBox(height: 16),
                      TextFormField(
                        readOnly: true,
                        controller: _tanggalMulaiController,
                        decoration: InputDecoration(
                          labelText: 'Tanggal Mulai',
                          border: OutlineInputBorder(),
                        ),
                        onTap: () async {
                          DateTime? pickedDate = await showDatePicker(
                            context: context,
                            initialDate: _tanggalMulai,
                            firstDate: DateTime(2000),
                            lastDate: DateTime(2101),
                          );
                          if (pickedDate != null && pickedDate != _tanggalMulai) {
                            setState(() {
                              _tanggalMulai = pickedDate;
                              _tanggalMulaiController.text = DateFormat('yyyy-MM-dd').format(_tanggalMulai);
                            });
                          }
                        },
                        validator: (value) => value!.isEmpty ? 'Pilih tanggal mulai' : null,
                      ),
                      SizedBox(height: 16),
                      TextFormField(
                        readOnly: true,
                        controller: _tanggalSelesaiController,
                        decoration: InputDecoration(
                          labelText: 'Tanggal Selesai',
                          border: OutlineInputBorder(),
                        ),
                        onTap: () async {
                          DateTime? pickedDate = await showDatePicker(
                            context: context,
                            initialDate: _tanggalSelesai,
                            firstDate: _tanggalMulai,
                            lastDate: DateTime(2101),
                          );
                          if (pickedDate != null && pickedDate != _tanggalSelesai) {
                            setState(() {
                              _tanggalSelesai = pickedDate;
                              _tanggalSelesaiController.text = DateFormat('yyyy-MM-dd').format(_tanggalSelesai);
                            });
                          }
                        },
                        validator: (value) => value!.isEmpty ? 'Pilih tanggal selesai' : null,
                      ),
                      SizedBox(height: 16),
                      GestureDetector(
                        onTap: _pickDokumen,
                        child: Container(
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey),
                            borderRadius: BorderRadius.circular(8),
                            color: Colors.white,
                          ),
                          padding: EdgeInsets.symmetric(vertical: 16, horizontal: 12),
                          child: Row(
                            children: [
                              Icon(Icons.attach_file, color: Colors.blue),
                              SizedBox(width: 8),
                              Expanded(
                                child: Text(
                                  _dokumen == null ? 'Unggah Dokumen' : _dokumen!.path.split('/').last,
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),
                            ],
                          ),
                        ),
                      ),
                      SizedBox(height: 16),
                      ElevatedButton(
                        onPressed: _ajukanIzin,
                        child: Text('Ajukan', style: TextStyle(fontWeight: FontWeight.bold, color: Colors.white)),
                        style: ButtonStyle(
                          backgroundColor: MaterialStateProperty.all<Color>(Color.fromARGB(255, 131, 1, 1)),
                          padding: MaterialStateProperty.all<EdgeInsets>(EdgeInsets.symmetric(vertical: 14)),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
