<?php

namespace App\Recorder;

abstract class RecordDriver extends Driver
{
    /**
     * @param array<mixed> $object
     *
     * @return array<mixed>
     */
    protected function afterSave(array $object): array
    {
        $object = parent::afterSave($object);
        $this->record($object);

        return $object;
    }

    /**
     * @param array<mixed> $object
     */
    protected function record(array $object): bool
    {
        $this->check();
        $filename = sprintf('%s/%s', $this->storageDir, $object['id']);

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
}
