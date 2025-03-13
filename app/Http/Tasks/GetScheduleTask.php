<?php

namespace App\Http\Tasks;

use App\Http\Controllers\Controller;
use App\Http\Tasks\ApiNTCTE\GenerateUserScheduleFromApiTask;
use App\Http\Tasks\ApiNTCTE\GetDateForScheduleTask;
use App\Http\Tasks\Buttons\ButtonsScheduleTask;
use App\Models\User;

class GetScheduleTask extends Controller
{
    public function __construct(
        protected readonly GenerateUserScheduleFromApiTask $generateUserScheduleFromApiTask,
        protected readonly GetDateForScheduleTask $dateForScheduleTask,
    )
    {
    }

    public function run(string $typeSchedule, string $groupName = null, string $date): string
    {
        if ($typeSchedule === 'group') {
            $message = $this->generateUserScheduleFromApiTask->run($date, $typeSchedule, $groupName);
        } else {
            $message = '';
        }

        return $message;

    }

}
