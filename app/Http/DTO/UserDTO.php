<?php

namespace App\Http\DTO;

use Spatie\LaravelData\Data;

class UserDTO extends Data
{
    public function __construct(
        protected int     $telegram_id,
        protected string  $telegram_username,
        protected string  $telegram_first_name,
        protected ?string $telegram_last_name,
        protected ?string $group,
    )
    {
    }

    public function getTelegramId(): int
    {
        return $this->telegram_id;
    }

    public function setTelegramId(int $telegram_id): void
    {
        $this->telegram_id = $telegram_id;
    }

    public function getTelegramUsername(): string
    {
        return $this->telegram_username;
    }

    public function setTelegramUsername(string $telegram_username): void
    {
        $this->telegram_username = $telegram_username;
    }

    public function getTelegramFirstName(): string
    {
        return $this->telegram_first_name;
    }

    public function setTelegramFirstName(string $telegram_first_name): void
    {
        $this->telegram_first_name = $telegram_first_name;
    }

    public function getTelegramLastName(): ?string
    {
        return $this->telegram_last_name;
    }

    public function setTelegramLastName(?string $telegram_last_name): void
    {
        $this->telegram_last_name = $telegram_last_name;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): void
    {
        $this->group = $group;
    }
}
