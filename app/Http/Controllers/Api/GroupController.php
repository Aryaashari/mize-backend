<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    
    public function getGroup(Request $request) {

        try {
            // Ambil data user
            $user = $request->user();

            // Ambil data groups yang dimiliki user
            $groups = Group::with(["sizes"])->where("user_id", $user->id)->get();

            // Response Success
            return ResponseApiFormatter::Success("Berhasil ambil data group", $groups);
            
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

}
