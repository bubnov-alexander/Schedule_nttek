<?php

namespace App\Http\Controllers;

use App\Http\Tasks\Buttons\ButtonsMenuTask;
use App\Http\Tasks\CreateUserTask;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class StartCommandController extends Controller
{
    public function __construct(
        protected CreateUserTask  $createUserTask,
        protected ButtonsMenuTask $buttonsMenuTask
    )
    {
    }

    public function __invoke(Nutgram $bot): void
    {
        $bot->message()->delete();

        $message = $this->createUserTask->run($bot);
        $keyboard = $this->buttonsMenuTask->run();

        $bot->sendMessage(
            text: $message,
            parse_mode: ParseMode::HTML,
            reply_markup: $keyboard
        );
    }
}
