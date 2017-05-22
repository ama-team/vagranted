<?php

namespace AmaTeam\Vagranted\Installation\Aspect\Statistics;

use AmaTeam\Vagranted\Model\Filesystem\AccessorInterface;
use AmaTeam\Vagranted\Model\Installation\StatisticsInterface;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * @author Etki <etki@etki.me>
 */
class Handler implements StatisticsInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var AccessorInterface
     */
    private $filesystem;

    /**
     * @var DataContainer
     */
    private $data;

    /**
     * @param string $path
     * @param Serializer $serializer
     * @param AccessorInterface $filesystem
     */
    public function __construct(
        $path,
        Serializer $serializer,
        AccessorInterface $filesystem
    ) {
        $this->path = $path;
        $this->serializer = $serializer;
        $this->filesystem = $filesystem;
    }

    public function used(DateTimeInterface $usedAt = null)
    {
        $this->load();
        $this->data->setUsedAt($usedAt ?: new DateTimeImmutable());
        $this->data->setUsages($this->data->getUsages() + 1);
        return $this->save();
    }

    public function installed(DateTimeInterface $installedAt = null)
    {
        $this->load();
        $this->data->setInstalledAt($installedAt ?: new DateTimeImmutable());
        return $this->save();
    }

    public function getUsageCounter()
    {
        $this->load();
        return $this->data->getUsages();
    }

    public function getInstalledAt()
    {
        $this->load();
        return $this->data->getInstalledAt();
    }

    public function getUsedAt()
    {
        $this->load();
        return $this->data->getUsedAt();
    }

    public function getVersion()
    {
        return 1;
    }

    private function load()
    {
        if (!$this->data) {
            $this->data = $this->get();
        }
    }

    private function save()
    {
        return $this->set($this->data);
    }

    private function get()
    {
        if (!$this->filesystem->exists($this->path)) {
            return new DataContainer();
        }
        $contents = $this->filesystem->get($this->path);
        $buffer = $this->serializer->decode($contents, 'yaml');
        $buffer['usedAt'] = $this->decodeDatetime($buffer['usedAt']);
        $buffer['installedAt'] = $this->decodeDatetime($buffer['installedAt']);
        return $this
            ->serializer
            ->denormalize($buffer, DataContainer::class);
    }

    private function set(DataContainer $data)
    {
        $buffer = $this->serializer->normalize($data);
        $buffer['usedAt'] = $this->encodeDatetime($data->getUsedAt());
        $buffer['installedAt']
            = $this->encodeDatetime($data->getInstalledAt());
        $contents = $this->serializer->encode($buffer, 'yaml');
        $this->filesystem->set($this->path, $contents);
        return $this;
    }

    private function encodeDatetime(DateTimeInterface $input = null)
    {
        if (!$input) {
            return null;
        }
        return $input->format(DateTime::ISO8601);
    }

    private function decodeDatetime($input)
    {
        if (!$input) {
            return null;
        }
        return DateTimeImmutable::createFromFormat(DateTime::ISO8601, $input);
    }
}
