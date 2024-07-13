<?php

/**
 * @copyright   ©2024 Maatify.dev
 * @Liberary    Functions
 * @Project     Functions
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2024-07-12 3:54 PM
 * @see         https://www.maatify.dev Maatify.com
 * @link        https://github.com/Maatify/Functions  view project on GitHub
 * @copyright   ©2023 Maatify.dev
 *
 * @note        This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 */

namespace Maatify\Functions;

class PasswordGenerator
{
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function passwordGenerator(int $length = 8, string $characters = ''): string
    {
        $password = '';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charactersLength - 1)];
        }
        return $password;
    }

    public function otpGenerator(int $length = 6): string
    {
        return self::passwordGenerator($length, '012345679');
    }

    public static function numeric(): string
    {
        return '2345679';
    }

    public static function upperCase(): string
    {
        return 'ACDEFGHJKLMNPQRSTXYZ';
    }

    public static function lowerCase(): string
    {
        return 'abcdefghklmnprstxyz';
    }

    public static function specialCharacters(): string
    {
        return '#$%&+_-';
    }

    public static function upperAndLowerCase(): string
    {
        return self::upperCase() . self::lowerCase();
    }
    public static function allCharacters(): string
    {
        return self::numeric() . self::upperAndLowerCase() . self::specialCharacters();
    }
}