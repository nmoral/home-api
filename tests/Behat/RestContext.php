<?php

namespace App\Tests\Behat;

use App\Tests\Validator\JsonValidator;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class RestContext implements Context
{
    private KernelBrowser $browser;

    public function __construct(KernelBrowser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @When I send a :method request to :path with body:
     */
    public function iSendARequestToWithBody(string $method, string $path, PyStringNode $string): void
    {
        $this->browser->request($method, $path);
    }

    /**
     * @When I send a :method request to :path with body:
     */
    public function iSendARequestTo(string $method, string $path): void
    {
        $this->browser->request($method, $path);
    }

    /**
     * @Then the response should be in JSON
     */
    public function theResponseShouldBeInJson(): void
    {
        $content = $this->browser->getResponse()->getContent();
        if (false === $content) {
            Assert::assertTrue(false);

            return;
        }
        Assert::assertJson($content);
    }

    /**
     * @Then I found :stringKeys with value :value in the response
     */
    public function iFoundWithValueInTheResponse(string $stringKeys, string $value): void
    {
        $json = $this->browser->getResponse()->getContent();
        if (false === $json) {
            Assert::assertTrue(false);

            return;
        }
        $jsonValidator = new JsonValidator();
        $actual = $jsonValidator->asValue($json, $stringKeys, $value);

        Assert::assertTrue($actual);
    }
}
