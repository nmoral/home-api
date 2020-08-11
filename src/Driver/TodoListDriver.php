<?php

namespace App\Driver;

class TodoListDriver extends RecordDriver
{
    private PointDriver $pointDriver;

    public function __construct(string $todoListDir, PointDriver $pointDriver)
    {
        $this->storageDir = $todoListDir;
        $this->pointDriver = $pointDriver;
    }

    protected function beforeCreate(array $object): array
    {
        if (isset($object['points'])) {
            $points = $object['points'];
            $object['points'] = [];
            foreach ($points as $point) {
                $point = $this->pointDriver->create($point);
                $object['points'][$point['id']] = $point;
            }
        }

        return parent::beforeCreate($object);
    }

    protected function beforeUpdate(string $id, array $object): array
    {
        $object = parent::beforeUpdate($id, $object);

        if (isset($object['points'])) {
            foreach ($object['points'] as &$point) {
                $point = $this->pointDriver->update($point['id'], $point);
            }
        }

        return $object;
    }
}
