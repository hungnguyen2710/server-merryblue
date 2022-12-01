<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppBaseController extends Controller
{
    protected function responseAPI($status, $message, $data, $status_code, $time_update = null)
    {
        $array = array(
            'status' => $status,
            'message' => $message,
            'results' => $data
        );
        return response()->json($array, $status_code);
    }

}
