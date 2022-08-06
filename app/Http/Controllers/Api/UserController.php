<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;

class UserController extends Controller
{
    
    public function register(Request $request) {
        $fullname = $request->input("fullname");
        $email = $request->input("email");
        $password = $request->input("password");

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

            // Create user
            $user = User::create([
                "fullname" => $fullname,
                "email" => $email,
                "profile_photo_path" => "users/profile/user_avatar.png",
                "password" => Hash::make($password)
            ]);

            // Response Success
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
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function login(Request $request) {

        try {

            $request->validate(
                [
                    "email" => "required|email",
                    "password" => "required"
                ],
                [
                    "email.required" => "Anda belum mengisi email",
                    "email.email" => "Anda mengisi format email yang salah",

                    "password.required" => "Anda belum mengisi password"
                ]
            );

            // Cek apakah email dan password benar
            $credentials = request(["email", "password"]);
            if (!Auth::attempt($credentials)) {
                // Jika salah kirim response error
                return ResponseApiFormatter::Error(null, 401, "Username atau password salah");
            }


            // Ambil data user berdasarkan email
            $user = User::where("email", $request->email)->first();

            // Cek apakah password dari request dan password punya user sama
            if (!Hash::check($request->password, $user->password)) {
                // Jika beda kirim response error
                return ResponseApiFormatter::Error(null, 401, "Username atau password salah");
            }

            /* === Fitur verifikasi email belum ada === */
            // Cek email sudah diverifikasi atau belum
            // if ($user->email_verified_at == null) {
            //     // Jika belum terverifikasi, kembalikan response error
            //     return ResponseApiFormatter::Error(null, 401, "Email belum terverifikasi, silahkan verifikasi email terlebih dahulu");
            // }


            // Buat token
            $token = $user->createToken("authToken")->plainTextToken;

            // Repsonse success
            $responseData = [
                "access_token" => $token,
                "token_type" => "Bearer",
                "user" => [
                    "id" => $user->id,
                    "fullname" => $user->fullname,
                    "email" => $user->email,
                    "profile_photo_path" => $user->profile_photo_path,
                    "profile_photo_url" => asset("") ."storage/". $user->profile_photo_path,
                    "created_at" => $user->created_at,
                    "updated_at" => $user->updated_at
                ],
                
            ];

            return ResponseApiFormatter::Success("Berhasil login", $responseData);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null,422,trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba lagi nanti");
        }


    }

    public function getUser(Request $request) {
        try {

            // Repsonse success
            $user = $request->user();
            $responseData = [
                   "id" => $user->id,
                   "fullname" => $user->fullname,
                   "email" => $user->email,
                   "profile_photo_path" => $user->profile_photo_path,
                   "profile_photo_url" => asset("") ."storage/". $user->profile_photo_path,
                   "created_at" => $user->created_at,
                   "updated_at" => $user->updated_at
               
           ];
           return ResponseApiFormatter::Success("Berhasil ambil data user", $responseData);

        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba lagi nanti");
        }
    }

    public function editUser(Request $request) {
        try {
            $request->validate(
                [
                    "fullname" => "required|regex:/^[\pL\s]+$/u|min:3|max:30"
                ],
                [
                    "fullname.required" => "Anda belum mengisi nama lengkap",
                    "fullname.regex" => "Nama lengkap harus berupa huruf",
                    "fullname.min" => "Nama lengkap minial 3 karakter",
                    "fullname.max" => "Nama lengkap maksimal 30 karakter"
                ]
            );

            // Ambil data user
            $user = $request->user();

            // Update fullname
            $user->update(["fullname" => $request->fullname]);

            // Response Success
            $responseData = [
                "id" => $user->id,
                "fullname" => $user->fullname,
                "email" => $user->email,
                "profile_photo_path" => $user->profile_photo_path,
                "profile_photo_url" => asset("") ."storage/". $user->profile_photo_path,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at
            
            ];
            return ResponseApiFormatter::Success("Berhasil edit user", $responseData);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }
    }

    public function editUserPhoto(Request $request) {
        try {
            $request->validate(
                [
                    "profile_photo" => "mimes:png,jpg,jpeg"
                ],
                [
                    "profile_photo.required" => "Anda belum mengupload foto",
                    "profile_photo.mimes" => "File harus berekstensi jpg, jpeg, atau png"
                ]
            );

            // Ambil data user
            $user = $request->user();

            // Cek apakah ada file profile_photo
            if ($request->file("profile_photo")) {

                // Hapus file lama, ketika photo profile bukan default avatar
                if ($user->profile_photo_path !== "users/profile/user_avatar.png") {
                    Storage::disk("public")->delete($user->profile_photo_path);
                }


                // Ambil file
                $file = $request->file("profile_photo");
            
                // Buat nama file
                $fileName = time() . "_userId(" . $user->id . ")." . $file->getClientOriginalExtension();

                // Simpan ke folder users/profile di dalam public
                $path = $file->storeAs("users/profile", $fileName, "public");

                // Ubah profile path user
                $user->profile_photo_path = $path;
                $user->update();
            }

            // Response Success
            $responseData = [
                "id" => $user->id,
                "fullname" => $user->fullname,
                "email" => $user->email,
                "profile_photo_path" => $user->profile_photo_path,
                "profile_photo_url" => asset("") ."storage/". $user->profile_photo_path,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at
            ];

            return ResponseApiFormatter::Success("Berhasil edit profile", $responseData);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }
    }

}
