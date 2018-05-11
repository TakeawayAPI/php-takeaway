<?php

namespace Takeaway\Traits;
use Takeaway\Model;
use Takeaway\Http\Request;
use Takeaway\Exceptions\ParseException;

/**
 * Classes with this trait are able to resolve the XML returned by Takeaway
 * servers to a human readable structure of arrays, objects and models.
 */
trait MapsResponses
{
    /**
     * Get the specification for mapping XML to PHP structures.
     *
     * @see \Takeaway\Traits\MapsResponses::getData()
     *
     * @internal
     *
     * @return array
     */
    protected abstract function getMapping();

    /**
     * Map XML data to PHP data.
     *
     * This function maps the data, given by `$data`, to a more PHP / human
     * friendly representation, complying with `$spec`. The specification is
     * given as an associative array, where the key is the location of the
     * data in the XML, and the value specifies what to do with that data.
     *
     * The key consists of tag names, optionally separated by periods to
     * indicate hierarchy. To get all `b` childs in an `a` element, simply
     * use `a.b`. The location does not specify the data type of the element,
     * so `a.b` may target both a child `b` element in an `a` element, or all
     * children `b` of an `a` element.
     *
     * The specification can either be a string, an array consisting of a string
     * or an associative array with options for more advanced data
     * transformations.
     *
     * If the specification is a string, the XML value is stored with the string
     * as its name. A modifier may be prepended to the string, to indicate that
     * the XML value should be cast to a certain data type. The following casts
     * are supported:
     *
     * - `#name` casts the value to an integer.
     * - `.name` casts the value to a float.
     * - `!name` casts the value to a boolean.
     *
     * If the string spefication is the only element of an array, like
     * `['.cast']`, then the XML value is considered an array of scalar values,
     * which will each be converted according to the specification. The
     * resulting array is then stored in the given name.
     *
     * More advanced specifications take the form of associative arrays with at
     * least a `name` and a `type`. Here, `name` describes the name of the value
     * in the resulting data structure, and `type` specifies what to do with the
     * XML data.
     *
     * Sometimes, XML data contains associative-ish arrays, like the following
     * example taken directly from Takeaway's API:
     *
     * ```
     * <cn>
     *  <nl>Nederland</nl>
     *  <de>Niederlande</de>
     *  <fr>Pays-Bas</fr>
     *  <en>Netherlands</en>
     * </cn>
     * ```
     *
     * One would probably want the resulting data structure to take the form of
     * `['nl' => 'Nederland', 'de' => 'Niederlande', ...]`. To do this, simply
     * specify `type` as `array`:
     *
     * ```
     * 'cn' => [
     *  'name' => 'names',
     *  'type' => 'object'
     * ]
     * ```
     *
     * Finally, data may be converted to instances of a model. The `type` should
     * then be `class`, and an additional field `class` dictates the name of the
     * model class. The `class` field may be an array consisting of the class
     * name, in which case the resulting data structure is an array of models.
     *
     * The `mapping` field dictates where data should end up in the class. This
     * field is also a data specification and follows the same rules as the
     * `$spec` parameter of this method. Example:
     *
     * ```
     * 'dd.da' => [
     *  'name' => 'deliverAreas',
     *  'type' => 'class',
     *  'class' => [DeliverArea::class],
     *  'mapping' => [
     *   'pc.pp' => ['postalCode'],
     *   'ma' => 'minimumAmount',
     *   'co' => [
     *    'name' => 'deliverCosts',
     *    'type' => 'class',
     *    'class' => DeliverCosts::class,
     *    'mapping' => [
     *     'fr' => 'from',
     *     'to' => 'to',
     *     'ct' => 'costs'
     *    ]
     *   ]
     *  ]
     * ],
     * ```
     *
     * @api
     *
     * @throws \Takeaway\Exceptions\ParseException
     *  If the parsing fails, or if the specification contains an error.
     *
     * @param array $spec Data specification.
     * @param array $data The data to process.
     * @return array
     */
    public function getData($spec = null, $data = null)
    {
        // Use our own specification on first call.
        $spec = $spec ?? $this->getMapping();

        // Try to use our own data by fetching it, if we are a request.
        if ($data === null) {
            if (!$this instanceof Request) {
                throw new ParseException('Trying to call getData() on an object which is not a request, without given data.');
            }

            $data = $this->execute();
        }

        $result = [];

        /**
         * @var string $remote
         * @var string|array $local
         */
        foreach ($spec as $remote => $local) {
            $remote = explode('.', $remote);

            $value = (array) $data;

            $i = 0;

            foreach ($remote as $path)
            {
                if (!isset($value[$path])) {
                    $value = null;
                    break;
                }

                $value = $value[$path];

                if (++$i < count($remote)) {
                    $value = (array)$value;
                }
            }

            // The value is `null` if we could not find it. In that case, we
            // simply skip it.
            if ($value !== null) {
                $this->resolve($result, $value, $local);
            }
        }

        return $result;
    }

