<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function register(Request $request) {
        $fullname = $request->input("fullname");
        $email = $request->input("email");
        $password = $request->input("password");
        // $confirm_password = $request->input("confirm_password");

        try {

            $request->validate(
                [
                    "fullname" => "required|regex:/^[\pL\s]+$/u|min:3|max:30",
                    "email" => "required|email|unique:users,email",
                    "password" => "required|min:8|confirmed"
                ],
                [
                    "fullname.required" => "Anda belum mengisi nama lengkap",
                    "fullname.regex" => "Nama lengkap harus berupa huruf",
                    "fullname.min" => "Nama lengkap minimal 3 karakter",
                    "fullname.max" => "Nama lengkap maksimal 30 karakter",
    
                    "email.required" => "Anda belum mengisi email",
                    "email.email" => "Anda mengisi format email yang salah",
                    "email.unique" => "Email telah terdaftar",
    
                    "password.required" => "Anda belum mengisi password",
                    "password.min" => "Password minimal 8 karakter",
                    "password.confirmed" => "Konfirmasi password tidak sesuai",
                ]
            );

            $user = User::create([
                "fullname" => $fullname,
                "email" => $email,
                "password" => Hash::make($password)
            ]);

            $responseData = [
                "id" => $user->id,
                "fullname" => $user->fullname,
                "email" => $user->email,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at
            ];

            return ResponseApiFormatter::Success("Berhasil tambah data user", $responseData);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null,422,trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null,500,$error);
        }

    }

}
