<?php

namespace Behatch\Context;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behatch\HttpCall\HttpCallResultPool;
use Behatch\Json\Json;
use Behatch\Json\JsonInspector;
use Behatch\Json\JsonSchema;

class JsonContext extends BaseContext
{
    protected $inspector;

    protected $httpCallResultPool;

    public function __construct(HttpCallResultPool $httpCallResultPool, $evaluationMode = 'javascript')
    {
        $this->inspector = new JsonInspector($evaluationMode);
        $this->httpCallResultPool = $httpCallResultPool;
    }

    /**
     * Checks, that the response is correct JSON
     *
     * @Then the response should be in JSON
     */
    public function theResponseShouldBeInJson()
    {
        $this->getJson();
    }

    /**
     * Checks, that the response is not correct JSON
     *
     * @Then the response should not be in JSON
     */
    public function theResponseShouldNotBeInJson()
    {
        $this->not(
            [$this, 'theResponseShouldBeInJson'],
            'The response is in JSON'
        );
    }

    /**
     * Checks, that given JSON node is equal to given value
     *
     * @Then the JSON node :node should be equal to :text
     */
    public function theJsonNodeShouldBeEqualTo($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if ($actual != $text) {
            throw new \Exception(
                sprintf("The node value is '%s'", json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON nodes are equal to givens values
     *
     * @Then the JSON nodes should be equal to:
     */
    public function theJsonNodesShouldBeEqualTo(TableNode $nodes)
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldBeEqualTo($node, $text);
        }
    }

    /**
     * Checks, that given JSON node matches given pattern
     *
     * @Then the JSON node :node should match :pattern
     */
    public function theJsonNodeShouldMatch($node, $pattern)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if (preg_match($pattern, $actual) === 0) {
            throw new \Exception(
                sprintf("The node value is '%s'", json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node is null
     *
     * @Then the JSON node :node should be null
     */
    public function theJsonNodeShouldBeNull($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if (null !== $actual) {
            throw new \Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node is not null.
     *
     * @Then the JSON node :node should not be null
     */
    public function theJsonNodeShouldNotBeNull($node)
    {
        $this->not(function () use ($node) {
            return $this->theJsonNodeShouldBeNull($node);
        }, sprintf('The node %s should not be null', $node));
    }

    /**
     * Checks, that given JSON node is true
     *
     * @Then the JSON node :node should be true
     */
    public function theJsonNodeShouldBeTrue($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if (true !== $actual) {
            throw new \Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node is false
     *
     * @Then the JSON node :node should be false
     */
    public function theJsonNodeShouldBeFalse($node)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if (false !== $actual) {
            throw new \Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node is equal to the given string
     *
     * @Then the JSON node :node should be equal to the string :text
     */
    public function theJsonNodeShouldBeEqualToTheString($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== $text) {
            throw new \Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node is equal to the given number
     *
     * @Then the JSON node :node should be equal to the number :number
     */
    public function theJsonNodeShouldBeEqualToTheNumber($node, $number)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        if ($actual !== (float) $number && $actual !== (int) $number) {
            throw new \Exception(
                sprintf('The node value is `%s`', json_encode($actual))
            );
        }
    }

    /**
     * Checks, that given JSON node has N element(s)
     *
     * @Then the JSON node :node should have :count element(s)
     */
    public function theJsonNodeShouldHaveElements($node, $count)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        $this->assertSame($count, sizeof((array) $actual));
    }

    /**
     * Checks, that given JSON node contains given value
     *
     * @Then the JSON node :node should contain :text
     */
    public function theJsonNodeShouldContain($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        $this->assertContains($text, (string) $actual);
    }

    /**
     * Checks, that given JSON nodes contains values
     *
     * @Then the JSON nodes should contain:
     */
    public function theJsonNodesShouldContain(TableNode $nodes)
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldContain($node, $text);
        }
    }

    /**
     * Checks, that given JSON node does not contain given value
     *
     * @Then the JSON node :node should not contain :text
     */
    public function theJsonNodeShouldNotContain($node, $text)
    {
        $json = $this->getJson();

        $actual = $this->inspector->evaluate($json, $node);

        $this->assertNotContains($text, (string) $actual);
    }

    /**
     * Checks, that given JSON nodes does not contain given value
     *
     * @Then the JSON nodes should not contain:
     */
    public function theJsonNodesShouldNotContain(TableNode $nodes)
    {
        foreach ($nodes->getRowsHash() as $node => $text) {
            $this->theJsonNodeShouldNotContain($node, $text);
        }
    }

    /**
     * Checks, that given JSON node exist
     *
     * @Then the JSON node :name should exist
     */
    public function theJsonNodeShouldExist($name)
    {
        $json = $this->getJson();

        try {
            $node = $this->inspector->evaluate($json, $name);
            if(null === $node){
                throw new \Exception();
            }
        } catch (\Exception $e) {
            throw new \Exception("The node '$name' does not exist.");
        }
        return $node;
    }

    /**
     * Checks, that given JSON node does not exist
     *
     * @Then the JSON node :name should not exist
     */
    public function theJsonNodeShouldNotExist($name)
    {
        $this->not(function () use ($name) {
            return $this->theJsonNodeShouldExist($name);
        }, "The node '$name' exists.");
    }

    /**
     * @Then the JSON should be valid according to this schema:
     */
    public function theJsonShouldBeValidAccordingToThisSchema(PyStringNode $schema)
    {
        $this->inspector->validate(
            $this->getJson(),
            new JsonSchema($schema)
        );
    }

    /**
     * @Then the JSON should be invalid according to this schema:
     */
    public function theJsonShouldBeInvalidAccordingToThisSchema(PyStringNode $schema)
    {
        $this->not(function () use ($schema) {
            return $this->theJsonShouldBeValidAccordingToThisSchema($schema);
        }, 'Expected to receive invalid json, got valid one');
    }

    /**
     * @Then the JSON should be valid according to the schema :filename
     */
    public function theJsonShouldBeValidAccordingToTheSchema($filename)
    {
        $this->checkSchemaFile($filename);

        $this->inspector->validate(
            $this->getJson(),
            new JsonSchema(
                file_get_contents($filename),
                'file://' . str_replace(DIRECTORY_SEPARATOR, '/', realpath($filename))
            )
        );
    }

    /**
     * @Then the JSON should be invalid according to the schema :filename
     */
    public function theJsonShouldBeInvalidAccordingToTheSchema($filename)
    {
        $this->checkSchemaFile($filename);

        $this->not(function () use ($filename) {
            return $this->theJsonShouldBeValidAccordingToTheSchema($filename);
        }, "The schema was valid");
    }

    /**
     * @Then the JSON should be equal to:
     */
    public function theJsonShouldBeEqualTo(PyStringNode $content)
    {
        $actual = $this->getJson();

        try {
            $expected = new Json($content);
        } catch (\Exception $e) {
            throw new \Exception('The expected JSON is not a valid');
        }

        $this->assertSame(
            (string) $expected,
            (string) $actual,
            "The json is equal to:\n". $actual->encode()
        );
    }

    /**
     * @Then print last JSON response
     */
    public function printLastJsonResponse()
    {
        echo $this->getJson()
            ->encode();
    }

    /**
     * Checks, that response JSON matches with a swagger dump
     *
     * @Then the JSON should be valid according to swagger :dumpPath dump schema :schemaName
     */
    public function theJsonShouldBeValidAccordingToTheSwaggerSchema($dumpPath, $schemaName)
    {
        $this->checkSchemaFile($dumpPath);

        $dumpJson = file_get_contents($dumpPath);
        $schemas = json_decode($dumpJson, true);
        $definition = json_encode(
            $schemas['definitions'][$schemaName]
        );
        $this->inspector->validate(
            $this->getJson(),
            new JsonSchema(
                $definition
            )
        );
    }
    /**
     *
     * Checks, that response JSON not matches with a swagger dump
     *
     * @Then the JSON should not be valid according to swagger :dumpPath dump schema :schemaName
     */
    public function theJsonShouldNotBeValidAccordingToTheSwaggerSchema($dumpPath, $schemaName)
    {
        $this->not(function () use ($dumpPath, $schemaName) {
            return $this->theJsonShouldBeValidAccordingToTheSwaggerSchema($dumpPath, $schemaName);
        }, 'JSON Schema matches but it should not');
    }



    protected function getJson()
    {
        return new Json($this->httpCallResultPool->getResult()->getValue());
    }

    private function checkSchemaFile($filename)
    {
        if (false === is_file($filename)) {
            throw new \RuntimeException(
                'The JSON schema doesn\'t exist'
            );
        }
    }
}
