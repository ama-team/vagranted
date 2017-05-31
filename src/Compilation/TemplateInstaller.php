<?php

namespace AmaTeam\Vagranted\Compilation;

use Psr\Log\LoggerAwareTrait;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Compilation\Context;
use AmaTeam\Vagranted\Model\Filesystem\WorkspaceInterface;
use AmaTeam\Vagranted\Model\ResourceSet\ResourceSetInterface;
use AmaTeam\Vagranted\Twig\Factory;
use Exception;
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
     * todo: refactor this mess
     *
     * @param ResourceSetInterface $set
     * @param WorkspaceInterface $workspace
     * @param Context $context
     * @throws Exception
     */
    public function compile(
        ResourceSetInterface $set,
        WorkspaceInterface $workspace,
        Context $context
    ) {
        $twig = $this->twigFactory->create($context);
        foreach ($set->getTemplates() as $template => $targets) {
            foreach ($targets as $target) {
                $loggerContext = [
                    'template' => $template,
                    'target' => $target,
                    'set' => $set->getName()
                ];
                $format = 'Rendering template `{template}` from set `{set}` ' .
                    'to `{target}`';
                $this->logger->debug($format, $loggerContext);
                $source = $set->getWorkspace()->resolve($template);
                $target = $workspace->resolve($target);
                $template = $twig->load($source);
                try {
                    $content = $template->render($context->getContext());
                    $this->filesystem->set($target, $content);
                } catch (Exception $e) {
                    // todo: wrap in a proper self-describing exception
                    $format = 'Exception during template {template} '.
                        'rendering (set: {set})';
                    $this->logger->error($format, $loggerContext);
                    throw $e;
                }
            }
        }
    }
}
