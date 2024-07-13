<?php

/**
 * @copyright   ©2024 Maatify.dev
 * @Liberary    Functions
 * @Project     Functions
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2023-03-21 9:38 AM
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

class GeneralFunctions
{

    public static function HostUrl(): string
    {
        //        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return 'https://' . $_SERVER['HTTP_HOST'] . '/';
    }

    public static function HostName(): array|string
    {
        $name = str_replace("www.", "", strtolower($_SERVER['HTTP_HOST']));
        //        $name =  str_replace(['.com', '.net', '.org', '.dev', '.online', '.info'], "", $name);
        $name = substr($name, 0, strrpos($name, '.'));

        return strtoupper($name);
    }

    public static function GoogleCaptchaV3SiteKey(): string
    {
        if (! empty($_ENV['GRCAPV3STATUS'])) {
            return $_ENV['GRCAPV3SITE'];
        } else {
            return '';
        }
    }

    public static function GoogleCaptchaV2SiteKey(): string
    {
        if (! empty($_ENV['GRCAPV2STATUS'])) {
            return $_ENV['GRCAPV2SITE'];
        } else {
            return '';
        }
    }

    public static function CdnKeyWebsite()
    {
        return $_ENV['CDN_KEY_SITE'];
    }

    public static function CdnKeyPortal()
    {
        return $_ENV['CDN_KEY_PORTAL'];
    }

    public static function CdnKeyDashboard()
    {
        return $_ENV['CDN_KEY_DASHBOARD'];
    }

    public static function ClearSpaces(string $string): string
    {
        return preg_replace(
            '!\s+!',
            ' ',
            preg_replace('~[\r\n]+~', '', $string)
        );
    }

    public static function ClearString($string): string
    {
        return htmlspecialchars(stripslashes(trim($string)), ENT_QUOTES, 'UTF-8');
    }

    public static function ClearComment($string): string
    {
        $string = str_replace(array('\'', "&quot;", "&#039;"), "’", $string);

        return self::ClearString($string);
    }

    public static function PageName(): string
    {
        return $_SERVER["REQUEST_URI"];
    }

    public static function CurrentUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public static function CurrentPage(): string
    {
        return basename($_SERVER["PHP_SELF"], '.php') ?? "index";
    }

    public static function CurrentPageError(int|string $line = ''): string
    {
        return self::CurrentPage() . ':' . $line;
    }

    public static function CurrentAction(): string
    {
        return $_GET['action'] ?? "index";
    }

    public static function CurrentMicroTimeStamp(): int
    {
        //        return round(microtime(true) * 1000);
        return intval(microtime(true) * 1000);
    }

    public static function ForwardIp(): string
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    }

    public static function UserIp(): string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    public static function UserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    private static function crypto_rand_secure(int $range): int
    {
        if ($range < 1) {
            return 0;
        } // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int)($log / 8) + 1;    // length in bytes
        $bits = (int)$log + 1;           // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $rnd;
    }

    public static function GenerateOTP(int $length = 6): string
    {
        $token = '';
        $codeAlphabet = '0123456789';
        $max = strlen($codeAlphabet); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure($max - 1)];
        }

        return $token;
    }

    public static function DefaultOneLetter(int $length = 1): string
    {
        $token = '';
        $codeAlphabet = 'QWERTYPASDFGMNBXZ';
        $max = strlen($codeAlphabet); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[self::crypto_rand_secure($max - 1)];
        }

        return $token;
    }

    public static function NumberToNumberText(int $day): string
    {
        return match ($day) {
            1 => '1st',
            2 => '2nd',
            3 => '3rd',
            default => $day . 'th',
        };
    }

    public static function CleanPrettyUrl($string): string
    {
        $string = str_replace(' ', '-', strtolower($string));
        $string = str_replace('_', '-', strtolower($string));

        return (string)preg_replace('/[^a-z0-9\-]/', '', $string); // Removes special chars.
    }

    public static function EchoReplacementWysIsWyg(string $str): string
    {
        return (string)str_replace(
            array(
                '"\'',
                '\'"',
                "`",
                "'’",
                '’',
                "’'",
                '"’',
                '"’'),
            "'",
            htmlspecialchars_decode(htmlspecialchars_decode($str/*nl2br($str)*/), ENT_QUOTES));
    }

    public static function ValidateAjaxPage(string $page_without_extension): bool
    {
        if (! empty($_SERVER['HTTP_REFERER'])) {
            $url = strtok($_SERVER['HTTP_REFERER'], '?');
            if (str_contains($url, GeneralFunctions::HostUrl())
                && basename(strtok($_SERVER['HTTP_REFERER'], '?'), '.php') ?? 'index' == $page_without_extension
            ) {
                return true;
            }
        }

        return false;
    }

    public static function ValidateAjaxIsSameHost(): bool
    {
        if (! empty($_SERVER['HTTP_REFERER'])
            && $_SERVER['HTTP_HOST'] == parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST)
        ) {
            return true;
        }

        return false;
    }

    public static function Bool2String($var): string
    {
        return filter_var($var, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    }

    public static function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);

        // Mask the local part, keep the first and last characters visible
        $maskedLocal = substr($local, 0, 1) . str_repeat('*', max(0, strlen($local) - 2)) . substr($local, -1);

        // Split the domain into name and extension (e.g., gmail.com -> gmail and com)
        [$domainName, $extension] = explode('.', $domain);

        // Mask the domain name, keep the first character visible
        $maskedDomainName = substr($domainName, 0, 1) . str_repeat('*', max(0, strlen($domainName) - 1));

        // Combine the masked parts
        return $maskedLocal . '@' . $maskedDomainName . '.' . $extension;
    }
}