<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Validation\Validator;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * {@inheritdoc}
     */
    protected function formatValidationErrors(Validator $validator)
    {
        //return $validator->errors()->all();
        echo json_encode ([
            'code' => 0,
            'message' => $validator->errors()->all()
        ]);exit;
    }

}
