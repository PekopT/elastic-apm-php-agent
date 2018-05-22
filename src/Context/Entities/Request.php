<?php

namespace Hotrush\Context\Entities;

class Request implements EntityInterface
{
    /**
     * @var array
     */
    private $request = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->extractRequestData();
    }

    /**
     * @param array $request
     */
    public function setRequestData(array $request)
    {
        $this->request = array_merge_recursive($this->request, $request);
    }

    /**
     * @param $keys
     * @return void
     */
    public function removeSensitiveEnvKeys(array $keys)
    {
        if ($keys) {
            $env = $_SERVER;

            $keysArray = array_combine(
                $keys,
                array_fill(0, count($keys), '')
            );

            $this->request['env'] = array_diff_key($env, $keysArray);
        }
    }

    /**
     * Extract request data from $_SERVER and $_COOKIE vars
     *
     * @return void
     */
    private function extractRequestData()
    {
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        $serverProtocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : '';

        $this->request = [
            'http_version' => substr($serverProtocol, strpos($serverProtocol, '/')+1),
            'method' => isset($_SERVER['REQUEST_METHOD']) ?  $_SERVER['REQUEST_METHOD'] : 'cli',
            'socket' => [
                'remote_address' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '',
                'encrypted' => isset($_SERVER['HTTPS']),
            ],
            'url' => [
                'protocol' => isset($_SERVER['HTTPS']) ? 'https' : 'http',
                'hostname' => isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '',
                'port' => isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '',
                'pathname' => isset($_SERVER['REQUEST_URI'])
                    ? substr(
                        $_SERVER['REQUEST_URI'],
                        0,
                        strpos($_SERVER['REQUEST_URI'], '?') !== false
                            ? strpos($_SERVER['REQUEST_URI'], '?')
                            : null
                    )
                    : '',
                'search' => '?' . ((isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '') ? (isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '') : ''),
                'full' => isset($_SERVER['SERVER_NAME'])
                    ? (isset($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].($_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : '')
                    : '',
            ],
            'headers' => $headers,
            'env' => $_SERVER,
        ];

        if ($this->request['url']['search'] && strlen($this->request['url']['search']) > 1024) {
            $this->request['url']['search'] = substr($this->request['url']['search'], 0, 1021).'...';
        }
        if ($this->request['url']['full'] && strlen($this->request['url']['full']) > 1024) {
            $this->request['url']['full'] = substr($this->request['url']['full'], 0, 1021).'...';
        }

        if (!empty($_COOKIE)) {
            $this->request['cookies'] = $_COOKIE;
        }
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return false;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->request;
    }
}