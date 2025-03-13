<?php

namespace App\Http\Tasks;

use App\Http\Controllers\Controller;
use App\Http\DTO\GenerateUserDTO;
use App\Models\User;

class UpdateUserTask extends Controller
{
    public function __construct(
        protected readonly GenerateUserDTO $generateUserDTO
    )
    {
    }

    public function run($nutgramUser, string $group): string
    {
        $userDTO = $this->generateUserDTO->run($nutgramUser, $group);

        $user = User::where('telegram_id', $userDTO->getTelegramId())->first();

        $user->telegram_username = $userDTO->getTelegramUsername();
        $user->telegram_first_name = $userDTO->getTelegramFirstName();
        $user->telegram_last_name = $userDTO->getTelegramLastName();
        $user->group = $userDTO->getGroup();
        $user->save();

        return 'Группа '.  $group . ' успешно установлена';

    }

}
