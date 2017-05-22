<?php

namespace AmaTeam\Vagranted\Installation;

use AmaTeam\Vagranted\Application\Configuration\Constants;
use AmaTeam\Vagranted\Event\Event\Installation\Deleted;
use AmaTeam\Vagranted\Event\Event\Installation\Evicted;
use AmaTeam\Vagranted\Event\Event\Installation\Installed;
use AmaTeam\Vagranted\Event\Event\Installation\Loaded;
use AmaTeam\Vagranted\Event\EventDispatcherAwareInterface;
use AmaTeam\Vagranted\Event\EventDispatcherAwareTrait;
use AmaTeam\Vagranted\Language\MappingIterator;
use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\Installation\Installation;
use AmaTeam\Vagranted\Model\Installation\NormalizingInstallerInterface;
use AmaTeam\Vagranted\Model\Installation\Specification;
use Iterator;
use Psr\Log\LoggerAwareInterface;

/**
 * @author Etki <etki@etki.me>
 */
class StorageController implements
    LoggerAwareInterface,
    EventDispatcherAwareInterface
{
    use LoggerAwareTrait;
    use EventDispatcherAwareTrait;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var InstallerCollection
     */
    private $installers;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @param Storage $storage
     * @param InstallerCollection $installers
     * @param Loader $loader
     */
    public function __construct(
        Storage $storage,
        InstallerCollection $installers,
        Loader $loader
    ) {
        $this->storage = $storage;
        $this->installers = $installers;
        $this->loader = $loader;
    }

    /**
     * @param string $uri
     * @param string $id
     * @return Installation
     */
    public function install($uri, $id)
    {
        $installer = $this->installers->requireOne($uri);
        $workspace = $this->storage->create($id);
        $target = $workspace
            ->getPath(Constants::INSTALLATION_ARTIFACT_DIRECTORY);
        $specification = $installer->install($uri, $target);
        $specification = $specification ?: new Specification();
        $specification->setUri($uri);
        $installation = (new Installation())
            ->setId($id)
            ->setWorkspace($workspace)
            ->setSpecification($specification);
        $this->loader->bootstrap($installation);
        $installation->getStatistics()->installed();
        $this->getLogger()->notice(
            'Installed resource set from uri {uri}',
            ['uri' => $uri,]
        );
        $this->eventDispatcher->dispatch(
            Installed::NAME,
            new Installed($installation)
        );
        return $installation;
    }

    /**
     * @param string $id
     * @return Installation|null
     */
    public function get($id)
    {
        if (!$this->storage->exists($id)) {
            return null;
        }
        $workspace = $this->storage->get($id);
        $installation = (new Installation())
            ->setId($id)
            ->setWorkspace($workspace);
        $this->loader->load($installation);
        $this->eventDispatcher->dispatch(
            Loaded::NAME,
            new Loaded($installation)
        );
        return $this->loader->load($installation);
    }

    /**
     * Lists existing installations.
     *
     * @return Iterator|Installation[]
     */
    public function enumerate()
    {
        $iterator = $this->storage->enumerate();
        $callback = function (WorkspaceInterface $workspace) {
            // todo this is ugly, isn't it?
            return $this->get(basename($workspace->getPath()));
        };
        return new MappingIterator($iterator, $callback);
    }

    /**
     * Ensures installation isn't present, returns true if real deletion has
     * occurred.
     *
     * @param string $id
     * @return Installation|null
     */
    public function delete($id)
    {
        $this->getLogger()->debug('Deleting installation {id}', ['id' => $id,]);
        $installation = $this->get($id);
        if (!$installation) {
            return null;
        }
        $this->storage->purge($id);
        $this->eventDispatcher->dispatch(
            Deleted::NAME,
            new Deleted($installation)
        );
        return $installation;
    }

    /**
     * Tests if installation exists.
     *
     * @param string $id
     * @return bool
     */
    public function exists($id)
    {
        return $this->storage->exists($id);
    }

    /**
     * Evicts installations that are passed by provided filter.
     *
     * @param callable $filter
     * @return Installation[]
     */
    public function evict(callable $filter)
    {
        $result = [];
        foreach ($this->enumerate() as $installation) {
            if (!$filter($installation)) {
                $this->logger->info(
                    'Installation #{id} doesn\'t fall under eviction filter, ' .
                    'skipping',
                    ['id' => $installation->getId(),]
                );
                continue;
            }
            $this->delete($installation->getId());
            $this->eventDispatcher->dispatch(
                Evicted::NAME,
                new Evicted($installation)
            );
            $this->logger->info(
                'Evicted installation #{id}',
                ['id' => $installation->getId(),]
            );
            $result[] = $installation;
        }
        return $result;
    }

    /**
     * todo: this functionality certainly doesn't belong to storage controller:
     * either rename it or move it out
     *
     * @param $uri
     * @return string
     */
    public function normalize($uri)
    {
        $installer = $this->installers->requireOne($uri);
        if ($installer instanceof NormalizingInstallerInterface) {
            return $installer->normalize($uri);
        }
        return $uri;
    }

    public function supports($uri)
    {
        return (bool) $this->installers->findOne($uri);
    }
}
