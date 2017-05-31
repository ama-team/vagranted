<?php

namespace AmaTeam\Vagranted\Installation\Installer;

use AmaTeam\Pathetic\Path;
use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Installation\DescribedInstallerInterface;
use AmaTeam\Vagranted\Model\Installation\Description;
use AmaTeam\Vagranted\Support\Guzzle\Factory as GuzzleFactory;
use AmaTeam\Vagranted\Support\Zippy\Factory as ZippyFactory;
use GuzzleHttp\RequestOptions;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * @author Etki <etki@etki.me>
 */
class HttpInstaller implements DescribedInstallerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    const PACKAGE_TYPES = ['tar', 'tar.gz', 'tar.bz2', 'zip',];
    const SCHEMES = ['http', 'https',];
    const PARSE_PATTERN = '~^(\w+)\+([\w\.]+)://~';

    /**
     * @var GuzzleFactory
     */
    private $guzzle;

    /**
     * @var ZippyFactory
     */
    private $zippy;

    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @param GuzzleFactory $guzzle
     * @param ZippyFactory $zippy
     * @param AccessorInterface $filesystem
     */
    public function __construct(
        GuzzleFactory $guzzle,
        ZippyFactory $zippy,
        AccessorInterface $filesystem
    ) {
        $this->guzzle = $guzzle;
        $this->zippy = $zippy;
        $this->filesystem = $filesystem;
    }


    public function getId()
    {
        return 'http';
    }

    public function supports($uri)
    {
        return (boolean) $this->analyze($uri);
    }

    public function install($uri, $path)
    {
        $split = $this->analyze($uri);
        $uri = $split[0] . '://' . $split[2];
        $type = $split[1];
        $this->logger->debug(
            'Downloading {type} archive from uri `{uri}`',
            ['uri' => $uri, 'type' => $type,]
        );
        // todo wrap in filesystem abstraction call
        $temporaryFile = Path::parse(tempnam(sys_get_temp_dir(), 'vagranted-'));
        $this->logger->debug(
            'Using temporary file `{path}`',
            ['path' => $temporaryFile]
        );
        $guzzle = $this->guzzle->create();
        $zippy = $this->zippy->create();
        $guzzle->get(
            $uri,
            [RequestOptions::SINK => $temporaryFile->toPlatformString(),]
        );
        $this->logger->debug(
            'Downloaded archive from uri {uri}, extracting',
            ['uri' => $uri,]
        );
        $archive = $zippy->open($temporaryFile->toPlatformString(), $type);
        $archive->extract($path);
        $this->logger->debug('Extracted archive');
        $this->filesystem->delete($temporaryFile);
    }

    /**
     * Splits uri into actual schema, auxiliary schema and schemaless uri
     *
     * @param string $uri
     * @return string[]|null
     */
    private function analyze($uri)
    {
        if (!preg_match(self::PARSE_PATTERN, $uri, $matches)) {
            return null;
        }
        if (!in_array($matches[1], self::SCHEMES)) {
            return null;
        }
        if (!in_array($matches[2], self::PACKAGE_TYPES)) {
            return null;
        }
        return [
            $matches[1],
            $matches[2],
            substr($uri, strlen($matches[0]))
        ];
    }

    public function getDescription()
    {
        return (new Description())
            ->setName('HTTP(S) archive installer')
            ->setDescription('Fetches and decompresses archives over http')
            ->setPatterns([
                'http+tar.gz://host.tld/archive',
                'https+tar://host.tld/archive',
                'https+zip://host.tld/archive',
            ]);
    }
}
