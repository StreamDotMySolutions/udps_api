<?php

namespace App\Http\Controllers\User; 

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    
    // public function index(Request $request)
    // {
    //     return $request->user()->tokens->map(function ($token) {
    //         return [
    //             'id' => $token->id,
    //             'name' => $token->name,
    //             'api_key' => $token->api_key,
    //             'created_at' => $token->created_at,
    //             'last_used_at' => $token->last_used_at,
    //         ];
    //     });
    // }

    public function index(Request $request)
    {
        $currentTokenId = optional($request->user()->currentAccessToken())->id;

        return $request->user()->tokens
            ->where('id', '!=', $currentTokenId)
            ->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'api_key' => $token->api_key,
                    'created_at' => $token->created_at,
                    'last_used_at' => $token->last_used_at,
                ];
            })
            ->values(); // reindex if needed
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'abilities' => 'sometimes|array',
        ]);

        // Create the token
        $token = $request->user()->createToken(
            $request->name,
            $request->abilities ?? ['*']
        );

        // Save the plaintext token in the `api_key` column
        // The token object contains 'accessToken' which is the model
        $token->accessToken->api_key = $token->plainTextToken;
        $token->accessToken->save();

        return response()->json([
            'name' => $request->input('name'),
            'token' => $token->plainTextToken
        ]);
    }

    public function destroy(Request $request, $id)
    {

        $request->validate([
            //'id' => 'required',
            'acknowledge' => 'required|accepted',
        ]);

        $token = $request->user()->tokens()->findOrFail($id);
        $token->delete();

        return response()->json(['message' => 'Token deleted.']);
    }




}
