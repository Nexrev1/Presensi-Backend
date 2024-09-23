import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:presensi_app/models/riwayat-presensi-response.dart'; // Sesuaikan dengan model yang digunakan

class RiwayatPresensiPage extends StatelessWidget {
  final List<Datum> riwayat; // Gunakan model Datum

  RiwayatPresensiPage({required this.riwayat});

  @override
  Widget build(BuildContext context) {
    // Mendapatkan ukuran layar
    final screenWidth = MediaQuery.of(context).size.width;

    return Scaffold(
      appBar: AppBar(
        backgroundColor: Color.fromARGB(255, 151, 1, 26), // Warna merah khas Chinese
        title: Text(
          'Riwayat Presensi',
          style: TextStyle(color: Colors.white), // Mengubah warna teks menjadi putih
        ),
        iconTheme: IconThemeData(color: Colors.white), // Mengubah warna ikon menjadi putih
      ),
      body: Stack(
        children: [
          Positioned.fill(
            child: Image.asset(
              'assets/image/background3.jpg',
              fit: BoxFit.cover,
              color: Colors.black.withOpacity(0.5), // Warna overlay untuk kontras
              colorBlendMode: BlendMode.darken,
            ),
          ),
          riwayat.isEmpty
              ? Center(
                  child: Container(
                    padding: EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.8),
                      borderRadius: BorderRadius.circular(8),
                      boxShadow: [BoxShadow(color: Colors.black26, blurRadius: 8, offset: Offset(0, 4))],
                    ),
                    child: Text(
                      'Tidak ada data presensi.',
                      style: TextStyle(fontSize: 18, color: Colors.grey),
                    ),
                  ),
                )
              : ListView.builder(
                  itemCount: riwayat.length,
                  itemBuilder: (context, index) {
                    final presensi = riwayat[index];
                    String formattedDate = DateFormat('EEEE, dd MMM yyyy').format(presensi.createdAt); // Format hari, tanggal, tahun

                    return Card(
                      margin: EdgeInsets.symmetric(vertical: 8, horizontal: 10),
                      elevation: 8,
                      color: Color(0xFFFFF3F3), // Warna latar belakang kartu
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Row(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Kiri Card
                            Expanded(
                              flex: 3,
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    formattedDate,
                                    style: TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: screenWidth > 600 ? 16 : 14, // Ukuran font responsif
                                      color: Color(0xFFC8102E), // Warna merah untuk tanggal
                                    ),
                                  ),
                                  SizedBox(height: 10),
                                  Text(
                                    'Lokasi Kampus: ${presensi.location ?? 'Unknown Location'}',
                                    style: TextStyle(
                                      fontSize: screenWidth > 600 ? 14 : 12, // Ukuran font responsif
                                      color: Colors.grey[800],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            SizedBox(width: 10),
                            // Kanan Card
                            Expanded(
                              flex: 2,
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                    children: [
                                      _buildTimeBox('Masuk', presensi.masuk, Colors.green),
                                      _buildTimeBox('Pulang', presensi.pulang, Colors.red),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
        ],
      ),
    );
  }

  Widget _buildTimeBox(String label, String? time, Color color) {
    return Expanded(
      child: Column(
        children: [
          Text(
            label,
            style: TextStyle(
              fontSize: 12, // Ukuran font label
              color: color,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(height: 4),
          Text(
            time ?? '-',
            style: TextStyle(
              fontSize: 20, // Ukuran font waktu
              color: color,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }
}
