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

    public function getDetailPriority(Priority $priority) {

        try {

            // Ambil data ukuran berdasarkan prioritas
            $priority->size;

            // Response Success
            return ResponseApiFormatter::Success("Berhasil ambil data detail prioritas", [
                "priority" => $priority
            ]);

        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function createPriority(Request $request) {
        
        try {

            $request->validate(
                [
                    "size_id" => "required",
                    "due_dates" => "required|date_format:Y-m-d H:i:s"
                ],
                [
                    "due_dates.required" => "Anda belum menambahkan tenggat waktu",
                    "due_dates.date_format" => "Format waktu [tahun-bulan-tanggal jam:menit:detik]"
                ]
            );

            // Ambil data ukuran
            $size = Size::where("id", $request->size_id)->first();

            // Cek apakah data ukuran sudah memiliki prioritas
            if ($size->priority != null) {
                // Jika sudah maka kembalikan error
                return ResponseApiFormatter::Error(null, 422, "Data ukuran telah memiliki prioritas");
            }

            // Buat data prioritas
            $priority = Priority::create([
                "size_id" => $request->size_id,
                "due_dates" => $request->due_dates 
            ]);

            // Response Success
            $responsePriority = [
                "id" => $priority->id,
                "size_id" => $priority->size_id,
                "due_dates" => $priority->due_dates,
                "created_at" => $priority->created_at,
                "updated_at" => $priority->updated_at,
                "size" => $size
            ];

            return ResponseApiFormatter::Success("Berhasil tambah data prioritas", [
                "priority" => $responsePriority
            ]);


        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null,422,trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function updatePriority(Request $request, Priority $priority) {

        try {
            $request->validate(
                [
                    "due_dates" => "required|date_format:Y-m-d H:i:s"
                ],
                [
                    "due_dates.required" => "Anda belum menambahkan tenggat waktu",
                    "due_dates.date_format" => "Format waktu [tahun-bulan-hari jam-menit-detik]"
                ]
            );
    
            // Update data prioritas
            $priority->due_dates = $request->due_dates;
            $priority->update();
    
            // Ambil data ukuran berdasarkan prioritas
            $priority->size;
    
            // Response Success
            return ResponseApiFormatter::Success("Berhasil update data prioritas", [
                "priority" => $priority
            ]);
        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang error, silahkan coba lagi nanti");
        }

    }

    public function deletePriority(Priority $priority) {

        try {
            // Hapus data priority
            $priority->delete();
    
            // Response Success
            return ResponseApiFormatter::Success("Berhasil hapus data prioritas");
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang error, silahkan coba lagi nanti");
        }

    }


}
