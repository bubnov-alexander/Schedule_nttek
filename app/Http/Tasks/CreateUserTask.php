<?php

namespace App\Http\Tasks;

use App\Http\Controllers\Controller;
use App\Http\DTO\GenerateUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class CreateUserTask extends Controller
{
    public function __construct(
        protected AddUserTask     $addUserTask,
        protected GenerateUserDTO $generateUserDTO
    )
    {
    }

    public function run(Nutgram $bot): string
    {
        $nutgramUser = $bot->user();
        $botName = $bot->getMyName()->getBot();

        $user = User::where('telegram_id', $nutgramUser->id)->first();

        if ($user !== null) {
            $message = "С возвращением в @$botName\n<b>@" . $nutgramUser->username . "</b>\nВыберите действие ниже 👇";
        } else {
            $userDTO = $this->generateUserDTO->run($nutgramUser);
            $this->addUserTask->run($userDTO);
            $message = "Добро пожаловать в @$botName\n<b>@" . $nutgramUser->username . "</b>\nВыберите действие ниже 👇";
        }

        return $message;
    }

}
