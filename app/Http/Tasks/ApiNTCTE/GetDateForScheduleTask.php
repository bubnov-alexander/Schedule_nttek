<?php

namespace App\Http\Tasks\ApiNTCTE;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GetDateForScheduleTask
{
    /**
     * @throws ConnectionException
     */
    public function run(): array
    {
        $response = Http::timeout(30) // общий таймаут, сек
        ->connectTimeout(10)     // таймаут на установление соединения
        ->retry(3, 200)          // повторить запрос 3 раза, задержка 200 мс
        ->get('https://erp.nttek.ru/api/schedule/legacy');

        if ($response->successful()) {
            $data = $response->json();
            $message = response()->json($data);
        } else {
            Log::error('API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $message = response()->json(['error' => 'Ошибка подключения'], 500);
        }

        return $message->getData();
    }
}
