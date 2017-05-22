<?php

namespace AmaTeam\Vagranted\Console\Command\Installer;

use AmaTeam\Vagranted\Language\Strings;
use AmaTeam\Vagranted\Model\Exception\InvalidInputException;
use AmaTeam\Vagranted\Model\Installation\DescribedInstallerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class InspectCommand extends AbstractInstallerCommand
{
    public function __construct($name = 'installer:inspect')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Installer to inspect'
            )
            ->setHelp('Provides basic information about installer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $installer = $this->getApi()->getInstallerAPI()->get($id);
        if (!$installer) {
            $pattern = 'Installer `%s` doesn\'t exist';
            throw new InvalidInputException(sprintf($pattern, $id));
        }
        $output->writeln(sprintf('%s:', $id));
        if (!($installer instanceof DescribedInstallerInterface)) {
            $output->writeln('  - No additional information provided');
            return 0;
        }
        $description = $installer->getDescription();
        $output->writeln(sprintf('  name: %s', $description->getName()));
        $output->writeln('  description: >');
        $output->writeln(Strings::indent($description->getDescription(), 4));
        $output->writeln('  patterns:');
        foreach ($description->getPatterns() as $pattern) {
            $output->writeln(sprintf('    - %s', $pattern));
        }
        return 0;
    }
}
