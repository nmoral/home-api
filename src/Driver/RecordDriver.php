<?php

namespace App\Driver;

use App\Normalizer\JsonNormalizer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class RecordDriver extends Driver
{
    protected ?string $storageDir = null;

    private JsonNormalizer $normalizer;

    private EntityJar $jar;

    /**
     * @required
     */
    public function setNormalizer(JsonNormalizer $normalizer): void
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @required
     */
    public function setEntityJar(EntityJar $jar): void
    {
        $this->jar = $jar;
    }

    /**
     * @return array<mixed>
     */
    final public function retrieve(?string $id): array
    {
        $filename = $this->getFilename($id);
        if (!file_exists($filename)) {
            throw new NotFoundHttpException('Not found');
        }

        $json = $this->getContent($filename);

        $entity = $this->denormalize($json);

        return $this->jar->add($entity);
    }

    final public function delete(?string $id): bool
    {
        $filename = $this->getFilename($id);

        return unlink($filename);
    }

    /**
     * @return array<mixed>
     */
    final public function retrieveList(): array
    {
        try {
            $objects = scandir($this->storageDir);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException('unable to fetch objects', 0, $exception);
        }

        $ids = array_diff($objects, ['.', '..']);
        $entities = [];
        foreach ($ids as $id) {
            $entities[] = $this->retrieve($id);
        }

        return $entities;
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<mixed>
     */
    protected function beforeUpdate(string $id, array $object): array
    {
        $this->retrieve($id);
        $object['id'] = $id;
        $object = $this->jar->add($object);

        return parent::beforeUpdate($id, $object);
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<mixed>
     */
    protected function afterCreate(array $object): array
    {
        $object = parent::afterCreate($object);
        $this->record($object);

        return $object;
    }

    /**
     * @param array<mixed> $object
     */
    protected function record(array $object): bool
    {
        $this->check();
        $filename = $this->getFilename($object['id']);

        return false !== file_put_contents($filename, json_encode($object));
    }

    private function checkDir(): void
    {
        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    private function check(): void
    {
        if (null === $this->storageDir) {
            throw new \InvalidArgumentException('You must defined the storage dir');
        }

        $this->checkDir();
    }

    protected function getFilename(string $id): string
    {
        return sprintf('%s/%s', $this->storageDir, $id);
    }

    private function getContent(string $filename): string
    {
        $json = file_get_contents($filename);

        if (false === $json) {
            throw new \InvalidArgumentException('unable to fetch data');
        }

        return $json;
    }

    /**
     * @return array<mixed>
     */
    private function denormalize(string $json): array
    {
        return $this->normalizer->denormalize($json);
    }
}
