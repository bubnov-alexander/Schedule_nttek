<?php

namespace App\Http\Tasks\Buttons;

use App\Http\Tasks\ApiNTCTE\GetDateForScheduleTask;
use App\Models\User;
use Carbon\Carbon;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ButtonsScheduleTask
{
    public function __construct(
        protected readonly GetDateForScheduleTask $dateForScheduleTask
    )
    {
    }

    public function run(string $groupName = null): InlineKeyboardMarkup
    {
        $keyboard = InlineKeyboardMarkup::make();

        $allDates = $this->dateForScheduleTask->run();
        $lastFiveDates = array_slice($allDates, 0, 5);
        $lastFiveDates = array_reverse($lastFiveDates);

        if ($groupName !== null) {
            foreach ($lastFiveDates as $date) {
                $dateLabel = strtoupper(Carbon::parse($date)
                    ->translatedFormat('d.m.Y (l)'));
                $dateLabel = mb_convert_case($dateLabel, MB_CASE_TITLE, "UTF-8");
                $keyboard->addRow(InlineKeyboardButton::make(text: $dateLabel, callback_data: "getSchedule group $groupName $date"));
            }

            $keyboard->addRow(
                InlineKeyboardButton::make('👥 Другие группы', callback_data: 'another_group_schedule'),
                InlineKeyboardButton::make('👨‍🏫 Преподаватель', callback_data: 'teachers_schedule'),
                InlineKeyboardButton::make('👤 Моя группа', callback_data: "getSchedule group $groupName $allDates[0]"),
            );
            $keyboard->addRow(InlineKeyboardButton::make('🔙 Назад', callback_data: 'schedule_menu'));

        } else {
            $keyboard = null;
        }

        return $keyboard;
    }

}
