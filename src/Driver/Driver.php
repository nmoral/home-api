<?php

namespace App\Driver;

abstract class Driver
{
    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    final public function create(array $object): array
    {
        $object = $this->beforeCreate($object);

        $object = $this->addId($object);
        $object['createdAt'] = new \DateTime();

        return $this->afterCreate($object);
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<mixed>
     */
    final public function update(string $id, array $object): array
    {
        $object = $this->beforeUpdate($id, $object);
        $object['modifiedAt'] = new \DateTime();

        return $this->afterUpdate($object);
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    protected function beforeCreate(array $object): array
    {
        return $object;
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    protected function afterCreate(array $object): array
    {
        return $object;
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    protected function beforeUpdate(string $id, array $object): array
    {
        return $object;
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<Mixed>
     */
    protected function afterUpdate(array $object): array
    {
        return $object;
    }

    /**
     * @param array<mixed> $object
     *
     * @return array<mixed>
     */
    private function addId(array $object): array
    {
        $object['id'] ??= uniqid();

        return $object;
    }
}
