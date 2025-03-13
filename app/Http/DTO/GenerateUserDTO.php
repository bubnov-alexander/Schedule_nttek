<?php

namespace App\Http\DTO;

use Illuminate\Support\Facades\Log;

class GenerateUserDTO
{
    public function run($user, string $group = null): UserDTO
    {
        return new UserDTO(
            telegram_id: $user->id,
            telegram_username: $user->username,
            telegram_first_name: $user->first_name,
            telegram_last_name: $user->last_name,
            group: $group,
        );
    }

}
