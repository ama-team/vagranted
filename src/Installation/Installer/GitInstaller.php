<?php

namespace AmaTeam\Vagranted\Installation\Installer;

use AmaTeam\Vagranted\Model\ConfigurationInterface;
use AmaTeam\Vagranted\Model\Installation\DescribedInstallerInterface;
use AmaTeam\Vagranted\Model\Installation\Description;
use AmaTeam\Vagranted\Model\Installation\Specification;
use AmaTeam\Vagranted\Model\Installation\InstallerInterface;
use AmaTeam\Vagranted\Support\Git\Factory;
use PHPGit\Git;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Etki <etki@etki.me>
 */
class GitInstaller implements
    InstallerInterface,
    DescribedInstallerInterface,
    LoggerAwareInterface
{
    use LoggerAwareTrait;

    const PROTOCOLS = ['ssh', 'http', 'https'];
    const SCHEME_PATTERN = '~^git\+(\w+)://~';

    /**
     * @var ConfigurationInterface
     */
    private $configuration;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @param ConfigurationInterface $configuration
     * @param Factory $factory
     */
    public function __construct(
        ConfigurationInterface $configuration,
        Factory $factory
    ) {
        $this->configuration = $configuration;
        $this->factory = $factory;
    }

    public function getId()
    {
        return 'git';
    }

    /**
     * @inheritdoc
     */
    public function supports($uri)
    {
        return (boolean) $this->getTransportProtocol($uri);
    }

    private function stripPrefix($uri)
    {
        if (!preg_match(self::SCHEME_PATTERN, $uri, $matches)) {
            return $uri;
        }
        return substr($uri, strlen($matches[0]));
    }

    private function getTransportProtocol($uri)
    {
        if (!preg_match(self::SCHEME_PATTERN, $uri, $matches)) {
            return null;
        }
        if (!in_array($matches[1], self::PROTOCOLS)) {
            return null;
        }
        return $matches[1];
    }

    private function simplify($uri)
    {
        $address = $this->stripPrefix($uri);
        $protocol = $this->getTransportProtocol($uri);
        return sprintf('%s://%s', $protocol, $address);
    }

    /**
     * @inheritdoc
     */
    public function install($uri, $path)
    {
        $simplified = $this->simplify($uri);
        $parts = explode('#', $simplified);
        $repository = $parts[0];
        $revision = isset($parts[1]) ? $parts[1] : null;
        $git = $this->createGit($path);
        $git->clone($repository, $path);
        $git->checkout($revision);
        // todo ugliest hack ever
        if (DIRECTORY_SEPARATOR === '\\') {
            exec(sprintf('attrib -r -h /s /d "%s\\**"', $path));
        }
        return (new Specification())
            ->setUri($uri)
            ->setRevision($revision ?: $this->computeBranch($git))
            ->setReference($this->computeReference($git))
            ->setSource($this->computeSource($git));
    }

    /**
     * Returns current branch name.
     *
     * @param Git $git
     * @return string|null
     */
    private function computeBranch(Git $git)
    {
        $branches = $git->branch() ?: [];
        return array_reduce($branches, function ($carrier, $branch) {
            return $carrier ?: ($branch['current'] ? $branch['name'] : null);
        }, null);
    }

    /**
     * Returns exact checked out reference
     *
     * @param Git $git
     * @return string|null
     */
    private function computeReference(Git $git)
    {
        $commits = $git->log(null, ['limit' => 1,]);
        if (!$commits) {
            return null;
        }
        $commit = current($commits);
        return $commit['hash'];
    }

    /**
     * @param Git $git
     * @return string|null
     */
    private function computeSource(Git $git)
    {
        $remotes = $git->remote();
        if (!$remotes) {
            return null;
        }
        $remote = current($remotes);
        return $remote['fetch'];
    }

    /**
     * Creates git instance using provided repository location.
     *
     * @param string $directory
     * @return Git
     */
    private function createGit($directory)
    {
        return $this->factory->create($directory, $this->computeGitBinary());
    }

    /**
     * Returns path to git binary.
     *
     * @return string
     */
    private function computeGitBinary()
    {
        $extras = $this->configuration->getExtras() ?: [];
        $key = 'installer.git.binary';
        return isset($extras[$key]) ? $extras[$key] : 'git';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return (new Description())
            ->setName('Git repository fetcher')
            ->setDescription(
                'Installs git repositories as resource sets' .
                PHP_EOL .
                PHP_EOL .
                'Git binary may be configured via `installer.git.binary` option'
            )
            ->setPatterns([
                'git+ssh://github.com/fake-organization/memes.git',
                'git+http://github.com/vovney/bitcoin.git#master',
                'git+https://github.com/event-faking/not-a-virus.git#0.1.0',
            ]);
    }
}
