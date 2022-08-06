<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    
    public function getSizes(Request $request) {
        try {
            // Ambil data user
            $user = $request->user();

            // Ambil data size berdasarkan user_id
            $sizes = Size::where("user_id", $user->id)->get();
            return ResponseApiFormatter::Success("Berhasil ambil data ukuran", $sizes);

        } catch(\Exception $err) {
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba beberapa saat lagi");
        }
    }

}
