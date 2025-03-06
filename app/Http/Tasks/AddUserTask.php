<?php

namespace App\Http\Tasks;

use App\Http\Controllers\Controller;
use App\Http\DTO\UserDTO;
use App\Models\User;

class AddUserTask extends Controller
{
    public function run(UserDTO $userDTO): User
    {
        return User::create([
            'telegram_id' => $userDTO->getTelegramId(),
            'telegram_username' => $userDTO->getTelegramUsername(),
            'telegram_first_name' => $userDTO->getTelegramFirstName(),
            'telegram_last_name' => $userDTO->getTelegramLastName(),
            'group' => $userDTO->getGroup(),
        ]);
    }
}
