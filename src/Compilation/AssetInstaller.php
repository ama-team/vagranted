<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

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
                $this->install($set, $workspace, $asset, $target);
            }
        }
    }

    /**
     * Isolates hard work from loops
     *
     * @param ResourceSetInterface $set
     * @param WorkspaceInterface $workspace
     * @param string $asset
     * @param string $target
     */
    private function install(
        ResourceSetInterface $set,
        WorkspaceInterface $workspace,
        $asset,
        $target
    ) {
        $asset = $set->getWorkspace()->getPath($asset);
        $path = $workspace->getPath($target);
        $context = [
            'asset' => $asset,
            'set' => $set->getName(),
            'target' => $target,
        ];
        if ($asset === $path) {
            $message = 'Skipping asset `{asset}` (set `{set}`) ' .
                'installation: source and target paths are the same';
            $this->logger->debug($message, $context);
            return;
        }
        $this->logger->debug(
            'Copying asset `{asset}` from set `{set}` to `{target}`',
            $context
        );
        $this->filesystem->copy($asset, $path);
    }
}
