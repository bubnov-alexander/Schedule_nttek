<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class GenerateScheduleInDbFromXMLX extends Command
{
    protected $signature = 'parse:schedule';
    protected $description = 'Парсинг расписания из Excel в базу данных';

    public function handle()
    {
        $path = public_path('excel/2024.xlsx');
        // Получаем данные только с первого листа
        $data = Excel::toArray((object)null, $path)[0];

        // Сколько всего строк/столбцов
        $rowCount = count($data);
        $colCount = $rowCount ? count($data[0]) : 0;

        // Размер блока, где хранится одна группа
        $groupHeight = 9; // 9 строк
        $groupWidth = 3; // 3 столбца

        $groupedSchedules = [];

        // Ключевые слова, которые явно показывают, что это НЕ группа
        $skipGroupWords = [
            'РАСПИСАНИЕ ЗАНЯТИЙ',  // "РАСПИСАНИЕ ЗАНЯТИЙ НА"
            'КОРПУС',              // "КОРПУС 1"
            'УТВЕРЖДАЮ',           // "УТВЕРЖДАЮ"
            'дата',                // Если вдруг есть ячейка "20.03.2025 г." и т.п.
            'г.',
        ];

        // Ключевые слова, по которым понятно, что строка — это шапка или левый текст
        $skipLineWords = [
            'Урок',
            'Дисциплина',
            'Каб.',
            'обед', // Если вы не хотите включать обед в расписание
        ];

        // Двойной цикл по блокам 9×3
        for ($rowBlock = 0; $rowBlock < $rowCount; $rowBlock += $groupHeight) {
            if ($rowBlock + $groupHeight - 1 >= $rowCount) {
                break; // не хватает строк на полный блок
            }

            for ($colBlock = 0; $colBlock < $colCount; $colBlock += $groupWidth) {
                if ($colBlock + $groupWidth - 1 >= $colCount) {
                    break; // не хватает столбцов
                }

                // Название группы – верхняя левая ячейка блока
                $groupName = trim($data[$rowBlock][$colBlock] ?? '');

                // 1) Проверяем, не похоже ли это на «служебное» название
                if (!$groupName) {
                    // Пустая ячейка — не рассматриваем блок
                    continue;
                }
                // Если в названии встречаются слова из $skipGroupWords — пропускаем блок
                if ($this->containsAny($groupName, $skipGroupWords)) {
                    continue;
                }

                // 2) Собираем расписание из следующих строк
                $schedule = [];
                // Предположим, что реальное расписание начинается со 2-й или 3-й строки блока
                // (зависит от того, сколько шапочных строк у вас внутри блока).
                // Допустим, начинаем с 1-й строки блока (r=1).
                for ($r = 1; $r < $groupHeight; $r++) {
                    $lesson = trim($data[$rowBlock + $r][$colBlock] ?? '');
                    $subject = trim($data[$rowBlock + $r][$colBlock + 1] ?? '');
                    $room = trim($data[$rowBlock + $r][$colBlock + 2] ?? '');

                    // Пропускаем полностью пустые строки
                    if (!$lesson && !$subject && !$room) {
                        continue;
                    }

                    // Пропускаем строки, где присутствуют служебные слова (заголовки и прочее)
                    $lineAll = $lesson . ' ' . $subject . ' ' . $room;
                    if ($this->containsAny($lineAll, $skipLineWords)) {
                        continue;
                    }

                    // Если нет дисциплины (subject), то пропускаем эту строку
                    if (!$subject) {
                        continue;
                    }

                    // Если "номер урока" не соответствует шаблону (например, "10-11"), пропускаем
                    if (!preg_match('/^\d{1,2}(-\d{1,2})?$/', $lesson)) {
                        continue;
                    }

                    $schedule[] = [
                        'lesson' => $lesson,
                        'subject' => $subject,
                        'room' => $room,
                    ];
                }

                // Если в итоге расписание оказалось пустым — возможно, это не настоящая группа, пропускаем
                if (empty($schedule)) {
                    continue;
                }

                // Сохраняем результат в массив
                $groupedSchedules[$groupName] = $schedule;
            }
        }

        // Смотрим, что получилось
        $file = fopen(public_path('schedule.txt'), 'w');
        foreach ($groupedSchedules as $group => $lessons) {
            fwrite($file, "Group: $group\n");
            foreach ($lessons as $lesson) {
                fwrite(
                    $file,
                    "Номер: {$lesson['lesson']}, Урок: {$lesson['subject']}, Уабинет: {$lesson['room']}\n"
                );
            }
            fwrite($file, "\n");
        }
        fclose($file);


        $this->info('Готово! Расписание загружено.');

        foreach ($groupedSchedules as $group => $lessons) {
            dump("Group: $group");
        }
    }

    /**
     * Проверяет, содержит ли $text какое-либо из слов $words (регистр игнорируется).
     */
    private
    function containsAny($text, array $words): bool
    {
        foreach ($words as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }
}
