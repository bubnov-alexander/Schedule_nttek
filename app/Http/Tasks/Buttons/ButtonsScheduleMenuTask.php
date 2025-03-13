<?php

namespace App\Http\Tasks\Buttons;

use App\Http\Tasks\ApiNTCTE\GetDateForScheduleTask;
use App\Models\User;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ButtonsScheduleMenuTask
{
    public function __construct(
        protected readonly GetDateForScheduleTask $dateForScheduleTask
    )
    {
    }

    public function run(int $userId): InlineKeyboardMarkup
    {
        $group = User::where('telegram_id', $userId)
            ->first()
            ->getGroup();

        $date = $this->dateForScheduleTask->run();

        $keyboard = InlineKeyboardMarkup::make();

        $keyboard->addRow(
            InlineKeyboardButton::make('📅 Моё расписание', callback_data: "getSchedule group $group $date[0]"),
            InlineKeyboardButton::make('👨‍🏫 Преподаватели', callback_data: 'teachers_menu'),
            InlineKeyboardButton::make('🎓 Другая группу', callback_data: 'another_group_schedule_menu'),

        );
        $keyboard->addRow(
            InlineKeyboardButton::make('	🌍 Открыть сайт', url: 'https://a.nttek.ru/'),
            InlineKeyboardButton::make('🛎️ Время звонков', callback_data: 'time_calls_menu'),
            InlineKeyboardButton::make('🔢 Таблица (Excel)', callback_data: 'table_excel_menu')
        );

        $keyboard->addRow(
            InlineKeyboardButton::make('🔙 Назад', callback_data: 'menu')
        );

        return $keyboard;
    }

}
