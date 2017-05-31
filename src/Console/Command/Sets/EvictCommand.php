<?php

namespace AmaTeam\Vagranted\Console\Command\Sets;

use AmaTeam\Vagranted\Event\Event\Installation\Evicted;
use DateInterval;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Etki <etki@etki.me>
 */
class EvictCommand extends AbstractResourceSetCommand
{
    public function __construct($name = 'sets:evict')
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();
        $this
            ->addArgument(
                'ttl',
                InputArgument::OPTIONAL,
                'TTL in DateInterval constructor format',
                'P60D'
            )
            ->setDescription(
                'Deletes all resource sets that haven\'t been used in a while'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Evicting stale resource sets');
        $this->getEventDispatcher()->addListener(
            Evicted::NAME,
            function (Evicted $event) use ($output) {
                $id = $event->getInstallation()->getId();
                $uri = $event->getInstallation()->getSpecification()->getUri();
                $pattern = '  - Installation <info>%s</info> (<info>%s</info>) '.
                    'has been evicted';
                $message = sprintf($pattern, $id, $uri);
                $output->writeln($message);
            }
        );
        $ttl = new DateInterval($input->getArgument('ttl'));
        $evicted = $this
            ->getResourceSetAPI()
            ->evict($ttl);
        $pattern = 'Finished, resource sets evicted: %d';
        $output->writeln(sprintf($pattern, sizeof($evicted)));
    }
}
