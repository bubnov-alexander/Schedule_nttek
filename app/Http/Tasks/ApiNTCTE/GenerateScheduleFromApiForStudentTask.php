<?php

namespace App\Http\Tasks\ApiNTCTE;

use Carbon\Carbon;

class GenerateScheduleFromApiForStudentTask
{
    public function run($allSchedule, $date, $groupName): string
    {
        $text = '';

        $lessonTimes = [
            '1' => '8:30 - 9:50',
            '1-2' => '8:30 - 9:50',
            '3' => '10:00 - 10:40',
            '4' => '10:40 - 11:20',
            '5' => '11:20 - 12:00',
            '6-7' => '12:10 - 13:30',
            '8-9' => '13:40 - 15:00',
            '10-11' => '15:15 - 16:35',
            '12-13' => '16:40 - 18:00',
        ];

        $dateLabel = strtoupper(Carbon::parse($date)
            ->translatedFormat('d.m.Y (l)'));
        $dateLabel = mb_convert_case($dateLabel, MB_CASE_TITLE, "UTF-8");

        if (isset($allSchedule['schedule']) && is_array($allSchedule['schedule'])) {
            foreach ($allSchedule['schedule'] as $schedule) {
                $lessonNumber = $schedule['lesson'] ?? 'Уроков нет';

                if (isset($lessonTimes[$lessonNumber])) {
                    $lessonNumber .= ' ' . $lessonTimes[$lessonNumber];
                }

                $name    = $schedule['name'] ?? '';
                $rooms   = $schedule['rooms'] ?? '';
                $teacher = $schedule['teachers'] ?? '';

                if (is_array($rooms)) {
                    $rooms = implode(', ', $rooms);
                }
                if (is_array($teacher)) {
                    $teacher = implode(', ', $teacher);
                }

                $text .= "\n**Номер урока:** {$lessonNumber}\n" .
                    "**Урок:** {$name} {$rooms}\n" .
                    "**Препод:** {$teacher}\n";
            }
        } else {
            $text = "Расписание отсутствует.";
        }

        return "Расписание на $dateLabel:\n__{$text}__\n" .
            "Расписание $groupName. Выберите день:\n";
    }

}
