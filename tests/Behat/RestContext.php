<?php

namespace App\Tests\Behat;

use Behat\Behat\Tester\Exception\PendingException;
use App\Driver\PointDriver;
use App\Driver\TodoListDriver;
use App\Normalizer\JsonNormalizer;
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
        $filenames = $this->getFilename();
        foreach ($filenames as $filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
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

    /**
     * @Given a list with :id as id and with body:
     */
    public function aListWithAsIdAndWithBody(string $id, PyStringNode $string): void
    {
        $jsonNormalize = new JsonNormalizer();
        $todoList = $jsonNormalize->denormalize((string) $string);
        $todoList['id'] = $id;
        $pointDriver = new PointDriver();
        $todoListDriver = new TodoListDriver($this->getTodoListDir(), $pointDriver);

        $todoListDriver->create($todoList);
    }

    /**
     * @Then the list :id should no longer exist
     */
    public function theListShouldNoLongerExist($id)
    {
        $this->browser->getResponse()->getContent();

        $todoListDir = $this->getTodoListDir();
        $filename = $this->generateFilename($todoListDir, $id);
        Assert::assertFileDoesNotExist($filename);
    }

    /**
     * @return array<string>
     */
    private function getFilename(): array
    {
        $responseContent = $this->browser->getResponse()->getContent();
        if (false === $responseContent) {
            throw new \InvalidArgumentException('Unable to parse json');
        }

        if (empty($responseContent)) {
            return [];
        }

        $todoList = \json_decode($responseContent, true);
        if (null === $todoList) {
            throw new \InvalidArgumentException('Unable to parse json');
        }

        $todoListDir = $this->getTodoListDir();
        if (isset($todoList['id'])) {
            return [$this->generateFilename($todoListDir, $todoList['id'])];
        }
        $ids = [];
        foreach ($todoList as $list) {
            if (!isset($list['id'])) {
                continue;
            }
            $ids[] = $this->generateFilename($todoListDir, $list['id']);
        }

        return $ids;
    }

    private function getTodoListDir(): string
    {
        $dirname = $this->container->getParameter('todo_list_dir');
        $projectDir = $this->container->getParameter('kernel.project_dir');

        return sprintf('%s/%s', $projectDir, $dirname);
    }

    /**
     * @param string $todoListDir
     * @param $list
     * @return string
     */
    private function generateFilename(string $todoListDir, $list): string
    {
        return sprintf('%s/%s', $todoListDir, $list);
    }
}
