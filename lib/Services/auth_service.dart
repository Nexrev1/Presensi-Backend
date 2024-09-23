import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:presensi_app/models/login-response.dart';

class AuthService {
  final String loginUrl = 'http://10.0.2.2:8000/api/login';

  Future<LoginResponseModel> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse(loginUrl),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'email': email, 'password': password}),
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      if (response.statusCode == 200) {
        // Decode JSON and create a LoginResponseModel instance
        final Map<String, dynamic> responseData = jsonDecode(response.body);
        return LoginResponseModel.fromJson(responseData);
      } else {
        // Extract and print error message from response
        final Map<String, dynamic> responseData = jsonDecode(response.body);
        final String errorMessage = responseData['message'] ?? 'Unknown error';
        throw Exception('Failed to login: $errorMessage');
      }
    } catch (e) {
      print('Error: $e');
      throw Exception('An error occurred during login: ${e.toString()}');
    }
  }
}
