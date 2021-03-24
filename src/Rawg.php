<?php

namespace Rawg;

use Illuminate\Support\Arr;

/**
 * Collection of methods that perform a RESTful request to RAWG
 *
 * @author Danny Herran <this@dannyherran.com>
 */

class Rawg
{

    /*
    |--------------------------------------------------------------------------
    | Base API URL
    |--------------------------------------------------------------------------
    |
     */
    protected $api_url;

    /*
    |--------------------------------------------------------------------------
    | User Agent [deprecated]
    |--------------------------------------------------------------------------
    |
    |
    |
     */
    protected $user_agent;

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    |
    |
     */
    protected $api_key;

    /*
    |--------------------------------------------------------------------------
    | Service Endpoint
    |--------------------------------------------------------------------------
    |
    |
    |
     */
    protected $endpoint;

    /*
    |--------------------------------------------------------------------------
    | Service URL
    |--------------------------------------------------------------------------
    |
    |
    |
     */
    protected $request_url;

    /*
    |--------------------------------------------------------------------------
    | Verify SSL Peer
    |--------------------------------------------------------------------------
    |
    |
    |
     */
    protected $verify_ssl;

    /*
    |--------------------------------------------------------------------------
    | Params
    |--------------------------------------------------------------------------
    |
    |
    |
     */
    protected $params = [];


    /**
     * Class constructor
     */
    public function __construct()
    {
        //
    }

    /**
     * Setting the API URL
     *
     * @return $this
     */
    public function setApiUrl()
    {
        $this->api_url = config('rawg.api_url');
        return $this;
    }

    /**
     * Getting the API URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->api_url;
    }

    /**
     * Setting user_agent
     *
     * @return $this
     */
    public function setUserAgent()
    {
        $this->user_agent = config('rawg.user_agent');
        return $this;
    }

    /**
     * Getting user_agent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
    }

    /**
     * Setting api_key
     *
     * @return $this
     */
    public function setApiKey()
    {
        $this->api_key = config('rawg.api_key');
        return $this;
    }

    /**
     * Getting api_key
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api_key;
    }

    /**
     * Set parameter by key
     *
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setParamByKey(string $key, $value)
    {
        Arr::set($this->params, $key, $value);

        return $this;
    }

    /**
     * Get parameter by the key
     *
     * @param string $key
     * @return mixed
     */
    public function getParamByKey(string $key)
    {
        if (array_key_exists($key, array_dot($this->params))) {
            return array_get($this->params, $key);
        }
    }

    /**
     * Set all parameters at once
     *
     * @param array $param
     * @return $this
     */
    public function setParams(array $param)
    {
        $this->params = array_merge($this->params, $param);

        return $this;
    }

    /**
     * Return parameters array
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get a webservice response
     *
     * @param string $needle - response key
     * @return string
     */
    public function get(string $needle = '')
    {
        return empty($needle)
        ? $this->getResponse()
        : $this->getResponseByKey($needle);
    }

    /**
     * Get response value by key
     *
     * @param string $needle - retrieves response parameter using "dot" notation
     * @param int $offset
     * @param int $length
     * @return array
     */
    public function getResponseByKey(string $needle = '', $offset = 0, $length = null)
    {
        $obj = $this->get();

        if (empty($needle)) {
            return $obj;
        }

        // Find and return the key
        return array_get($obj, $needle, null);
    }

    /*
    |--------------------------------------------------------------------------
    | Protected methods
    |--------------------------------------------------------------------------
    |
     */

    /**
     * Setup the request basics
     *
     * @return void
     * @throws \ErrorException
     */
    public function load(string $endpoint = '')
    {
        if (empty($endpoint)) {
            throw new \ErrorException('A valid endpoint is required.');
        }

        $this->validateConfig();

        // Set the API URL
        $this->setApiUrl();

        // Set the API Key
        $this->setApiKey();

        // Set the user agent
        $this->setUserAgent();

        // Is ssl_verify_peer key set, use it, otherwise use default key
        $this->verify_ssl = empty(config('rawg.ssl_verify_peer'))
        ? false
        : config('rawg.ssl_verify_peer');

        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Validate the configuration file
     *
     * @throws \ErrorException
     */
    protected function validateConfig()
    {
        // Check for config file
        if (!\Config::has('rawg')) {
            throw new \ErrorException('Unable to find RAWG config file.');
        }

        // if (!array_key_exists('api_key', config('rawg'))) {
        //     throw new \ErrorException('Unable to find API Key param in config file.');
        // }

        if (!array_key_exists('user_agent', config('rawg'))) {
            throw new \ErrorException('Unable to find User Agent param in config file.');
        }

        if (!array_key_exists('api_url', config('rawg'))) {
            throw new \ErrorException('API URL must be declared in the config file.');
        }
    }

    /**
     * Get the RESTful response
     *
     * @return type
     */
    protected function getResponse()
    {
        // Replace any curly brackets placeholders
        $endpoint = preg_replace_callback('~\{([^}]*)\}~', function ($matches) {
            return $this->params[$matches[1]] ?? null;
        }, $this->endpoint);

        // Set the API Key
        $this->setParamByKey('key', $this->api_key);

        $this->request_url = $this->api_url . $endpoint;
        $this->request_url .= '?' . http_build_query($this->params);

        return $this->make();
    }

    /**
     * Make a cURL request to a given URL
     *
     * @return object
     */
    protected function make()
    {
        $ch = curl_init($this->request_url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);

        $output = curl_exec($ch);

        if ($output === false) {
            throw new \ErrorException(curl_error($ch));
        }

        curl_close($ch);

        return json_decode($output, true);
    }
}
