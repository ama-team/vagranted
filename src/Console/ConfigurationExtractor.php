<?php

namespace AmaTeam\Vagranted\Console;

use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Model\Configuration;
use AmaTeam\Vagranted\Model\Configuration\LoggerConfiguration;
use AmaTeam\Vagranted\Model\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class ConfigurationExtractor
{
    /**
     * @param InputInterface $input
     * @return Configuration
     */
    public function extract(InputInterface $input)
    {
        $workingDirectory = $input->getOption(Options::WORKING_DIRECTORY);
        $workingDirectory = $workingDirectory ?: getcwd();
        $projectDirectory = $input->getOption(Options::PROJECT_DIRECTORY);
        $projectDirectory = $projectDirectory ?: $workingDirectory;
        $targetDirectory = $input->getOption(Options::TARGET_DIRECTORY);
        $targetDirectory = $targetDirectory ?: $projectDirectory;
        $dataDirectory = $input->getOption(Options::DATA_DIRECTORY);
        $dataDirectory = $dataDirectory ?: Helper::getDefaultDataDirectory();
        $extras = $this->extractExtras($input);
        return (new Configuration())
            ->setWorkingDirectory($workingDirectory)
            ->setProjectDirectory($projectDirectory)
            ->setTargetDirectory($targetDirectory)
            ->setDataDirectory($dataDirectory)
            ->setLogger($this->extractLoggerConfiguration($input))
            ->setExtras($extras);
    }

    private function extractLoggerConfiguration(InputInterface $input)
    {
        return (new LoggerConfiguration())
            ->setLevel($input->getOption(Options::LOGGER_LEVEL))
            ->setTarget($input->getOption(Options::LOGGER_TARGET))
            ->setFormat($input->getOption(Options::LOGGER_FORMAT))
            ->setPrefix($input->getOption(Options::LOGGER_PREFIX));
    }

    private function extractExtras(InputInterface $input)
    {
        $options = $input->getOption(Options::CUSTOM_OPTION);
        $callback = function ($carrier, $value) {
            $position = strpos($value, '=');
            if (!$position) {
                // todo custom exception
                $pattern = 'Invalid option definition: `%s`, ' .
                    'expected `%key%=%value%` instead';
                throw new RuntimeException(sprintf($pattern, $value));
            }
            $key = substr($value, 0, $position);
            $value = substr($value, $position + 1);
            $carrier[$key] = $value;
            return $carrier;
        };
        return array_reduce($options, $callback, []);
    }
}
