<?php

namespace AmaTeam\Vagranted\Console\Command\Installer;

use AmaTeam\Vagranted\Model\Installation\DescribedInstallerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lists all available installers.
 *
 * @author Etki <etki@etki.me>
 */
class ListCommand extends AbstractInstallerCommand
{
    public function __construct($name = 'installer:list')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->setDescription('Lists all available installers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Existing installers:');
        $api = $this->getApi()->getInstallerAPI();
        foreach ($api->enumerate() as $installer) {
            $name = '(no information available)';
            if ($installer instanceof DescribedInstallerInterface) {
                $description = $installer->getDescription();
                $name = $description->getName();
            }
            $output->writeln(sprintf('  %s: %s', $installer->getId(), $name));
        }
    }
}
