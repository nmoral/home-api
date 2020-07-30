<?php

namespace App\Tests\Validator;

class JsonValidator
{
    /**
     * @param mixed|null $value
     */
    public function asValue(string $json, string $stringKeys, $value = null): bool
    {
        return $value === $this->exploreJson($json, $stringKeys);
    }

    public function asKey(string $json, string $stringKeys): bool
    {
        $this->exploreJson($json, $stringKeys);

        return true;
    }

    /**
     * @return mixed
     */
    protected function exploreJson(string $json, string $stringKeys)
    {
        $result = json_decode($json, true);
        $keys = explode('.', $stringKeys);
        $key = null;
        foreach ($keys as $key) {
            if (!key_exists($key, $result)) {
                throw new \InvalidArgumentException($key.' not found in '.$json);
            }
            $result = $result[$key];
        }

        return $result;
    }
}
