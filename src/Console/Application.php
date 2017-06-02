<?php

namespace AmaTeam\Vagranted\Console;

use AmaTeam\Vagranted\Application\Configuration\Defaults;
use AmaTeam\Vagranted\Console\Command as Commands;
use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\DI\References;
use AmaTeam\Vagranted\Application\VersionProvider;
use AmaTeam\Vagranted\Model\ConfigurationWrapper;
use Symfony\Component\Console\Application as ApplicationBase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Etki <etki@etki.me>
 */
class Application extends ApplicationBase implements
    EventDispatcherAwareInterface
{
    const NAME = 'Vagranted';

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(
        ContainerInterface $container,
        VersionProvider $versionProvider
    ) {
        $this->container = $container;
        parent::__construct(self::NAME, $versionProvider->getVersion());
    }

    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();
        $definition->addOptions([
            new InputOption(
                Options::DATA_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED,
                'Directory in which Vagranted stores installed files'
            ),
            new InputOption(
                Options::PROJECT_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED,
                'Directory vagranted will run compilation for, defaults to working directory'
            ),
            new InputOption(
                Options::WORKING_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED,
                'Current working directory'
            ),
            new InputOption(
                Options::TARGET_DIRECTORY,
                null,
                InputOption::VALUE_REQUIRED,
                'Directory to compile project in (defaults to project directory)'
            ),
            new InputOption(
                Options::LOGGER_LEVEL,
                null,
                InputOption::VALUE_REQUIRED,
                'One of the Psr\\Log\\LogLevel levels',
                Defaults::LOGGER_LEVEL
            ),
            new InputOption(
                Options::LOGGER_TARGET,
                null,
                InputOption::VALUE_REQUIRED,
                'Log target location',
                Defaults::LOGGER_TARGET
            ),
            new InputOption(
                Options::LOGGER_FORMAT,
                null,
                InputOption::VALUE_REQUIRED,
                'Logger format',
                Defaults::LOGGER_FORMAT
            ),
            new InputOption(
                Options::LOGGER_PREFIX,
                null,
                InputOption::VALUE_REQUIRED,
                'Logger prefix',
                Defaults::LOGGER_PREFIX
            ),
            new InputOption(
                Options::CUSTOM_OPTION,
                null,
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Extra option',
                []
            )
        ]);
        return $definition;
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $callback = function (ConsoleCommandEvent $event) {
            $extractor = new ConfigurationExtractor();
            $configuration = $extractor->extract($event->getInput());
            /** @var ConfigurationWrapper $provider */
            $provider = $this->container->get(References::CONFIGURATION);
            $provider->setEnclosure($configuration);
        };
        $this->dispatcher->addListener(ConsoleEvents::COMMAND, $callback);
        return parent::doRun($input, $output);
    }

    public function withDefaultCommands()
    {
        $this->addCommands([
            new Commands\CompileCommand(),
            new Commands\Installer\ListCommand(),
            new Commands\Installer\TestCommand(),
            new Commands\Sets\ListCommand(),
            new Commands\Sets\InspectCommand(),
            new Commands\Sets\InstallCommand(),
            new Commands\Sets\DeleteCommand(),
            new Commands\Sets\EvictCommand(),
            new Commands\Configuration\InspectCommand(),
        ]);
        return $this;
    }

    public function add(Command $command)
    {
        if ($command instanceof ContainerAwareInterface) {
            $command->setContainer($this->container);
        }
        return parent::add($command);
    }

    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->setDispatcher($dispatcher);
    }
}
