<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use SergiX44\Nutgram\Nutgram;
use App\Http\Controllers\StartCommandController;
use \App\Http\Controllers\MenuCommandController;
use \App\Http\Controllers\ScheduleMenuController;
use \App\Http\Controllers\GetScheduleController;
use \App\Http\Controllers\SetGroupController;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

$bot->onCommand('start', StartCommandController::class)
    ->description('Регистрация в боте');

$bot->onCommand('menu', MenuCommandController::class)
    ->description('Главное меню');

$bot->onCommand('setgroup', SetGroupController::class)
    ->description('Установить группу');


$bot->onCallbackQueryData('menu', MenuCommandController::class);
$bot->onCallbackQueryData('schedule_menu', ScheduleMenuController::class);
$bot->onCallbackQueryData('getSchedule {typeSchedule} {groupName} {date}', GetScheduleController::class);

Route::post('/telegram/webhook', function (Nutgram $bot) {
    $bot->run();
});
