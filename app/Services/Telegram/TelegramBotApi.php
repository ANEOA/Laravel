<?php

namespace App\Services\Telegram;


use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';
    public static function sendMessage($token, $chat_id, $message)
    {
        Http::get(self::HOST . $token . '/sendMessage', [
            'chat_id' => $chat_id,
            'text' => $message,
        ]);
    }
}
