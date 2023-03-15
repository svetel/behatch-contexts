<?php

namespace Behatch\HttpCall;

use Behat\Mink\Mink;
use Behatch\HttpCall\Request\BrowserKit;
use Behatch\HttpCall\Request\Goutte;

/**
 * @method send(string $method, string $url, array $parameters = [], array $files = [], ?string $content = null, array $headers = [])
 */
class Request
{
    /**
     * @var Mink
     */
    private Mink $mink;
    private $client;

    /**
     * @param Mink $mink
     */
    public function __construct(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * @param string $name
     * @param mixed  $arguments
     * @return mixed
     */
    public function __call(string $name, mixed $arguments): mixed
    {
        return call_user_func_array([$this->getClient(), $name], $arguments);
    }

    /**
     * @return BrowserKit|Goutte
     */
    private function getClient(): Request\BrowserKit|Request\Goutte
    {
        if (null === $this->client) {
            if ('symfony2' === $this->mink->getDefaultSessionName()) {
                $this->client = new Request\Goutte($this->mink);
            } else {
                $this->client = new Request\BrowserKit($this->mink);
            }
        }

        return $this->client;
    }
}
