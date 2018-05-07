<?php

namespace Takeaway\Http;

use Takeaway\Traits\MapsResponses;

abstract class PrefabRequest extends Request
{
    // Every pre-fab request is required to map its response.
    use MapsResponses;

    /**
     * Extra data to remember after the request.
     * @var array
     */
    protected $extra;

    /**
     * Construct the request.
     * @param string $method Method to call.
     * @param array $params Parameters to call the method with.
     * @param array $extra Extra data to remember after the request.
     */
    public function __construct($method, $params = [], $extra = [])
    {
        parent::__construct($method, $params);

        $this->extra = $extra;
    }
}
