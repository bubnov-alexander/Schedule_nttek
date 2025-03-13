<?php

namespace App\Http\Tasks;

use App\Http\Controllers\Controller;
use App\Http\DTO\GenerateUserDTO;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CreateUserTask extends Controller
{
    public function __construct(
        protected AddUserTask $addUserTask,
        protected GenerateUserDTO $generateUserDTO
    )
    {
    }

    public function run($nutgramUser): string
    {
        $user = User::where('telegram_id', $nutgramUser->id)
            ->first();

        if ($user !== null) {
            $message = 'С возвращением в @nttek_2is6_bot @' . $nutgramUser->username . '. Напишите /menu';
        } else {
            $userDTO = $this->generateUserDTO->run($nutgramUser);
            $this->addUserTask->run($userDTO);

            $message = 'Добро пожаловать в @nttek_2is6_bot @' . $nutgramUser->username . '. Напишите /menu';
        }

        return $message;

    }

}
