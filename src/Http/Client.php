<?php

namespace Takeaway\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Takeaway\Takeaway;
use Takeaway\Exceptions\RequestException;

/**
 * Abstraction of the Guzzle HTTP client, taking care of exceptions and ensuring
 * we have a singleton.
 */
class Client
{
    /**
     * Singleton instance of the client.
     * @var Client
     */
    private static $instance;

    /**
     * Guzzle HTTP client.
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * Get an instance of the client.
     * @return Client
     */
    public static function instance()
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }

        return self::$instance = new Client();
    }

    /**
     * Construct the client.
     */
    public function __construct()
    {
        $this->client = new GuzzleClient();
    }

    /**
     * Make a request to the Takeaway backend.
     *
     * @throws \Takeaway\Exceptions\RequestException if the request fails.
     * @param string $body Body to send to the backend.
     * @return string
     */
    public function request($body)
    {
        try {
            $response = $this->client->request('POST', Takeaway::getConfigValue(Takeaway::CFG_BASE_URL), [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                ],
                'body' => $body,
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new RequestException('Takeaway API returned HTTP status code '.$response->getStatusCode(), (string) $response->getBody());
            }

            return (string) $response->getBody();
        } catch (GuzzleRequestException $e) {
            throw new RequestException($e->getMessage(), $e->hasResponse() ? $e->getResponse()->getBody() : '');
        }
    }
}
