<?php

namespace AmaTeam\Vagranted\Console\Command\Configuration;

use AmaTeam\Vagranted\Application\Configuration\Container;
use AmaTeam\Vagranted\Console\AbstractCommand;
use AmaTeam\Vagranted\DI\References;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class InspectCommand extends AbstractCommand
{
    public function __construct($name = 'configuration:inspect')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Dumps configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Serializer $serializer */
        $serializer = $this->getContainer()->get(References::SERIALIZER);
        /** @var Container $configuration */
        $configuration = $this->getContainer()->get(References::CONFIGURATION);
        $output->writeln($serializer->serialize($configuration->get(), 'yaml'));
    }
}
