import 'dart:convert';

class IzinResponse {
  final bool success;
  final String message;
  final IzinData? data;

  IzinResponse({
    required this.success,
    required this.message,
    this.data,
  });

  factory IzinResponse.fromJson(Map<String, dynamic> json) {
    return IzinResponse(
      success: json['success'] == true || json['success'] == 1,
      message: json['message'] ?? '',
      data: json['data'] != null ? IzinData.fromJson(json['data']) : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'success': success,
      'message': message,
      'data': data?.toJson(),
    };
  }
}

class IzinData {
  final int id;
  final String jenisIzin;
  final String tanggalMulai;
  final String tanggalSelesai;
  final String? dokumen; // Dokumen bisa null
  final String status;

  IzinData({
    required this.id,
    required this.jenisIzin,
    required this.tanggalMulai,
    required this.tanggalSelesai,
    this.dokumen, // Dokumen bisa null
    required this.status,
  });

  factory IzinData.fromJson(Map<String, dynamic> json) {
    return IzinData(
      id: json['id'] ?? 0,
      jenisIzin: json['jenis_izin'] ?? '',
      tanggalMulai: json['tanggal_mulai'] ?? '',
      tanggalSelesai: json['tanggal_selesai'] ?? '',
      dokumen: json['dokumen'] as String?, // Pastikan ini bisa null
      status: json['status'] ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'jenis_izin': jenisIzin,
      'tanggal_mulai': tanggalMulai,
      'tanggal_selesai': tanggalSelesai,
      'dokumen': dokumen,
      'status': status,
    };
  }
}
