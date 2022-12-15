<?php

namespace DreamFactory\Core\PubSub\Services;

use DreamFactory\Core\Services\BaseRestService;
use DreamFactory\Core\PubSub\Contracts\MessageQueueInterface;
use DreamFactory\Core\Utility\Session;
use \Illuminate\Support\Arr;

abstract class PubSub extends BaseRestService
{
    /** @var MessageQueueInterface */
    protected $client;

    public function __construct(array $settings)
    {
        parent::__construct($settings);

        $config = Arr::get($settings, 'config');
        Session::replaceLookups($config, true);
        $this->setClient($config);
    }

    /**
     * Returns the client component
     *
     * @return MessageQueueInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the messaging client;
     *
     * @param $config
     *
     * @return mixed
     */
    abstract protected function setClient($config);

    /**
     * Returns messaging queue type (AMQP or MQTT);
     *
     * @return mixed
     */
    abstract public function getQueueType();
}