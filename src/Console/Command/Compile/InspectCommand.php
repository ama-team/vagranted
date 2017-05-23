<?php

namespace AmaTeam\Vagranted\Console\Command\Compile;

use AmaTeam\Vagranted\Console\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class InspectCommand extends AbstractCommand
{
    public function __construct($name = 'compile:inspect')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setHelp('Prepares and prints compilation context');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $context = $this->getApi()->getCompilationAPI()->createContext();
        $text = $this->getSerializer()->serialize($context, 'yaml');
        $output->writeln($text);
    }
}
