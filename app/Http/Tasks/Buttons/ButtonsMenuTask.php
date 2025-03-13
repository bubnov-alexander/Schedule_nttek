<?php

namespace App\Http\Tasks\Buttons;

use App\Http\Controllers\Controller;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ButtonsMenuTask extends Controller
{
    public function run()
    {
        $keyboard = InlineKeyboardMarkup::make();

        $keyboard->addRow(
            InlineKeyboardButton::make('📅 Расписание', callback_data: 'schedule_menu'),
            InlineKeyboardButton::make('👨‍🏫 Преподаватели', callback_data: 'teachers_menu'),
        );

        $keyboard->addRow(
            InlineKeyboardButton::make('⚙ Настройки', callback_data: 'settings_menu'),
            InlineKeyboardButton::make('ℹ О боте', callback_data: 'about_menu')
        );

        return $keyboard;
    }

}
