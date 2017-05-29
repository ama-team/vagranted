<?php

namespace AmaTeam\Vagranted\Console\Command\Sets;

use AmaTeam\Vagranted\Model\Installation\Installation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class ListCommand extends AbstractResourceSetCommand
{
    public function __construct($name = 'sets:list')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Lists all installed resource sets');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installations = $this->getApi()->getResourceSetAPI()->enumerate();
        if (!$installations->valid()) {
            $output->writeln('<error>Whoops, no sets installed</error>');
            return 0;
        }
        $output->writeln('Installed sets:');
        /** @var Installation $installation */
        foreach ($installations as $installation) {
            $message = sprintf(
                '  %s: %s',
                $installation->getId(),
                $installation->getSpecification()->getUri()
            );
            $output->writeln($message);
        }
        return 0;
    }
}
