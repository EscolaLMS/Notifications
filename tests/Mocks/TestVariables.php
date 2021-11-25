<?php

namespace EscolaLms\Notifications\Tests\Mocks;

use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Core\Models\User;
use EscolaLms\Notifications\Core\NotificationVariableContract;
use EscolaLms\Notifications\Core\Traits\ContentIsValidIfContainsRequiredVariables;
use EscolaLms\Notifications\Core\Traits\TitleIsValidIfContainsRequiredVariables;

class TestVariables extends BasicEnum implements NotificationVariableContract
{
    use TitleIsValidIfContainsRequiredVariables;
    use ContentIsValidIfContainsRequiredVariables;

    const STUDENT_EMAIL      = "@VarStudentEmail";
    const STUDENT_FULL_NAME  = "@VarStudentFullName";
    const FRIEND_EMAIL       = "@VarFriendEmail";
    const FRIEND_FULL_NAME   = "@VarFriendFullName";

    public static function getMockVariables(): array
    {

        $faker = \Faker\Factory::create();
        return [
            self::STUDENT_EMAIL => $faker->email,
            self::STUDENT_FULL_NAME => $faker->name,
            self::FRIEND_EMAIL => $faker->email,
            self::FRIEND_FULL_NAME => $faker->name,
        ];
    }

    public static function getVariablesFromContent(User $user = null, User $otherUser = null): array
    {
        return [
            self::STUDENT_EMAIL => $user->email,
            self::STUDENT_FULL_NAME => $user->name,
            self::FRIEND_EMAIL => $otherUser->email,
            self::FRIEND_FULL_NAME => $otherUser->name,
        ];
    }

    public static function getRequiredVariables(): array
    {
        return [
            self::STUDENT_EMAIL,
        ];
    }

    public static function getRequiredTitleVariables(): array
    {
        return [
            self::STUDENT_EMAIL,
        ];
    }
}
