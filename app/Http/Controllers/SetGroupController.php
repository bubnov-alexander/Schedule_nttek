<?php

namespace App\Http\Controllers;

use App\Http\Tasks\Buttons\ButtonsScheduleMenuTask;
use App\Http\Tasks\UpdateUserTask;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use function Laravel\Prompts\text;

class SetGroupController extends Conversation
{
    public function __construct(
        protected readonly UpdateUserTask $updateUserTask,
        protected readonly ButtonsScheduleMenuTask $generateScheduleMenuTask
    )
    {
    }

    public function start(Nutgram $bot)
    {
        $bot->message()->delete();
        $bot->sendMessage('Напиши свою группу');
        $this->next('secondStep');
    }

    public function secondStep(Nutgram $bot)
    {
        $bot->message()->delete();
        $group = $bot->message()->text;
        $message = $this->updateUserTask->run($bot->user(), $group);

        $keyboard = $this->generateScheduleMenuTask->run($bot->userId());

        $bot->sendMessage(
            text: $message,
            reply_markup: $keyboard
        );
        $this->end();
    }
}
