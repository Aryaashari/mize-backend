<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PriorityController extends Controller
{
    

    public function getAllPriority(Request $request) {

        try {
            // Ambil data user
            $user = $request->user();

            // Ambil data ukuran yang dimiliki user
            $sizes = $user->sizes;

            // Tampung data priority berdasarkan ukuran yang dimiliki user
            $priorities = [];

            // Tampung data ukuran yang tidak memiliki prioritas
            $sizeWithoutPriority = [];
            
            foreach($sizes as $size) {
                if ($size->priority != null) {
                    $priorities[] = $size->priority;
                } else {
                    $sizeWithoutPriority[] = $size;
                }
            }

            // Jika ada request param size dan nilai false
            if ($request->size && $request->size === "false") {
                // Kembalikan response data ukuran yang tidak memiliki prioritas
                return ResponseApiFormatter::Success("Berhasil ambil data ukuran yang tidak memiliki prioritas", $sizeWithoutPriority);
            }

            // Response success
            return ResponseApiFormatter::Success("Berhasil ambil data prioritas", $priorities);
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function getDetailPriority(Request $request, Size $size) {

        try {

            // Ambil data priority berdasarkan ukuran
            $priority = Priority::where("size_id", $size->id)->first();

            // Response Success
            return ResponseApiFormatter::Success("Berhasil ambil data detail prioritas", [
                "size" => $size,
                "priority" => $priority
            ]);

        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function createPriority(Request $request, Size $size) {
        
        try {

            $request->validate(
                [
                    "due_dates" => "required|date_format:Y-m-d H:i:s"
                ],
                [
                    "due_dates.required" => "Anda belum menambahkan tenggat waktu",
                    "due_dates.date_format" => "Format waktu [tahun-bulan-tanggal jam:menit:detik]"
                ]
            );

            // Cek apakah data ukuran sudah memiliki prioritas
            if ($size->priority != null) {
                // Jika sudah maka kembalikan error
                return ResponseApiFormatter::Error(null, 422, "Data ukuran telah memiliki prioritas");
            }

            // Buat data prioritas
            $priority = Priority::create([
                "size_id" => $size->id,
                "due_dates" => $request->due_dates 
            ]);

            // Response Success
            $responsePriority = [
                "id" => $priority->id,
                "size_id" => $priority->size_id,
                "due_dates" => $priority->due_dates,
                "created_at" => $priority->created_at,
                "updated_at" => $priority->updated_at
            ];

            return ResponseApiFormatter::Success("Berhasil tambah data prioritas", [
                "size" => $size,
                "priority" => $responsePriority
            ]);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null,422,trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }


}
