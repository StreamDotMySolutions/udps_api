<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class OpenAITestController extends Controller
{
    public function test(): JsonResponse
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'user', 'content' => 'Say hello'],
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => $response->choices[0]->message->content ?? 'Key is valid.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid OpenAI key or network issue.',
                'details' => $e->getMessage(),
            ], 400);
        }
    }
}
