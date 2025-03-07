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

    public function run($user): string
    {
        dd($user);

        $user = User::where('telegram_id', $user->id)
            ->first();

        if ($user !== null) {
            $message = 'Вы уже зарегистрированы в боте. Напишите /menu';
        } else {
            $userDTO = $this->generateUserDTO->run($user);
            $this->addUserTask->run($userDTO);

            $message = 'Вы успешно зарегистрировались в боту. Напишите /menu';
        }

        return $message;

    }

}
