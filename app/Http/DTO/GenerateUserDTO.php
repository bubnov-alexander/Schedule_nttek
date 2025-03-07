<?php

namespace App\Http\DTO;

use Illuminate\Support\Facades\Log;

class GenerateUserDTO
{
    public function __construct(
        protected UserDTO $userDTO,
    )
    {
    }

    public function run($user): UserDTO
    {
        $userDTO = $this->userDTO;
        $userDTO->setTelegramId($user->user()->id);
        $userDTO->setTelegramUsername($user->user()->name);
        $userDTO->setTelegramFirstName($user->user()->first_name);
        $userDTO->setTelegramLastName($user->user()->last_name);

        return $userDTO;
    }

}
