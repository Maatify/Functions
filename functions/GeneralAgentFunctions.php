<?php

/**
 * @copyright   ©2024 Maatify.dev
 * @Liberary    Functions
 * @Project     Functions
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2023-07-24 4:15 PM
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

use donatj\UserAgent\UserAgent;
use donatj\UserAgent\UserAgentParser;
class GeneralAgentFunctions
{
    private static self $instance;
    private string $platform;
    private string $browser;
    private string $browserVersion;


    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private UserAgent $userAgentParser;

    public function __construct()
    {
//        $parser = new UserAgentParser();
//        $this->userAgentParser = $parser();
//        $this->platform = $this->userAgentParser->platform() ?? '';
//        $this->browser = $this->userAgentParser->browser() ?? '';
//        $this->browserVersion = $this->userAgentParser->browserVersion() ?? '';
        $this->reloadByString();
    }

    public function reloadByString(string|null $user_agent = null): void
    {
        try {
            $this->userAgentParser = (new UserAgentParser())->parse($user_agent);
            $this->platform = $this->userAgentParser->platform() ?? '';
            $this->browser = $this->userAgentParser->browser() ?? '';
            $this->browserVersion = $this->userAgentParser->browserVersion() ?? '';
        }catch (\Exception $exception){
            $this->platform = '';
            $this->browser = '';
            $this->browserVersion = '';
        }

    }

    public function platform(): string
    {
        return $this->platform;
    }

    public function browser(): string
    {
        return $this->browser;
    }

    public function browserVersion(): string
    {
        return $this->browserVersion;
    }

    public function userAgent(): UserAgent
    {
        return $this->userAgentParser;
    }
}