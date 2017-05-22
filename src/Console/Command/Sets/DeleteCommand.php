<?php

namespace AmaTeam\Vagranted\Console\Command\Sets;

use AmaTeam\Vagranted\Model\Exception\InvalidInputException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Removes specified resource set.
 *
 * @author Etki <etki@etki.me>
 */
class DeleteCommand extends AbstractResourceSetCommand
{
    public function __construct($name = 'sets:delete')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addUriArgument()
            ->setDescription('Deletes specified resource set');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $input->getArgument('uri');
        $installation = $this
            ->getApi()
            ->getResourceSetAPI()
            ->delete($uri);
        if (!$installation) {
            $pattern = 'Resource set %s hasn\'t been installed';
            throw new InvalidInputException(sprintf($pattern, $uri));
        }
        $set = $installation->getSet();
        $installation->setSet(null);
        $output->writeln($this->getSerializer()->serialize($set, 'yaml'));
    }
}
