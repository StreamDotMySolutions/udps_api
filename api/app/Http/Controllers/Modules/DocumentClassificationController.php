<?php

namespace App\Http\Controllers\Modules;
use App\Http\Controllers\Controller; 

use Illuminate\Http\Request;

class DocumentClassificationController extends Controller
{
    public function identify(Request $request)
    {

        /*
        * To identify the type of docuemnt being sent by user
        */

        return response()->json([
            'message' => 'Document identification',
        ]);
    }
}
