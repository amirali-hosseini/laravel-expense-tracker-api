<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function response(array $data, bool $result, int $status = 200)
    {
        $data['result'] = $result;

        return response()->json($data, $status);
    }
}
