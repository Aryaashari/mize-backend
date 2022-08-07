<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
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

    public function detailSize(Size $size) {
        try {
            return ResponseApiFormatter::Success("Berhasil ambil data detail ukuran", $size);
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }
    }

    public function updateSize(Request $request, Size $size) {

        try {
            $request->validate(
                [
                    "name" => "required",
                    "panjang_badan" => "required|numeric|between:0.1,999.999",
                    "lebar_bahu" => "required|numeric|between:0.1,999.999",
                    "panjang_lengan" => "required|numeric|between:0.1,999.999",
                    "lingkar_lengan" => "required|numeric|between:0.1,999.999",
                    "lingkar_perut" => "required|numeric|between:0.1,999.999",
                    "lingkar_dada" => "required|numeric|between:0.1,999.999",
                    "lingkar_pesak" => "required|numeric|between:0.1,999.999",
                    "lingkar_panggul" => "required|numeric|between:0.1,999.999",
                    "lingkar_paha" => "required|numeric|between:0.1,999.999",
                    "lingkar_lutut" => "required|numeric|between:0.1,999.999",
                    "lingkar_tumit" => "required|numeric|between:0.1,999.999",
                    "panjang_celana" => "required|numeric|between:0.1,999.999",
                ],

                [
                    "name.required" => "Anda belum mengisi nama ukuran",
                    "panjang_badan.required" => "Anda belum mengisi panjang badan",
                    "lebar_bahu.required" => "Anda belum mengisi lebar bahu",
                    "panjang_lengan.required" => "Anda belum mengisi panjang lengan",
                    "lingkar_lengan.required" => "Anda belum mengisi lingkar lengan",
                    "lingkar_perut.required" => "Anda belum mengisi lingkar perut",
                    "lingkar_dada.required" => "Anda belum mengisi lingkar dada",
                    "lingkar_pesak.required" => "Anda belum mengisi lingkar pesak",
                    "lingkar_panggul.required" => "Anda belum mengisi lingkar panggul",
                    "lingkar_paha.required" => "Anda belum mengisi lingkar paha",
                    "lingkar_lutut.required" => "Anda belum mengisi lingkar lutut",
                    "lingkar_tumit.required" => "Anda belum mengisi lingkar tumit",
                    "panjang_celana.required" => "Anda belum mengisi panjang celana",

                    "panjang_badan.numeric" => "Panjang badan harus berupa angka",
                    "lebar_bahu.numeric" => "Lebar bahu harus berupa angka",
                    "panjang_lengan.numeric" => "Panjang lengan harus berupa angka",
                    "lingkar_lengan.numeric" => "Lingkar lengan harus berupa angka",
                    "lingkar_perut.numeric" => "Lingkar perut harus berupa angka",
                    "lingkar_dada.numeric" => "Lingkar dada harus berupa angka",
                    "lingkar_pesak.numeric" => "Lingkar pesak harus berupa angka",
                    "lingkar_panggul.numeric" => "Lingkar panggul harus berupa angka",
                    "lingkar_paha.numeric" => "Lingkar paha harus berupa angka",
                    "lingkar_lutut.numeric" => "Lingkar lutut harus berupa angka",
                    "lingkar_tumit.numeric" => "Lingkar tumit harus berupa angka",
                    "panjang_celana.numeric" => "Panjang celana harus berupa angka",
                    

                    "panjang_badan.between" => "Panjang badan harus di rentang 0.1 - 999.999",
                    "lebar_bahu.between" => "Lebar bahu harus di rentang 0.1 - 999.999",
                    "panjang_lengan.between" => "Panjang lengan harus di rentang 0.1 - 999.999",
                    "lingkar_lengan.between" => "Lingkar lengan harus di rentang 0.1 - 999.999",
                    "lingkar_perut.between" => "Lingkar perut harus di rentang 0.1 - 999.999",
                    "lingkar_dada.between" => "Lingkar dada harus di rentang 0.1 - 999.999",
                    "lingkar_pesak.between" => "Lingkar pesak harus di rentang 0.1 - 999.999",
                    "lingkar_panggul.between" => "Lingkar panggul harus di rentang 0.1 - 999.999",
                    "lingkar_paha.between" => "Lingkar paha harus di rentang 0.1 - 999.999",
                    "lingkar_lutut.between" => "Lingkar lutut harus di rentang 0.1 - 999.999",
                    "lingkar_tumit.between" => "Lingkar tumit harus di rentang 0.1 - 999.999",
                    "panjang_celana.between" => "Panjang celana harus di rentang 0.1 - 999.999",
                ]
            );

            // Update data size
            $size->name = $request->name;
            $size->note = $request->note;
            $size->panjang_badan = $request->panjang_badan;
            $size->lebar_bahu = $request->lebar_bahu;
            $size->panjang_lengan = $request->panjang_lengan;
            $size->lingkar_lengan = $request->lingkar_lengan;
            $size->lingkar_perut = $request->lingkar_perut;
            $size->lingkar_dada = $request->lingkar_dada;
            $size->lingkar_pesak = $request->lingkar_pesak;
            $size->lingkar_panggul = $request->lingkar_panggul;
            $size->lingkar_paha = $request->lingkar_paha;
            $size->lingkar_lutut = $request->lingkar_lutut;
            $size->lingkar_tumit = $request->lingkar_tumit;
            $size->panjang_celana = $request->panjang_celana;
            $size->update();

            // Response Success
            return ResponseApiFormatter::Success("Berhasil update data ukuran", $size);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function deleteSize(Size $size) {

        try {
            // Delete size
            $size->delete();

            // Response Success
            return ResponseApiFormatter::Success("Berhasil hapus data ukuran");
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

}
