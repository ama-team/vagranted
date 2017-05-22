<?php

namespace AmaTeam\Vagranted\Console\Command\Sets;

use AmaTeam\Vagranted\DI\References;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class InspectCommand extends AbstractResourceSetCommand
{
    public function __construct($name = 'sets:inspect')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addUriArgument()
            ->setDescription('Shows resource set metadata');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installation = $this
            ->getResourceSetAPI()
            ->get($input->getArgument('uri'));
        if (!$installation) {
            return 1;
        }
        /** @var Serializer $serializer */
        $serializer = $this
            ->getContainer()
            ->get(References::SERIALIZER);
        $set = $installation->getSet();
        $installation->setSet(null);
        $output->writeln($serializer->serialize($set, 'yaml'));
        return 0;
    }
}
