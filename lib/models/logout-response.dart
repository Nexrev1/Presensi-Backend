import 'dart:convert';

LogoutResponseModel logoutResponseModelFromJson(String str) =>
    LogoutResponseModel.fromJson(json.decode(str));

String logoutResponseModelToJson(LogoutResponseModel data) =>
    json.encode(data.toJson());

class LogoutResponseModel {
  LogoutResponseModel({
    required this.success,
    required this.message,
  });

  bool success;
  String message;

  factory LogoutResponseModel.fromJson(Map<String, dynamic> json) =>
      LogoutResponseModel(
        success: json["success"],
        message: json["message"],
      );

  Map<String, dynamic> toJson() => {
        "success": success,
        "message": message,
      };
}
