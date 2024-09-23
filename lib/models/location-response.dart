import 'dart:convert';

// Model untuk menyimpan informasi lokasi
class LocationResponseModel {
  LocationResponseModel({
    required this.success,
    this.address,
    this.message,
  });

  bool success;
  String? address;
  String? message;

  // Factory constructor untuk membuat instance dari JSON
  factory LocationResponseModel.fromJson(Map<String, dynamic> json) {
    return LocationResponseModel(
      success: json["success"] ?? false,
      address: json["address"] as String?,
      message: json["message"] as String?,
    );
  }

  // Method untuk mengkonversi instance ke JSON
  Map<String, dynamic> toJson() => {
        "success": success,
        "address": address,
        "message": message,
      };
}

// Fungsi untuk mengkonversi data JSON menjadi model
LocationResponseModel locationResponseModelFromJson(String str) {
  final jsonData = json.decode(str);
  return LocationResponseModel.fromJson(jsonData);
}

// Fungsi untuk mengkonversi model menjadi data JSON
String locationResponseModelToJson(LocationResponseModel data) =>
    json.encode(data.toJson());
