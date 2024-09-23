//import 'dart:convert';

class LoginResponseModel {
  final bool success;
  final String message;
  final Data? data; // Make data nullable

  LoginResponseModel({
    required this.success,
    required this.message,
    this.data, // Initialize data as nullable
  });

  factory LoginResponseModel.fromJson(Map<String, dynamic> json) {
    return LoginResponseModel(
      success: json['success'] ?? false,
      message: json['message'] ?? '',
      data: json['data'] != null ? Data.fromJson(json['data']) : null, // Handle null data
    );
  }

  Map<String, dynamic> toJson() => {
        'success': success,
        'message': message,
        'data': data?.toJson(), // Handle null data
      };
}

class Data {
  final String token;
  final String name;

  Data({
    required this.token,
    required this.name,
  });

  factory Data.fromJson(Map<String, dynamic> json) {
    return Data(
      token: json['token'] ?? '',
      name: json['name'] ?? '',
    );
  }

  Map<String, dynamic> toJson() => {
        'token': token,
        'name': name,
      };
}
