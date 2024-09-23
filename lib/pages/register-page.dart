import 'dart:convert';
import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as myHttp;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:presensi_app/models/register-response.dart';
import 'package:presensi_app/pages/login-page.dart';

class RegisterPage extends StatefulWidget {
  const RegisterPage({super.key});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final Future<SharedPreferences> _prefs = SharedPreferences.getInstance();
  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();
  TextEditingController confirmPasswordController = TextEditingController();
  TextEditingController nameController = TextEditingController();
  bool isLoading = false;

  Future<void> register(String name, String email, String password, String confirmPassword) async {
    setState(() {
      isLoading = true;
    });

    if (password != confirmPassword) {
      setState(() {
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Password dan Konfirmasi Password tidak cocok")),
      );
      return;
    }

    try {
      Map<String, String> body = {
        "name": name,
        "email": email,
        "password": password,
        "password_confirmation": confirmPassword, // Pastikan menggunakan 'password_confirmation'
      };
      var response = await myHttp.post(
        Uri.parse('http://10.0.2.2:8000/api/register'),
        body: body,
      ).timeout(const Duration(seconds: 10));

      setState(() {
        isLoading = false;
      });

      if (response.statusCode == 200 || response.statusCode == 201) {
        var responseData = json.decode(response.body);
        RegisterResponse registerResponse = RegisterResponse.fromJson(responseData);

        if (registerResponse.success) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text("Pendaftaran berhasil! Silakan login.")),
          );
          Navigator.of(context).pushReplacement(
            MaterialPageRoute(builder: (context) => LoginPage()),
          );
        } else {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text("Pendaftaran gagal: ${registerResponse.message}")),
          );
        }
      } else {
        var errorData = json.decode(response.body);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Gagal mendaftar: ${errorData['message'] ?? 'Unknown error'}")),
        );
      }
    } catch (e) {
      setState(() {
        isLoading = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Error: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Stack(
        fit: StackFit.expand,
        children: [
          // Background Image
          Image.asset(
            'assets/image/background1.jpg',
            fit: BoxFit.cover,
          ),
          // Register Form
          SafeArea(
            child: Padding(
              padding: const EdgeInsets.all(16.0),
              child: Center(
                child: SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Center(
                        child: Text(
                          "REGISTER",
                          style: TextStyle(
                            fontSize: 24,
                            fontWeight: FontWeight.bold,
                            color: Colors.black, // Ganti warna teks menjadi hitam
                          ),
                        ),
                      ),
                      SizedBox(height: 20),
                      Text(
                        "Name",
                        style: TextStyle(color: Colors.black), // Ganti warna teks menjadi hitam
                      ),
                      TextField(
                        controller: nameController,
                        decoration: InputDecoration(
                          enabledBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                        ),
                      ),
                      SizedBox(height: 20),
                      Text(
                        "Email",
                        style: TextStyle(color: Colors.black), // Ganti warna teks menjadi hitam
                      ),
                      TextField(
                        controller: emailController,
                        decoration: InputDecoration(
                          enabledBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                        ),
                      ),
                      SizedBox(height: 20),
                      Text(
                        "Password",
                        style: TextStyle(color: Colors.black), // Ganti warna teks menjadi hitam
                      ),
                      TextField(
                        controller: passwordController,
                        obscureText: true,
                        decoration: InputDecoration(
                          enabledBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                        ),
                      ),
                      SizedBox(height: 20),
                      Text(
                        "Confirm Password",
                        style: TextStyle(color: Colors.black), // Ganti warna teks menjadi hitam
                      ),
                      TextField(
                        controller: confirmPasswordController,
                        obscureText: true,
                        decoration: InputDecoration(
                          enabledBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                          focusedBorder: OutlineInputBorder(
                            borderSide: BorderSide(color: Colors.black), // Ganti border color menjadi hitam
                          ),
                        ),
                      ),
                      SizedBox(height: 20),
                      ElevatedButton(
                        onPressed: () {
                          register(
                            nameController.text,
                            emailController.text,
                            passwordController.text,
                            confirmPasswordController.text,
                          );
                        },
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.redAccent, // Ganti warna latar belakang tombol
                        ),
                        child: isLoading
                            ? CircularProgressIndicator(
                                valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                              )
                            : Text("Daftar", style: TextStyle(color: Colors.white)), // Ganti warna teks tombol menjadi putih
                      ),
                      SizedBox(height: 20),
                      Center(
                        child: GestureDetector(
                          onTap: () {
                            Navigator.of(context).push(
                              MaterialPageRoute(builder: (context) => LoginPage()),
                            );
                          },
                          child: Text(
                            "Sudah punya akun? Silahkan login",
                            style: TextStyle(
                              color: Colors.black, // Ganti warna teks menjadi hitam
                              decoration: TextDecoration.underline,
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}
