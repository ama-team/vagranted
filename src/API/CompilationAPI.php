<?php

namespace AmaTeam\Vagranted\API;

use AmaTeam\Vagranted\Application\Configuration\Container;
use AmaTeam\Vagranted\Compilation\Controller;
use AmaTeam\Vagranted\Model\Filesystem\Workspace;
use AmaTeam\Vagranted\Project\Loader;

/**
 * @author Etki <etki@etki.me>
 */
class CompilationAPI
{
    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var Container
     */
    private $configuration;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @param Controller $controller
     * @param Container $configuration
     * @param Loader $loader
     */
    public function __construct(
        Controller $controller,
        Container $configuration,
        Loader $loader
    ) {
        $this->controller = $controller;
        $this->configuration = $configuration;
        $this->loader = $loader;
    }

    /**
     * Runs compilation
     */
    public function compile()
    {
        $project = $this->loader->load();
        $path = $this->configuration->get()->getTargetDirectory();
        $target = new Workspace($path);
        $this->controller->compile($project, $target);
    }
}
