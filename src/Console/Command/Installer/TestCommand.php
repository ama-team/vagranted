<?php

namespace AmaTeam\Vagranted\Console\Command\Installer;

use AmaTeam\Vagranted\Model\Installation\InstallerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class TestCommand extends AbstractInstallerCommand
{
    public function __construct($name = 'installer:test')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addArgument('uri', InputArgument::REQUIRED)
            ->setDescription(
                'Shows which installer would be used for provided uri'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uri = $input->getArgument('uri');
        $collection = $this->getInstallerCollection();
        $installers = $collection->find($uri);
        if (!$installers) {
            $pattern = '<error>No installers found for uri %s</error>';
            $output->writeln(sprintf($pattern, $uri));
            return 1;
        }
        $installer = current($installers);
        $pattern = 'Found installer: <info>%s</info>';
        $output->writeln(sprintf($pattern, $installer->getId()));
        /** @var InstallerInterface[] $extras */
        $extras = array_slice($installers, 1);
        if (!$extras) {
            return 0;
        }
        $output->writeln('Extra installers that also match the uri:');
        foreach ($extras as $installer) {
            $pattern = '  <info>%s</info>';
            $output->writeln(sprintf($pattern, $installer->getId()));
        }
        return 0;
    }
}
