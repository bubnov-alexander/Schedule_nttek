<?php

namespace App\Http\Tasks\ApiNTCTE;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateUserScheduleFromApiTask
{
    public function __construct(
        protected readonly GenerateScheduleFromApiForStudentTask $scheduleForStudentTask
    )
    {
    }

    public function run(string $date, string $typeSchedule, string $groupName): string
    {
        $date = Carbon::createFromFormat('d.m.Y', $date)
            ->format('Y-m-d');

        $cacheKey = "{$date}:{$typeSchedule}:{$groupName}";

        if (Cache::has($cacheKey)) {
            Log::info("Расписание для {$groupName} на {$date} загружено из кеша");
            $message = Cache::get($cacheKey);
        } else {
            try {
                $response = Http::timeout(30)
                    ->connectTimeout(10)
                    ->retry(3, 200)
                    ->get("https://erp.nttek.ru/api/schedule/legacy/{$date}/{$typeSchedule}/{$groupName}");
            } catch (\Exception $e) {
                Log::error("Ошибка запроса к API: " . $e->getMessage());
                return 'Ошибка подключения к сайту колледжа';
            }

            if ($response->successful()) {
                if ($typeSchedule === 'group') {
                    $message = $this->scheduleForStudentTask
                        ->run($response->json(), $date, $groupName);
                } else {
                    $message = $this->scheduleForStudentTask
                        ->run($response->json(), $date, $groupName);
                }

                Cache::put($cacheKey, $message);
            } else {
                Log::error("API не выдал расписание для даты {$date} группы {$groupName}", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $message = 'Расписание было не найдено';
            }
        }

        return $message;
    }
}
