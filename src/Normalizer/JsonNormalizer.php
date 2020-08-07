<?php

namespace App\Normalizer;

class JsonNormalizer
{
    /**
     * @return array<mixed>
     */
    public function denormalize(string $json): array
    {
        return json_decode($json, true);
    }
}
