<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2023-03-21
 * Time: 9:38 AM
 */

namespace Maatify\Functions;

class GeneralFunctions
{

    public static function HostUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
//        return 'https://' . $_SERVER['HTTP_HOST'] . '/';
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
        return round(microtime(true) * 1000);
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
}