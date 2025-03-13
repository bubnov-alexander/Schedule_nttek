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

    public function __invoke(Nutgram $bot): void
    {
        $bot->message()->delete();

        $message = $this->createUserTask->run($bot->user());
        $bot->sendMessage($message);
    }
}
