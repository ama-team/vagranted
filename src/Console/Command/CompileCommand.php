<?php

namespace AmaTeam\Vagranted\Console\Command;

use AmaTeam\Vagranted\Console\AbstractCommand;
use AmaTeam\Vagranted\Event\Event\Compilation\ResourceSetProcessed;
use AmaTeam\Vagranted\Event\Event\Compilation\Started;
use AmaTeam\Vagranted\Event\Event\Installation\Installed;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class CompileCommand extends AbstractCommand
{
    public function __construct($name = 'compile')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Compiles project');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $api = $this->getApi();
        $output->writeln(sprintf('Compiling project'));
        $dispatcher = $this->getEventDispatcher();
        $dispatcher->addListener(
            Started::NAME,
            function (Started $event) use ($output) {
                $output->writeln('Using resource sets:');
                foreach ($event->getContext()->getResourceSets() as $set) {
                    $output->writeln('  - ' . $set->getName());
                }
            }
        );
        $dispatcher->addListener(
            ResourceSetProcessed::NAME,
            function (ResourceSetProcessed $event) use ($output) {
                $set = $event->getResourceSet();
                $output->writeln('Processed resource set ' . $set->getName());
            }
        );
        $dispatcher->addListener(
            Installed::NAME,
            function (Installed $event) use ($output) {
                $installation = $event->getInstallation();
                $name = $installation->getSet()->getName();
                $uri = $installation->getSpecification()->getUri();
                $pattern = 'Installed resource set `%s` from `%s`';
                $output->writeln(sprintf($pattern, $name, $uri));
            }
        );
        $api->getCompilationAPI()->compile();
        $output->writeln('Compilation has successfully finished');
    }
}
