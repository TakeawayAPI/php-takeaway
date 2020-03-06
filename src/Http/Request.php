<?php

namespace Takeaway\Http;

use Takeaway\Takeaway;
use Takeaway\Exceptions\RequestException;

/**
 * A generic request to the Takeaway backend.
 *
 * @api
 */
class Request
{
    /**
     * The method to call.
     * @var string
     */
    protected $method;

    /**
     * The parameters to call the method with.
     * @var array
     */
    protected $params;

    /**
     * Construct the request.
     *
     * @api
     *
     * @param string $method Method to call.
     * @param array|null $params Parameters to call the method with.
     */
    public function __construct($method, $params = [])
    {
        $this->method = $method;
        $this->params = $params;
    }

    /**
     * Get the body to be sent to the Takeaway backend.
     *
     * @return string
     */
    public function getBody()
    {
        $md5 = '';
        $body = '';

        $params = $this->params;
        array_unshift($params, $this->method);

        foreach ($params as $index => $value) {
            $md5 .= $value;
            $body .= '&var'.($index + 1).'='.($value);
        }

        $md5 = md5($md5.Takeaway::getConfigValue(Takeaway::CFG_PASSWORD));

        $body = 'var0='.$md5.$body;

        $body .= '&version='.Takeaway::getConfigValue(Takeaway::CFG_VERSION);
        $body .= '&systemversion='.Takeaway::getConfigValue(Takeaway::CFG_SYSTEM_VERSION);
        $body .= '&appname='.Takeaway::getConfigValue(Takeaway::CFG_APP_NAME);
        $body .= '&language='.Takeaway::getConfigValue(Takeaway::CFG_LANGUAGE);

        return $body;
    }

    /**
     * Execute the request, returning parsed XML.
     *
     * @api
     *
     * @throws \Takeaway\Exceptions\RequestException if the request fails.
     *
     * @return \SimpleXMLElement
     */
    public function execute()
    {
        $response = Client::instance()->request($this->getBody());
        $xml = simplexml_load_string($response);

        if (isset($xml->error)) {
            throw new RequestException((string) $xml->error->errorMessage, $response);
        }

        return $xml;
    }
}
