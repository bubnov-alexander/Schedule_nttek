<?php

namespace App\Http\Controllers;

use App\Http\Tasks\Buttons\ButtonsMenuTask;
use App\Http\Tasks\Buttons\ButtonsScheduleTask;
use App\Http\Tasks\GetScheduleTask;
use App\Models\User;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class GetScheduleController extends Controller
{
    public function __construct(
        protected readonly GetScheduleTask $getScheduleTask,
        protected readonly ButtonsScheduleTask $buttonsSchedule,
        protected readonly ButtonsMenuTask $buttonsMenuTask
    )
    {
    }

    public function __invoke(Nutgram $bot, string $typeSchedule, $groupName, string $date)
    {
        $bot->message()->delete();

        if ($groupName === null) {
            $message = 'Вы не указали свою группу. Укажите группу командой /setgroup';
            $keyboard = $this->buttonsMenuTask->run();
        }
        else {
            $message = $this->getScheduleTask->run($typeSchedule, $groupName, $date);
            $keyboard = $this->buttonsSchedule->run($groupName);
        }

        return $bot->sendMessage(
            text: $message,
            chat_id: $bot->chatId(),
            parse_mode: ParseMode::MARKDOWN_LEGACY,
            reply_markup: $keyboard
        );

    }
}
