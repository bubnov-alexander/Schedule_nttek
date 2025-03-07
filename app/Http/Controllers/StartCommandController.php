<?php

namespace App\Http\Controllers;

use App\Http\Tasks\CreateUserTask;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Nutgram;

class StartCommandController extends Controller
{
    public function __construct(
        protected CreateUserTask $createUserTask
    )
    {
    }

    public function run(Nutgram $bot): void
    {
        $message = $this->createUserTask->run($bot);
        $bot->sendMessage($message);
    }
}
