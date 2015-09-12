<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2015 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Contao\Toolkit\Dca;

/**
 * Definition provides easy access to the data container definition array.
 *
 * @package Netzmacht\Contao\Toolkit\Dca
 */
class Definition
{
    /**
     * The dca definition.
     *
     * @var array
     */
    private $dca;

    /**
     * Definition constructor.
     *
     * @param array $dca The data definition array.
     */
    public function __construct(array &$dca)
    {
        $this->dca =& $dca;
    }

    /**
     * Get the path as array.
     *
     * @param mixed $path The path as "/" separted string or array.
     *
     * @return array
     */
    private function path($path)
    {
        if (!is_array($path)) {
            $path = explode('/', $path);
        }

        return $path;
    }

    /**
     * Get from the dca.
     *
     * @param array|string $path    The path.
     * @param mixed        $default The default value.
     *
     * @return mixed.
     */
    public function &get($path, $default = null)
    {
        $dca =& $this->dca;

        foreach ($this->path($path) as $key) {
            if (!is_array($dca) || !array_key_exists($key, $dca)) {
                return $default;
            }

            $dca =& $dca[$key];
        }

        return $dca;
    }

    /**
     * Check if the definition has a configuration.
     *
     * @param array|string $path The path as string or array.
     *
     * @return bool
     */
    public function has($path)
    {
        $dca =& $this->dca;

        foreach ($this->path($path) as $key) {
            if (!is_array($dca) || !array_key_exists($key, $dca)) {
                return false;
            }

            $dca =& $dca[$key];
        }

        return true;
    }

    /**
     * Set a configuration in the data definition array.
     *
     * @param array|string $path  The path as string or array.
     * @param mixed        $value The value.
     *
     * @return bool
     */
    public function set($path, $value)
    {
        $path    = is_array($path) ? $path : explode('/', $path);
        $current =& $this->dca;

        foreach ($path as $key) {
            if (!is_array($current)) {
                return false;
            }

            if (!isset($current[$key])) {
                $current[$key] = array();
            }

            unset($tmp);
            $tmp =& $current;

            unset($current);
            $current =& $tmp[$key];
        }

        $current = $value;

        return true;
    }

    /**
     * Get the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->get(['config', 'name']);
    }
}