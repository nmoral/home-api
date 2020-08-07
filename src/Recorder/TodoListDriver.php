<?php

namespace App\Recorder;

class TodoListDriver extends RecordDriver
{
    private PointDriver $pointDriver;

    public function __construct(string $todoListDir, PointDriver $pointDriver)
    {
        $this->storageDir = $todoListDir;
        $this->pointDriver = $pointDriver;
    }

    protected function beforeSave(array $object): array
    {
        if (isset($object['points'])) {
            foreach ($object['points'] as &$point) {
                $point = $this->pointDriver->save($point);
            }
        }

        return parent::beforeSave($object);
    }
}
