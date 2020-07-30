<?php

namespace App\Tests\BehatTest;

use App\Tests\Validator\JsonValidator;
use PHPUnit\Framework\TestCase;

class JsonValidatorTest extends TestCase
{
    private ?JsonValidator $validator;

    /**
     * JsonValidatorTest constructor.
     *
     * @param array<mixed> $data
     * @param string       $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->validator = new JsonValidator();
    }

    /**
     * @dataProvider getIFoundWithValueInTheResponse
     *
     * @param string|int|bool|null $value
     */
    public function testIFoundWithValueInTheResponse(string $requestContent, string $key, $value): void
    {
        self::assertTrue($this->validator->asValue($requestContent, $key, $value));
    }

    /**
     * @return \Generator<array<int, int|string|null>>
     */
    public function getIFoundWithValueInTheResponse(): \Generator
    {
        yield [
            '{"name": "foo"}',
            'name',
            'foo',
        ];

        yield [
            '{"name": {"foo": 1}}',
            'name.foo',
            1,
        ];

        yield [
            '{"name": {"foo": [1]}}',
            'name.foo.0',
            1,
        ];

        yield [
            '{"name": {"foo": [{"bar":null}]}}',
            'name.foo.0.bar',
            null,
        ];
    }
}
