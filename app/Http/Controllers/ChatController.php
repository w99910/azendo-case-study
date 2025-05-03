<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ChatController
{
    public static function routes()
    {
        Route::post('/chat', static::class);
    }

    public function __invoke(Request $request, ChatService $chatService)
    {
        $response = $chatService->chat($request->input('message'), $request->input('history'));
        return response()->stream(function () use ($response) {
            foreach ($response as $chunk) {
                echo $chunk;
            }
        }, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
