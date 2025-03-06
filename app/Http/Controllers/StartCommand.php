<?php

namespace App\Http\Controllers;

use App\Http\Tasks\AddUserTask;
use App\Http\Tasks\CreateUserTask;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;

class StartCommand extends Command
{
    protected string $command = 'start';

    protected ?string $description = 'A lovely start command';

    public function handle(Nutgram $bot): void
    {
        $message = CreateUserTask::class->run($bot); ;
        $bot->sendMessage($message);
    }
}
