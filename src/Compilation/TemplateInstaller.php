<?php

namespace AmaTeam\Vagranted\Compilation;

use AmaTeam\Vagranted\Logger\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use AmaTeam\Vagranted\Twig\Factory;
use Psr\Log\LoggerAwareInterface;

/**
 * @author Etki <etki@etki.me>
 */
class TemplateInstaller implements AspectCompilerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Factory
     */
    private $twigFactory;
    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @param Factory $twigFactory
     * @param AccessorInterface $filesystem
     */
    public function __construct(
        Factory $twigFactory,
        AccessorInterface $filesystem
    ) {
        $this->twigFactory = $twigFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * Renders all resource set templates and installs them in provided
     * location.
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
        foreach ($set->getTemplates() as $template => $targets) {
            foreach ($targets as $target) {
                $source = $set->getWorkspace()->getPath($template);
                $target = $workspace->getPath($target);
                $twig = $this->twigFactory->create($context);
                $template = $twig->load($source);
                $content = $template->render($context->getContext());
                $this->filesystem->set($target, $content);
            }
        }
    }
}
