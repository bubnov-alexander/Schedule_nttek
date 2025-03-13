<?php

namespace App\Http\Controllers;

use App\Http\Tasks\Buttons\ButtonsMenuTask;
use App\Http\Tasks\Buttons\ButtonsScheduleMenuTask;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ScheduleMenuController extends Controller
{
    public function __construct(
        protected readonly ButtonsScheduleMenuTask $generateScheduleMenuTask
    )
    {
    }


    public function __invoke(Nutgram $bot)
    {
        $bot->message()->delete();

        $keyboard = $this->generateScheduleMenuTask->run($bot->userId());

        return $bot->sendMessage(
            text: 'Выберите источник расписания 👇',
            chat_id: $bot->chatId(),
            reply_markup: $keyboard
        );
    }
}
