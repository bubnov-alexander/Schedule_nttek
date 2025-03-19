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
        $response = Http::timeout(30)
        ->connectTimeout(10)
        ->retry(3, 200)
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
