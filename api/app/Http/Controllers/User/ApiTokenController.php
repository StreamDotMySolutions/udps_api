<?php

namespace App\Http\Controllers\User; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    
    public function index(Request $request)
    {
        return $request->user()->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                //'abilities' => $token->abilities,
                'created_at' => $token->created_at,
                'last_used_at' => $token->last_used_at,
            ];
        });
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'sometimes|array',
        ]);

        $token = $request->user()->createToken(
            $request->name,
            $request->abilities ?? ['*']
        );

        return response()->json([
            'name' => $request->input('name'),
            'token' => $token->plainTextToken // ONLY available here
        ]);
    }



}
