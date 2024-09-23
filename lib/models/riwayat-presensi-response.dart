import 'dart:convert';

class RiwayatPresensiResponse {
  final bool success;
  final String message;
  final List<Datum> data;

  RiwayatPresensiResponse({
    required this.success,
    required this.message,
    required this.data,
  });

  factory RiwayatPresensiResponse.fromJson(Map<String, dynamic> json) {
    try {
      return RiwayatPresensiResponse(
        success: json['success'] ?? false,
        message: json['message'] ?? '',
        data: json['data'] != null
            ? List<Datum>.from(json['data'].map((x) => Datum.fromJson(x)))
            : [],
      );
    } catch (e) {
      // Log or handle parsing error here
      print('Error parsing RiwayatPresensiResponse: $e');
      return RiwayatPresensiResponse(
        success: false,
        message: 'Failed to parse response',
        data: [],
      );
    }
  }

  Map<String, dynamic> toJson() {
    return {
      'success': success,
      'message': message,
      'data': data.map((x) => x.toJson()).toList(),
    };
  }
}

class Datum {
  final int id;
  final int userId;
  final String? latitude;
  final String? longitude;
  final String tanggal;
  final String? masuk;
  final String? pulang;
  final String? ipAddress;
  final String? userAgent;
  final String? location;
  final DateTime createdAt;
  final DateTime updatedAt;
  final bool isHariIni;
  final bool isIzinApproved; // Tambahkan properti baru

  Datum({
    required this.id,
    required this.userId,
    this.latitude,
    this.longitude,
    required this.tanggal,
    this.masuk,
    this.pulang,
    this.ipAddress,
    this.userAgent,
    this.location,
    required this.createdAt,
    required this.updatedAt,
    required this.isHariIni,
    required this.isIzinApproved, // Tambahkan ini ke konstruktor
  });

  factory Datum.fromJson(Map<String, dynamic> json) {
    try {
      return Datum(
        id: json['id'],
        userId: json['user_id'],
        latitude: json['latitude'],
        longitude: json['longitude'],
        tanggal: json['tanggal'],
        masuk: json['masuk'],
        pulang: json['pulang'],
        ipAddress: json['ip_address'],
        userAgent: json['user_agent'],
        location: json['location'],
        createdAt: DateTime.tryParse(json['created_at'] ?? '') ?? DateTime.now(),
        updatedAt: DateTime.tryParse(json['updated_at'] ?? '') ?? DateTime.now(),
        isHariIni: json['is_hari_ini'] ?? false,
        isIzinApproved: json['is_izin_approved'] ?? false, // Tambahkan parsing ini
      );
    } catch (e) {
      // Log or handle parsing error here
      print('Error parsing Datum: $e');
      return Datum(
        id: 0,
        userId: 0,
        tanggal: '',
        createdAt: DateTime.now(),
        updatedAt: DateTime.now(),
        isHariIni: false,
        isIzinApproved: false, // Tambahkan ini sebagai fallback
      );
    }
  }

  get status => null;

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'user_id': userId,
      'latitude': latitude,
      'longitude': longitude,
      'tanggal': tanggal,
      'masuk': masuk,
      'pulang': pulang,
      'ip_address': ipAddress,
      'user_agent': userAgent,
      'location': location,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt.toIso8601String(),
      'is_hari_ini': isHariIni,
      'is_izin_approved': isIzinApproved, // Tambahkan ini
    };
  }
}


