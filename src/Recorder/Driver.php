<?php

namespace App\Recorder;

abstract class Driver
{
    protected ?string $storageDir = null;

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    final public function save(array $object): array
    {
        $object = $this->beforeSave($object);

        $object['id'] = uniqid();
        $object['createdAt'] = new \DateTime();

        return $this->afterSave($object);
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    protected function beforeSave(array $object): array
    {
        return $object;
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    protected function afterSave(array $object): array
    {
        return $object;
    }
}