    /**
     * Resolve a found piece of XML data to its PHP representation.
     *
     * @internal
     *
     * @param array $result The resulting data structure we have so far.
     * @param \SimpleXMLElement|string $value The XML data structure.
     * @param string|array $local Spefication of what the PHP representation
     *                            should look like.
     * @return void
     */
    protected function resolve(&$result, $value, $local)
    {
        // Here, we check if `$local` has the most simple specification; a
        // string representing the human readable name of the value. It may be
        // preceeded by a modifier, which casts the value to a certain data
        // type.
        if (is_string($local)) {
            list($key, $data) = $this->resolveSimple($value, $local);

            return $result[$key] = $data;
        }

        // One might also encapsulate the simple specification in an array
        // containing solely the simple specification, i.e. `['postalCode']`.
        if (is_array($local) && isset($local[0]) && is_string($local[0])) {
            list($key, $data) = $this->resolveSimpleArray($value, $local);

            return $result[$key] = $data;
        }

        // At this point, only advanced specifications are allowed, which take
        // the form of associative arrays.
        if (!is_array($local)) {
            throw new ParseException('Data specification is invalid!');
        }

        switch ($local['type']) {
            case 'object':
                // Change `<a>a</a><b>b</b>` to `['a' => 'a', 'b' => 'b']`.
                // We simply cast to array; SimpleXML does the rest for us.
                return $result[$local['name']] = (array) $value;

            case 'class':
                // Map all data in `mapping` using this method and put all the
                // data in a model.
                list($key, $data) = $this->resolveClass($value, $local);

                return $result[$key] = $data;

            default:
                throw new ParseException('Unknown parse type '.$local['type'].' in specification!');
        }
    }

    /**
     * Resolve a `class` specification. Automatically resolves class arrays.
     *
     * This function returns an array consisting of the name of the resulting
     * variable, and the data it should contain/
     *
     * @param array|\SimpleXMLElement $value XML data.
     * @param array $local Data specification.
     * @return array
     */
    protected function resolveClass($value, $local)
    {
        $result = null;

        if (is_array($local['class'])) {
            $result = [];
            $class = $local['class'][0];

            if (is_array($value)) {
                foreach ($value as $element) {
                    $result[] = new $class($this->getData($local['mapping'], $element), $local['extra'] ?? []);
                }
            }
        } else {
            $result = new $local['class']($this->getData($local['mapping'], $value), $local['extra'] ?? []);
        }

        return [$local['name'], $result];
    }

    /**
     * Resolve an array of simple, scalar values.
     *
     * This function returns an array consisting of the name of the resulting
     * variable, and the data it should contain/
     *
     * @param array|\SimpleXMLElement $value XML data.
     * @param array $local Data specification.
     * @return array
     */
    protected function resolveSimpleArray($value, $local)
    {
        $result = [];

        $local = $local[0];

        $key = $local;

        if (preg_match('/^[!#\\.]/', $local)) {
            $key = substr($local, 1);
        }

        if (is_array($value)) {
            foreach ($value as $element) {
                list($_, $data) = $this->resolveSimple($element, $local);

                $result[] = $data;
            }
        }

        return [$key, $result];
    }

    /**
     * Resolve a simple, scalar value.
     *
     * This function returns an array consisting of the name of the resulting
     * variable, and the data it should contain/
     *
     * @param array|\SimpleXMLElement $value XML data.
     * @param array $local Data specification.
     * @return array
     */
    protected function resolveSimple($value, $local)
    {
        $modifier = '';

        $key = $local;

        if (preg_match('/^[!#\\.]/', $local)) {
            $key = substr($local, 1);
            $modifier = substr($local, 0, 1);
        }

        switch ($modifier) {
            case '!':
                return [$key, (string) $value === '1'];
            case '#':
                return [$key, intval($value)];
            case '.':
                return [$key, floatval($value)];
            default:
                return [$key, (string) $value];
        }
    }
}
