<?php

namespace App\Http\Controllers;

use App\Http\Tasks\Buttons\ButtonsMenuTask;
use SergiX44\Nutgram\Nutgram;

class MenuCommandController extends Controller
{
    public function __construct(
        protected readonly ButtonsMenuTask $generateMenuTask
    )
    {
    }

    public function __invoke(Nutgram $bot)
    {
        $bot->message()->delete();

        $keyboard = $this->generateMenuTask->run();

        return $bot->sendMessage(
            text: 'Выберите действие ниже 👇',
            chat_id: $bot->chatId(),
            reply_markup: $keyboard
        );
    }
}
