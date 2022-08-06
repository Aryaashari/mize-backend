<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SizeController extends Controller
{
    
    public function getSizes(Request $request) {
        try {
            // Ambil data user
            $user = $request->user();

            // Ambil data size berdasarkan user_id
            $sizes = Size::where("user_id", $user->id);

            // Cek apakah ada query parameter search
            if($request->search) {
                $sizes = $sizes->where("name", "LIKE", "%". $request->search ."%");
            }
            
            $sizes = $sizes->get();

            return ResponseApiFormatter::Success("Berhasil ambil data ukuran", $sizes);

        } catch(\Exception $err) {
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba beberapa saat lagi");
        }
    }

    public function createSize(Request $request) {

        try {
            $request->validate(
                [
                    "name" => "required"
                ],
                [
                    "name.required" => "Anda belum mengisi nama ukuran"
                ]
            );

            // Ambil data user
            $user = $request->user();

            // Buat data ukuran
            $size = Size::create([
                "user_id" => $user->id,
                "name" => $request->name,
                "note" => $request->note,
                "panjang_badan" => $request->panjang_badan,
                "lebar_bahu" => $request->lebar_bahu,
                "panjang_lengan" => $request->panjang_lengan,
                "lingkar_lengan" => $request->lingkar_lengan,
                "lingkar_perut" => $request->lingkar_perut,
                "lingkar_dada" => $request->lingkar_dada,
                "lingkar_pesak" => $request->lingkar_pesak,
                "lingkar_panggul" => $request->lingkar_panggul,
                "lingkar_paha" => $request->lingkar_paha,
                "lingkar_lutut" => $request->lingkar_lutut,
                "lingkar_tumit" => $request->lingkar_tumit,
                "panjang_celana" => $request->panjang_celana,
            ]);

            // Response Success
            $responseSize = [
                "id" => $size->id,
                "user_id" => $user->id,
                "name" => $size->name,
                "note" => $size->note,
                "panjang_badan" => $size->panjang_badan,
                "lebar_bahu" => $size->lebar_bahu,
                "panjang_lengan" => $size->panjang_lengan,
                "lingkar_lengan" => $size->lingkar_lengan,
                "lingkar_perut" => $size->lingkar_perut,
                "lingkar_dada" => $size->lingkar_dada,
                "lingkar_pesak" => $size->lingkar_pesak,
                "lingkar_panggul" => $size->lingkar_panggul,
                "lingkar_paha" => $size->lingkar_paha,
                "lingkar_lutut" => $size->lingkar_lutut,
                "lingkar_tumit" => $size->lingkar_tumit,
                "panjang_celana" => $size->panjang_celana,
            ];
            return ResponseApiFormatter::Success("Berhasil tambah data ukuran", $responseSize);

        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Server sedang bermasalah, silahkan coba lagi nanti");
        }

    }

}
