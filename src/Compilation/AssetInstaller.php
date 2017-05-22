<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use Psr\Log\LoggerAwareInterface;

/**
 * This class copies locally-installed resource sets files into current
 * directory.
 *
 * @author Etki <etki@etki.me>
 */
class AssetInstaller implements AspectCompilerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @param AccessorInterface $filesystem
     */
    public function __construct(AccessorInterface $filesystem) {
        $this->filesystem = $filesystem;
    }

    /**
     * Installs all assets in the target location.
     *
     * @param ResourceSetInterface $set
     * @param WorkspaceInterface $workspace
     * @param Context $context
     */
    public function compile(
        ResourceSetInterface $set,
        WorkspaceInterface $workspace,
        Context $context
    ) {
        foreach ($set->getAssets() as $asset => $targets) {
            foreach ($targets as $target) {
                $source = $set->getWorkspace()->getPath($asset);
                $target = $workspace->getPath($target);
                if ($source === $target) {
                    $message = 'Skipping asset {asset} (set `{set}`) ' .
                        'installation: source and target paths are the same';
                    $this->getLogger()->debug(
                        $message,
                        ['asset' => $asset, 'set' => $set->getName(),]
                    );
                    continue;
                }
                $this->getLogger()->debug(
                    'Copying asset {asset} from set `{set}` to {target}',
                    [
                        'asset' => $asset,
                        'set' => $set->getName(),
                        'target' => $target,
                    ]
                );
                $this->filesystem->copy($source, $target);
            }
        }
    }
}
