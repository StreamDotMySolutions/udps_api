<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DocumentClassificationController extends Controller
{
 

   public function classify(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Store image temporarily
        $path = $request->file('image')->store('temp', 'public');
        $imagePath = storage_path('app/public/' . $path);

        // Encode image to base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageMime = mime_content_type($imagePath);

        // Prepare OpenAI Vision API payload
        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => 'Please classify this image. Give a short label or description.'
                            ],
                            [
                                'type' => 'image_url',
                                'image_url' => [
                                    'url' => 'data:' . $imageMime . ';base64,' . $imageData,
                                ],
                            ],
                        ],
                    ],
                ],
                'max_tokens' => 100,
            ]);

        // Clean up
        Storage::disk('public')->delete($path);

        \Log::info($response);

        return response()->json([
            'classification' => $response['choices'][0]['message']['content'] ?? 'No result'
        ]);
    }
}
