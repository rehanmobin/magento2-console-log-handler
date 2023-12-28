<?php
/**
 * @category  Mage4
 * @package   Mage4_ConsoleLogHandler
 * @author    Rehan Mobin <m.rehan.mobin@gmail.com>
 * @copyright Copyright (c) Mage4. All rights reserved. (https://www.mage4.com)
 */

namespace Mage4\ConsoleLogHandler\Plugin;

use Mage4\ConsoleLogHandler\Monolog\Handler\ConsoleHandler;
use Magento\Framework\Logger\Monolog;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Monolog\Logger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;


class AppendConsoleLogHandler
{
    const CONFIG_PATH_IS_LOG_HANDLER_ENABLED = 'console_log_handler/settings/is_enabled';

    private ScopeConfigInterface $config;

    public function __construct(
        ScopeConfigInterface $config
    ) {
        $this->config = $config;
    }

    public function beforeSetHandlers(Monolog $subject, array $handlers): array
    {
        if ($this->isLogHandlerEnabled()) {
            $streamHandler = new ConsoleHandler('php://stdout', Logger::DEBUG);
            $streamHandler->setOutput(new ConsoleOutput($this->getVerbosityFromShell($_SERVER['SHELL_VERBOSITY'] ?? 0)));
            $handlers[] = $streamHandler;
        }
        return [$handlers];
    }

    private function getVerbosityFromShell(int $shellVerbosity): int
    {
        switch ($shellVerbosity) {
            case 1:
                return OutputInterface::VERBOSITY_VERBOSE;
                break;
            case 2:
                return OutputInterface::VERBOSITY_VERY_VERBOSE;
                break;
            case 3:
                return OutputInterface::VERBOSITY_DEBUG;
                break;
            default:
                return $shellVerbosity;
        }
    }

    private function isLogHandlerEnabled(): bool
    {
        return $this->config->isSetFlag(self::CONFIG_PATH_IS_LOG_HANDLER_ENABLED);
    }
}
