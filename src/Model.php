<?php

namespace Takeaway;

use Takeaway\Traits\MakesRequests;

/**
 * A model containing data, and possibly having related models.
 */
abstract class Model
{
    /**
     * Data of the model.
     * @var array
     */
    protected $data;

    /**
     * Extra data which should not be publicly accessible.
     * @var array
     */
    protected $extra;

    /**
     * Whether or not the model has lazy loaded extra data.
     * @var boolean
     */
    protected $hasLazyLoaded;

    /**
     * Construct a new model.
     * @param array|null $data Data of the model.
     * @param array|null $extra Extra data which should not be publicly
     *                          accessible.
     */
    public function __construct($data = [], $extra = [])
    {
        $this->data = $data;
        $this->extra = $extra;

        $this->hasLazyLoaded = false;
    }

    /**
     * Lazy load extra data. Implementations might implement this method if they
     * are able to fetch more data on-the-fly.
     *
     * @return void
     */
    protected function lazyLoad()
    {
        //
    }

    /**
     * Update the data in the model.
     *
     * Duplicate keys will prefer the provided data.
     *
     * @param array $data New data to add.
     * @return void
     */
    protected function fill($data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * Access a property of the model.
     *
     * Attempts to lazy load data if it has not done so already.
     *
     * @param string $name Name of the property.
     * @return mixed|null
     */
    public function __get($name)
    {
        if (!isset($this->data[$name])) {
            if (!$this->hasLazyLoaded) {
                $this->hasLazyLoaded = true;
                $this->lazyLoad();
            }
        }

        return $this->data[$name] ?? null;
    }
}
