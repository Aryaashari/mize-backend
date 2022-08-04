<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    
    public function getSizesByUserId(Request $request) {
        try {
            $userId = $request->input("user_id");
            if ($userId == null) {
                return ResponseApiFormatter::Error(null,404,"data ukuran tidak ada");
            }
            $sizes = Size::where("user_id", $userId)->get();
            return ResponseApiFormatter::Success("get data success!", $sizes);

        } catch(\Exception $err) {
            return ResponseApiFormatter::Error(null,500,"server error");
        }
    }

}
