<?php

namespace App\Http\DTO;

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
        $userDTO->setTelegramId($user->id);
        $userDTO->setTelegramUsername($user->name);
        $userDTO->setTelegramFirstName($user->first_name);
        $userDTO->setTelegramLastName($user->last_name);

        return $userDTO;
    }

}
