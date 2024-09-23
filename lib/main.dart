import 'package:flutter/material.dart';
import 'package:presensi_app/pages/home-page.dart';
import 'package:presensi_app/pages/login-page.dart';
import 'package:presensi_app/pages/location-page.dart'; // Import LocationPage
import 'package:presensi_app/pages/register-page.dart';
import 'package:presensi_app/pages/riwayat-presensi-page.dart'; // Import RiwayatPresensiPage

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      initialRoute: '/',
      routes: {
        '/': (context) => LoginPage(),
        '/home': (context) => HomePage(),
        '/register': (context) => RegisterPage(),
        '/location': (context) {
          final args = ModalRoute.of(context)!.settings.arguments as Map<String, dynamic>;
          return LocationPage(tipe: args['tipe']);
        },
        '/riwayat': (context) => RiwayatPresensiPage(riwayat: []), // Route untuk RiwayatPresensiPage
      },
      theme: ThemeData(
        primarySwatch: Colors.red,
        // Tambahkan lebih banyak kustomisasi tema di sini jika diperlukan
      ),
      onUnknownRoute: (settings) {
        return MaterialPageRoute(
          builder: (context) => Scaffold(
            appBar: AppBar(title: Text('404')),
            body: Center(child: Text('Halaman tidak ditemukan')),
          ),
        );
      },
    );
  }
}
