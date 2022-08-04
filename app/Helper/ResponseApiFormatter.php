<?php

namespace App\Helper;


class ResponseApiFormatter {

    private static $response = [
        "meta" => [
            "status" => "success",
            "code" => 200,
            "message" => null
        ],
        "data" => null
    ];


    public static function Success($message = null, $data = null) {
        self::$response["meta"]["message"] = $message;
        self::$response["data"] = $data;

        return response()->json(self::$response, self::$response["meta"]["code"]);
    }


    public static function Error($data = null, $code = null, $message = null) {
        self::$response["meta"]["status"] = "error";
        self::$response["meta"]["code"] = $code;
        self::$response["meta"]["message"] = $message;
        self::$response["data"] = $data;

        return response()->json(self::$response, self::$response["meta"]["code"]);
    }

}