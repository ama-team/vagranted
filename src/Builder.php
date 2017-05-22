<?php

namespace AmaTeam\Vagranted;

use AmaTeam\Vagranted\DI\Pass\Compilation\AspectCompilerPass;
use AmaTeam\Vagranted\DI\Pass\Configuration\ReconfigurableServiceCollectionPass;
use AmaTeam\Vagranted\DI\Pass\Console\CommandInjectionPass;
use AmaTeam\Vagranted\DI\Pass\Event\DispatcherInjectionPass;
use AmaTeam\Vagranted\DI\Pass\Event\ListenerPass;
use AmaTeam\Vagranted\DI\Pass\Installation\InstallerPass;
use AmaTeam\Vagranted\DI\Pass\Installation\LoaderPass;
use AmaTeam\Vagranted\DI\Pass\Logger\FactoryPass;
use AmaTeam\Vagranted\DI\Pass\Logger\LoggerInjectionPass;
use AmaTeam\Vagranted\DI\Pass\Serialization\SerializerPass;
use AmaTeam\Vagranted\DI\Pass\Twig\ExtensionCollectionPass;
use AmaTeam\Vagranted\DI\Tags;
use AmaTeam\Vagranted\Filesystem\Helper;
use AmaTeam\Vagranted\Model\Configuration;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * @author Etki <etki@etki.me>
 */
class Builder
{
    /**
     * @var ContainerBuilder
     */
    private $container;
    /**
     * @var Configuration
     */
    private $configuration;
    /**
     * @var string[]
     */
    private $serviceDefinitionFiles = [];
    /**
     * @var Definition[]
     */
    private $installers = [];
    /**
     * @var bool
     */
    private $compileContainer = true;
    /**
     * @var Extension[]
     */
    private $extensions = [];

    public function __construct()
    {
        $this->withEmptyContainer();
        $this->withDefaultConfiguration();
    }

    public function withConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    public function withContainer(ContainerBuilder $container)
    {
        $this->container = $container;
        return $this;
    }

    public function withServiceDefinitionFiles(array $paths)
    {
        $this->serviceDefinitionFiles = $paths;
        return $this;
    }

    public function withInstaller($key, Definition $definition)
    {
        $this->installers[$key] = $definition;
    }

    /**
     * @param Extension[] $extensions
     * @return $this
     */
    public function withExtensions(array $extensions)
    {
        $this->extensions = $extensions;
        return $this;
    }

    public function compileContainer()
    {
        $this->compileContainer = true;
        return $this;
    }

    public function doNotCompileContainer()
    {
        $this->compileContainer = false;
        return $this;
    }

    public function withEmptyContainer()
    {
        return $this->withContainer(new ContainerBuilder());
    }

    public function withDefaultConfiguration()
    {
        $configuration = (new Configuration())
            ->setWorkingDirectory(getcwd())
            ->setProjectDirectory(getcwd())
            ->setDataDirectory(Helper::getDefaultDataDirectory());
        return $this->withConfiguration($configuration);
    }

    public function withoutServiceDefinitionFiles()
    {
        return $this->withServiceDefinitionFiles([]);
    }

    public function build()
    {
        $this->loadInstallers();
        $this->loadPrivateDefinitions();
        $this->loadExternalDefinitions();
        $this->registerCompilerPasses();
        $this->registerExtensions();
        $this->container->set('vagranted.configuration', $this->configuration);
        $this->container->set('vagranted.container', $this->container);
        if ($this->compileContainer) {
            $this->container->compile();
        }
        return new API($this->container);
    }

    private function loadInstallers()
    {
        foreach ($this->installers as $key => $definition) {
            if (!$definition->hasTag(Tags::INSTALLER)) {
                $definition->addTag(Tags::INSTALLER);
            }
            // todo hardcode
            $id = 'vagranted.resource_set.installer.' . $key;
            $this->container->setDefinition($id, $definition);
        }
    }

    private function loadPrivateDefinitions()
    {
        $this->loadDefinition(
            Helper::getInstallationRoot(),
            // todo hardcode
            'resources/configuration/container.yml'
        );
    }

    private function loadExternalDefinitions()
    {
        foreach ($this->serviceDefinitionFiles as $path) {
            if (!Helper::isAbsolutePath($path)) {
                $path = $this->configuration->getWorkingDirectory() .
                    DIRECTORY_SEPARATOR .
                    $path;
            }
            $this->loadDefinition(dirname($path), basename($path));
        }
    }

    private function loadDefinition($root, $path)
    {
        $locator = new FileLocator($root);
        $loader = new YamlFileLoader($this->container, $locator);
        $loader->load($path);
    }

    private function registerCompilerPasses()
    {
        $this
            ->container
            ->addCompilerPass(new ReconfigurableServiceCollectionPass())
            ->addCompilerPass(new FactoryPass())
            ->addCompilerPass(new InstallerPass())
            ->addCompilerPass(new LoaderPass())
            ->addCompilerPass(new ListenerPass())
            ->addCompilerPass(new SerializerPass())
            ->addCompilerPass(new DispatcherInjectionPass())
            ->addCompilerPass(new AspectCompilerPass())
            ->addCompilerPass(new ExtensionCollectionPass())
            ->addCompilerPass(
                new LoggerInjectionPass(),
                PassConfig::TYPE_AFTER_REMOVING
            )
            ->addCompilerPass(new CommandInjectionPass());
    }

    private function registerExtensions()
    {
        foreach ($this->extensions as $extension) {
            $this->container->registerExtension($extension);
        }
    }
}
