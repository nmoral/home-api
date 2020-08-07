<?php

namespace App\Tests\Behat;

use App\Tests\Validator\JsonValidator;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RestContext implements Context
{
    private KernelBrowser $browser;

    private ContainerInterface $container;

    public function __construct(KernelBrowser $browser, ContainerInterface $container)
    {
        $this->browser = $browser;
        $this->container = $container;
    }

    /**
     * @AfterScenario
     */
    public function AfterScenario(): void
    {
        $filename = $this->getFilename();
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * @When I send a :method request to :path with body:
     */
    public function iSendARequestToWithBody(string $method, string $path, PyStringNode $string): void
    {
        $this->browser->request(
            $method,
            $path,
            [],
            [],
            [],
            (string) $string
        );
    }

    /**
     * @When I send a :method request to :path
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

    /**
     * @Then I found :stringKeys in the response
     */
    public function iFoundInTheResponse(string $stringKeys): void
    {
        $json = $this->browser->getResponse()->getContent();
        if (false === $json) {
            Assert::assertTrue(false);

            return;
        }
        $jsonValidator = new JsonValidator();
        $actual = $jsonValidator->asKey($json, $stringKeys);

        Assert::assertTrue($actual);
    }

    private function getFilename(): string
    {
        $responseContent = $this->browser->getResponse()->getContent();
        if (false === $responseContent) {
            throw new \InvalidArgumentException('Unable to parse json');
        }
        $todoList = \json_decode($responseContent, true);
        if (null === $todoList) {
            throw new \InvalidArgumentException('Unable to parse json');
        }

        $dirname = $this->container->getParameter('todo_list_dir');
        $projectDir = $this->container->getParameter('kernel.project_dir');

        return sprintf('%s/%s/%s', $projectDir, $dirname, $todoList['id']);
    }
}
