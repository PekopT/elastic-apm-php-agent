<?php

namespace Hotrush\Middleware;

use \Hotrush\Agent;
use \Hotrush\Stores\ErrorsStore;
use \Hotrush\Stores\TransactionsStore;
use \Hotrush\Serializers\Errors;
use \Hotrush\Serializers\Transactions;

use \GuzzleHttp\Psr7\Request;
use \GuzzleHttp\Client;

/**
 *
 * Connector which Transmits the Data to the Endpoints
 *
 */
class Connector
{

    /**
     * Agent Config
     *
     * @var \Hotrush\Helper\Config
     */
    private $config;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @param \Hotrush\Helper\Config $config
     */
    public function __construct(\Hotrush\Helper\Config $config)
    {
        $this->config = $config;
        $this->client = new Client();
    }

    /**
     * Push the Transactions to APM Server
     *
     * @param \Hotrush\Stores\TransactionsStore $store
     *
     * @return bool
     */
    public function sendTransactions(TransactionsStore $store)
    {
        $request = new Request(
            'POST',
            $this->getEndpoint('transactions'),
            $this->getRequestHeaders(),
            json_encode(new Transactions($this->config, $store))
        );
//        print_r(json_encode(new Transactions($this->config, $store)));die();

        $response = $this->client->send($request);
        return ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300);
    }

    /**
     * Push the Errors to APM Server
     *
     * @param \Hotrush\Stores\ErrorsStore $store
     *
     * @return bool
     */
    public function sendErrors(ErrorsStore $store)
    {
        $request = new Request(
            'POST',
            $this->getEndpoint('errors'),
            $this->getRequestHeaders(),
            json_encode(new Errors($this->config, $store))
        );

//        print_r(json_encode(new Errors($this->config, $store)));die();

        $response = $this->client->send($request);
        return ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300);
    }

    /**
     * Get the Endpoint URI of the APM Server
     *
     * @param string $endpoint
     *
     * @return string
     */
    private function getEndpoint($endpoint)
    {
        return sprintf(
            '%s/%s/%s',
            $this->config->get('serverUrl'),
            $this->config->get('apmVersion'),
            $endpoint
        );
    }

    /**
     * Get the Headers for the POST Request
     *
     * @return array
     */
    private function getRequestHeaders()
    {
        // Default Headers Set
        $headers = [
            'Content-Type' => 'application/json',
            'User-Agent' => sprintf('elasticapm-php/%s', Agent::VERSION),
        ];

        // Add Secret Token to Header
        if ($this->config->get('secretToken') !== null) {
            $headers['Authorization'] = sprintf('Bearer %s', $this->config['secretToken']);
        }

        return $headers;
    }

}
