<?php

namespace EscolaLms\Notifications\Tests\Mocks;

use EscolaLms\Auth\Models\Group;
use EscolaLms\Auth\Models\User;
use EscolaLms\Core\Enums\BasicEnum;
use EscolaLms\Notifications\Core\NotificationVariableContract;
use EscolaLms\Notifications\Core\Traits\ContentIsValidIfContainsRequiredVariables;
use EscolaLms\Notifications\Core\Traits\TitleIsValidIfContainsRequiredVariables;

class TestVariables extends BasicEnum implements NotificationVariableContract
{
    use TitleIsValidIfContainsRequiredVariables;
    use ContentIsValidIfContainsRequiredVariables;

    const STUDENT_EMAIL      = "@VarStudentEmail";
    const STUDENT_FIRST_NAME = "@VarStudentFirstName";
    const STUDENT_LAST_NAME  = "@VarStudentLastName";
    const STUDENT_FULL_NAME  = "@VarStudentFullName";
    const STUDENT_GROUP      = "@VarStudentGroup";

    public static function getMockVariables(): array
    {

        $faker = \Faker\Factory::create();
        return [
            self::STUDENT_EMAIL => $faker->email,
            self::STUDENT_FIRST_NAME => $faker->firstName,
            self::STUDENT_LAST_NAME => $faker->lastName,
            self::STUDENT_FULL_NAME => $faker->name,
            self::STUDENT_GROUP => $faker->word,
        ];
    }

    public static function getVariablesFromContent(User $user = null, Group $group = null): array
    {
        return [
            self::STUDENT_EMAIL => $user->email,
            self::STUDENT_FIRST_NAME => $user->firstName,
            self::STUDENT_LAST_NAME => $user->lastName,
            self::STUDENT_FULL_NAME => $user->name,
            self::STUDENT_GROUP => $group->name,
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
