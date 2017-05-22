<?php

namespace AmaTeam\Vagranted\Console\Command\Sets;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class InstallCommand extends AbstractResourceSetCommand
{
    public function __construct($name = 'sets:install')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addUriArgument()
            ->setDescription('Installs specified resource set');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $input->getArgument('uri');
        $output->writeln('Installing resource set from uri ' . $uri);
        $this->getApi()->getResourceSetAPI()->install($uri);
        $output->writeln('Done.');
    }
}
