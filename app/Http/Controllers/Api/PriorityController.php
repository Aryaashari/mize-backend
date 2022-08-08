<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Priority;
use Illuminate\Http\Request;

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


}
