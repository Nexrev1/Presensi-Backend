import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

// Model untuk respons dari API
class HomeResponse {
  final bool success;
  final String message;
  final List<PresensiData> data;
  final bool requiresLocationPage;

  HomeResponse({
    required this.success,
    required this.message,
    required this.data,
    this.requiresLocationPage = false, // Tambahkan parameter ini
  });

  // Membuat instance HomeResponse dari JSON
  factory HomeResponse.fromJson(Map<String, dynamic> json) {
    var list = json['data'] as List<dynamic>;
    List<PresensiData> presensiDataList = list.map((i) => PresensiData.fromJson(i as Map<String, dynamic>)).toList();

    return HomeResponse(
      success: json['success'] ?? false,
      message: json['message'] ?? '',
      data: presensiDataList,
      requiresLocationPage: json['requires_location_page'] ?? false, // Ambil nilai dari JSON
    );
  }
}

// Model untuk data presensi
class PresensiData {
  final int id;
  final int userId;
  final String latitude;
  final String longitude;
  final DateTime tanggal;
  final String? masuk;
  final String? pulang;
  final String ipAddress;
  final String userAgent;
  final String location;
  final DateTime createdAt;
  final DateTime updatedAt;
  final bool isHariIni;

  PresensiData({
    required this.id,
    required this.userId,
    required this.latitude,
    required this.longitude,
    required this.tanggal,
    this.masuk,
    this.pulang,
    required this.ipAddress,
    required this.userAgent,
    required this.location,
    required this.createdAt,
    required this.updatedAt,
    required this.isHariIni,
  });

  // Membuat instance PresensiData dari JSON
  factory PresensiData.fromJson(Map<String, dynamic> json) {
    return PresensiData(
      id: json['id'] as int,
      userId: json['user_id'] as int,
      latitude: json['latitude'] as String,
      longitude: json['longitude'] as String,
      tanggal: DateTime.parse(json['tanggal'] as String),
      masuk: json['masuk'] as String?,
      pulang: json['pulang'] as String?,
      ipAddress: json['ip_address'] as String,
      userAgent: json['user_agent'] as String,
      location: json['location'] as String,
      createdAt: DateTime.parse(json['created_at'] as String),
      updatedAt: DateTime.parse(json['updated_at'] as String),
      isHariIni: json['is_hari_ini'] as bool,
    );
  }

  // Mendapatkan jam masuk dalam format HH:mm
  String? get jamMasuk {
    if (masuk != null) {
      try {
        DateTime masukDateTime = DateTime.parse(masuk!);
        return DateFormat('HH:mm').format(masukDateTime);
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  // Mendapatkan jam pulang dalam format HH:mm
  String? get jamPulang {
    if (pulang != null) {
      try {
        DateTime pulangDateTime = DateTime.parse(pulang!);
        return DateFormat('HH:mm').format(pulangDateTime);
      } catch (e) {
        return null;
      }
    }
    return null;
  }

  // Mendapatkan waktu pembuatan dalam format 'yyyy-MM-dd – kk:mm'
  String getFormattedCreatedAt() {
    return DateFormat('yyyy-MM-dd – kk:mm').format(createdAt);
  }

  // Mendapatkan waktu pembaharuan dalam format 'yyyy-MM-dd – kk:mm'
  String getFormattedUpdatedAt() {
    return DateFormat('yyyy-MM-dd – kk:mm').format(updatedAt);
  }

  // Menyederhanakan pemeriksaan apakah sudah melakukan absensi masuk
  bool get isMasuk => masuk != null;

  // Menyederhanakan pemeriksaan apakah sudah melakukan absensi pulang
  bool get isPulang => pulang != null;
}
