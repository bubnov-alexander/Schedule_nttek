<?php

namespace App\Http\Tasks;

use App\Http\Controllers\Controller;
use App\Http\DTO\UserDTO;
use App\Models\User;

class AddUserTask extends Controller
{
    public function run(UserDTO $userDTO): User
    {
        $user = new User();
        $user->telegram_id = $userDTO->getTelegramId();
        $user->telegram_first_name = $userDTO->getTelegramFirstName();
        $user->telegram_last_name = $userDTO->getTelegramLastName();
        $user->telegram_username = $userDTO->getTelegramUsername();
        $user->group = $userDTO->getGroup();

        $user->save();

        return $user;

    }
}
