<?php

namespace App\Driver;

class EntityJar
{
    /**
     * @var array<mixed>
     */
    private array $jar = [];

    /**
     * @param array<string, mixed> $entity
     *
     * @return array<string, mixed>
     */
    public function add(array $entity): array
    {
        if (!isset($this->jar[$entity['id']])) {
            $this->jar[$entity['id']] = [];
        }
        $storedEntity = &$this->jar[$entity['id']];
        $this->jar[$entity['id']] = $this->mergeRecursive($storedEntity, $entity);

        return $this->jar[$entity['id']];
    }

    /**
     * @param mixed $storedEntity
     * @param mixed $entity
     *
     * @return mixed
     */
    private function mergeRecursive($storedEntity, $entity)
    {
        if (!is_array($entity)) {
            $storedEntity = $entity;

            return $storedEntity;
        }

        foreach ($entity as $attribute => $value) {
            $mergedData = $this->mergeRecursive($storedEntity[$attribute] ?? [], $value);
            $this->merge($attribute, $mergedData, $storedEntity);
        }

        return $storedEntity;
    }

    /**
     * @param string|int   $attribute
     * @param mixed        $mergedData
     * @param array<mixed> $storedEntity
     */
    private function merge($attribute, $mergedData, array &$storedEntity): void
    {
        $storedEntity[$attribute] = $mergedData;
    }
}
