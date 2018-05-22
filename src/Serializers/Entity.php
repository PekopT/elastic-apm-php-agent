<?php

namespace Hotrush\Serializers;

use \Hotrush\Agent;
use \Hotrush\Helper\Config;

/**
 *
 * Base Class with Common Settings for the Serializers
 *
 */
class Entity
{

    /**
     * @var \Hotrush\Helper\Config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get the shared Schema Skeleton
     *
     * @return array
     */
    protected function getSkeleton()
    {
        return [
            'service' => [
                'name' => $this->config->get('appName'),
                'version' => $this->config->get('appVersion'),
                'pid' => getmypid(),
                'language' => [
                    'name' => 'php',
                    'version' => phpversion()
                ],
                'agent' => [
                    'name' => Agent::NAME,
                    'version' => Agent::VERSION
                ]
            ],
            'system' => [
                'hostname' => $this->config->get('hostname'),
                'architecture' => php_uname('m'),
                'platform' => php_uname('s')
            ]
        ];
    }

}
