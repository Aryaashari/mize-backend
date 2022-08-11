<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseApiFormatter;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Size;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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

    public function getDetailGroup(Request $request, Group $group) {

        try {

            // Panggil semua data ukuran yang dimiliki group
            $group->sizes;
    
            // Response Success
            return ResponseApiFormatter::Success("Berhasil ambil detail group", $group);
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null, 500, "Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

    public function createGroup(Request $request) {

        try {

            $request->validate(
                [
                    "group_name" => "required",
                    "size_ids" => "required|array",
                    "size_ids.*" => "integer",
                    "due_dates" => "nullable|date_format:Y-m-d H:i:s"
                ],
                [
                    "group_name.required" => "Anda belum mengisi nama grup",
                    "size_ids.required" => "Anda belum menambahkan data ukuran",
                    "size_ids.array" => "Data id ukuran harus berupa array",
                    "due_dates.date_format" => "Format waktu [tahun-bulan-hari jam-menit-detik]"
                ]
            );

            // Ambil semua id data ukuran
            $sizes = Size::pluck("id")->toArray();

            // Looping semua element size_ids
            foreach($request->size_ids as $id) {
                // Cek apakah id tidak terdapat di database?
                if (!in_array($id, $sizes)) {
                    // Response error
                    return ResponseApiFormatter::Error(null, 422, "Terdapat id ukuran yang tidak valid");
                }
            }

            // Create group
            $group = Group::create([
                "user_id" => $request->user()->id,
                "name_group" => $request->group_name,
                "due_dates" => $request->due_dates
            ]);

            // Tambahkan data pivot
            $group->sizes()->attach($request->size_ids);

            // Response success
            $responseGroup = [
                "id" => $group->id,
                "user_id" => $group->user_id,
                "group_name" => $group->name_group,
                "due_dates" => $group->due_dates,
                "created_at" => $group->created_at,
                "updated_at" => $group->updated_at,
                "sizes" => $group->sizes
            ];

            return ResponseApiFormatter::Success("Berhasil tambah data group", $responseGroup);


        }catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        }catch(\Exception $error) {
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba lagi nanti");
        }
    }

    public function updateGroup(Request $request, Group $group) {

        try {
            $request->validate(
                [
                    "group_name" => "required",
                    "size_ids" => "required|array",
                    "size_ids.*" => "integer",
                    "due_dates" => "nullable|date_format:Y-m-d H:i:s"
                ],
                [
                    "group_name.required" => "Anda belum mengisi nama grup",
                    "size_ids.required" => "Anda belum menambahkan data ukuran",
                    "size_ids.array" => "Data id ukuran harus berupa array",
                    "due_dates.date_format" => "Format waktu [tahun-bulan-hari jam-menit-detik]"
                ]
            );
    
            // Ambil semua id data ukuran
            $sizes = Size::pluck("id")->toArray();
    
            // Looping semua element size_ids
            foreach($request->size_ids as $id) {
                // Cek apakah id tidak terdapat di database?
                if (!in_array($id, $sizes)) {
                    // Response error
                    return ResponseApiFormatter::Error(null, 422, "Terdapat id ukuran yang tidak valid");
                }
            }
    
    
            // Update group
            $group->name_group = $request->group_name;
            $group->due_dates = $request->due_dates;
            $group->update();
    
            // Hapus semua id ukuran di tabel pivot
            $group->sizes()->detach();
    
            // Tambahkan semua id ukuran pada tabel pivot
            $group->sizes()->attach($request->size_ids);
    
    
            // Response success
            $responseGroup = [
                "id" => $group->id,
                "user_id" => $group->user_id,
                "group_name" => $group->name_group,
                "due_dates" => $group->due_dates,
                "created_at" => $group->created_at,
                "updated_at" => $group->updated_at,
                "sizes" => $group->sizes
            ];
    
            return ResponseApiFormatter::Success("Berhasil tambah data group", $responseGroup);
        } catch(ValidationException $error) {
            $errMessage = explode("(", $error->getMessage());
            return ResponseApiFormatter::Error(null, 422, trim($errMessage[0]));
        } catch(\Exception $error) {
            return ResponseApiFormatter::Error(null,500,"Sistem sedang bermasalah, silahkan coba lagi nanti");
        }

    }

}
