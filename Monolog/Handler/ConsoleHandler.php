<?php
/**
 * @category  Mage4
 * @package   Mage4_ConsoleLogHandler
 * @author    Rehan Mobin <m.rehan.mobin@gmail.com>
 * @copyright Copyright (c) Mage4. All rights reserved. (https://www.mage4.com)
 */

namespace  Mage4\ConsoleLogHandler\Monolog\Handler;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\FormatterInterface;
use Monolog\Logger;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Monolog\Formatter\LineFormatter;
use Mage4\ConsoleLogHandler\Monolog\Formatter\ConsoleFormatter;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleHandler extends StreamHandler
{
    private $consoleFormatterOptions = [];
    /** @var null|OutputInterface */
    private $output = null;
    private $verbosityLevelMap = [
        OutputInterface::VERBOSITY_QUIET => Logger::ERROR,
        OutputInterface::VERBOSITY_NORMAL => Logger::WARNING,
        OutputInterface::VERBOSITY_VERBOSE => Logger::NOTICE,
        OutputInterface::VERBOSITY_VERY_VERBOSE => Logger::INFO,
        OutputInterface::VERBOSITY_DEBUG => Logger::DEBUG,
    ];

    protected function getDefaultFormatter(): FormatterInterface
    {
        if (!class_exists(CliDumper::class)) {
            return new LineFormatter();
        }
        if (!$this->output) {
            return new ConsoleFormatter($this->consoleFormatterOptions);
        }

        return new ConsoleFormatter(array_replace([
            'colors' => $this->output->isDecorated(),
            'multiline' => OutputInterface::VERBOSITY_DEBUG <= $this->output->getVerbosity(),
        ], $this->consoleFormatterOptions));
    }

    protected function write(array $record): void
    {
        // at this point we've determined for sure that we want to output the record, so use the output's own verbosity
        $this->output->write((string) $record['formatted'], false, $this->output->getVerbosity());
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    private function updateLevel(): bool
    {
        if (null === $this->output) {
            return false;
        }
        $verbosity = $this->output->getVerbosity();
        if (isset($this->verbosityLevelMap[$verbosity])) {
            $this->setLevel($this->verbosityLevelMap[$verbosity]);
        } else {
            $this->setLevel(Logger::DEBUG);
        }

        return true;
    }

    public function handle(array $record): bool
    {
        // we have to update the logging level each time because the verbosity of the
        // console output might have changed in the meantime (it is not immutable)
        return $this->updateLevel() && parent::handle($record);
    }
}
