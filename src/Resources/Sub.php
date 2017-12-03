<?php

namespace DreamFactory\Core\PubSub\Resources;

use DreamFactory\Core\Resources\BaseRestResource;

class Sub extends BaseRestResource
{
    const RESOURCE_NAME = 'sub';

    /** A resource identifier used in swagger doc. */
    const RESOURCE_IDENTIFIER = 'name';

    /** @var \DreamFactory\Core\PubSub\Services\PubSub */
    protected $parent;

    /**
     * {@inheritdoc}
     */
    protected static function getResourceIdentifier()
    {
        return static::RESOURCE_IDENTIFIER;
    }

    /** {@inheritdoc} */
    protected function getApiDocPaths()
    {
        $service = $this->getServiceName();
        $capitalized = camelize($service);
        $resourceName = strtolower($this->name);
        $path = '/' . $resourceName;

        $base = [
            $path => [
                'get'    => [
                    'summary'     => 'Retrieves subscribed topic(s)',
                    'description' => 'Retrieves subscribed topic(s)',
                    'operationId' => 'get' . $capitalized . 'SubscriptionTopics',
                    'responses'   => [
                        '200' => [
                            'description' => 'Success',
                            'content'     => [
                                'application/json' => [
                                    'schema' => [
                                        'type'  => 'array',
                                        'items' => [
                                            'type'       => 'object',
                                            'required'   => ['topic', 'service'],
                                            'properties' => [
                                                'topic'   => ['type' => 'string'],
                                                'service' => [
                                                    'type'       => 'object',
                                                    'required'   => ['endpoint'],
                                                    'properties' => [
                                                        'endpoint'  => [
                                                            'type'        => 'string',
                                                            'description' => 'Internal DreamFactory Endpoint. Ex: system/role'
                                                        ],
                                                        'verb'      => [
                                                            'type'        => 'string',
                                                            'description' => 'GET, POST, PATCH, PUT, DELETE'
                                                        ],
                                                        'parameter' => [
                                                            'type'  => 'array',
                                                            'items' => [
                                                                "{name}" => "{value}"
                                                            ]
                                                        ],
                                                        'header'    => [
                                                            'type'  => 'array',
                                                            'items' => [
                                                                "{name}" => "{value}"
                                                            ]
                                                        ],
                                                        'payload'   => [
                                                            'type'  => 'array',
                                                            'items' => [
                                                                "{name}" => "{value}"
                                                            ]
                                                        ]
                                                    ]
                                                ],
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ],
                    ],
                ],
                'post'   => [
                    'summary'     => 'Subscribes to topic(s)',
                    'description' => 'Subscribes to topic(s)',
                    'operationId' => 'subscribeTo' . $capitalized . 'Topics',
                    'requestBody' => [
                        'description' => 'Device token to register',
                        'content'     => [
                            'application/json' => [
                                'schema' => [
                                    'type'  => 'array',
                                    'items' => [
                                        'type'       => 'object',
                                        'required'   => ['topic', 'service'],
                                        'properties' => [
                                            'topic'   => ['type' => 'string'],
                                            'service' => [
                                                'type'       => 'object',
                                                'required'   => ['endpoint'],
                                                'properties' => [
                                                    'endpoint'  => [
                                                        'type'        => 'string',
                                                        'description' => 'Internal DreamFactory Endpoint. Ex: system/role'
                                                    ],
                                                    'header'    => [
                                                        'type'  => 'array',
                                                        'items' => [
                                                            "{name}" => "{value}"
                                                        ]
                                                    ],
                                                    'verb'      => [
                                                        'type'        => 'string',
                                                        'description' => 'GET, POST, PATCH, PUT, DELETE'
                                                    ],
                                                    'parameter' => [
                                                        'type'  => 'array',
                                                        'items' => [
                                                            "{name}" => "{value}"
                                                        ]
                                                    ],
                                                    'payload'   => [
                                                        'type'  => 'array',
                                                        'items' => [
                                                            "{name}" => "{value}"
                                                        ]
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'required'    => true
                    ],
                    'responses'   => [
                        '200' => ['$ref' => '#/components/responses/Success']
                    ],
                ],
                'delete' => [
                    'summary'     => 'Terminate subscriptions',
                    'description' => 'Terminates subscriptions to all topic(s)',
                    'operationId' => 'terminatesSubscriptionsTo' . $capitalized,
                    'responses'   => [
                        '200' => ['$ref' => '#/components/responses/Success']
                    ],
                ]
            ]
        ];

        return $base;
    }
}