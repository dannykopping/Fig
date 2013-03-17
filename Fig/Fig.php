<?php
namespace Fig;

class Fig
{
    /**
     * @var array   The configuration values
     */
    private static $values;

    /**
     * Initialize the configuration options
     *
     * @param $values
     */
    public static function setUp($values)
    {
        self::$values = $values;
    }

    /**
     * Get a configuration option
     *
     * Use a single key for top-level configurations,
     * or use dot-notation to specify hierarchy
     *
     * @param $name
     * @return array|null
     */
    public static function get($name)
    {
        if (!isset(self::$values)) {
            self::$values = array();
        }

        $tree = explode(".", $name);
        if (count($tree) <= 1) {
            if (isset(self::$values[$name])) {
                return self::$values[$name];
            }

            return null;
        }

        $node = self::$values;
        foreach ($tree as $branch) {
            if (!isset($node[$branch])) {
                return null;
            }

            $node = $node[$branch];
        }

        return $node;
    }

    /**
     * Set a configuration option
     *
     * Use a single key for top-level configurations,
     * or use dot-notation to specify hierarchy
     *
     * @param $name
     * @param $value
     */
    public static function set($name, $value)
    {
        if (!isset(self::$values)) {
            self::$values = array();
        }

        $tree = explode(".", $name);
        if (count($tree) <= 1) {
            self::$values[$name] = $value;
            return;
        }

        $node =& self::$values;
        foreach ($tree as $branch) {
            if (!isset($node[$branch])) {
                $node[$branch] = "";
            }

            $node =& $node[$branch];
        }

        $node = $value;
    }

    /**
     * Unset a configuration option
     *
     * Use a single key for top-level configurations,
     * or use dot-notation to specify hierarchy
     *
     * @param $name
     */
    public static function delete($name)
    {
        if (!isset(self::$values)) {
            self::$values = array();
        }

        $tree = explode(".", $name);
        if (count($tree) <= 1) {
            unset(self::$values[$name]);
            return;
        }

        $node = self::$values;
        foreach ($tree as $branch) {
            if (!isset($node[$branch])) {
                $node[$branch] = "";
            }

            $node = $node[$branch];
        }

        self::removeElement($node, self::$values);
    }

    /**
     * Removes a node from a given array
     *
     * @see http://stackoverflow.com/questions/1068099/php-remove-array-element-by-element-pointer-alias
     *
     * @param $element
     * @param array $array
     * @return bool
     */
    private static function removeElement($element, array &$array)
    {
        foreach ($array as $key => &$value) {
            if ($element == $value) {
                unset($array[$key]);
                return true;
            } else {
                if (is_array($value)) {
                    $found = self::removeElement($element, $value);
                    if ($found) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Clear all configuration values
     */
    public static function clear()
    {
        self::$values = null;
    }

    /**
     * Get all configuration values
     *
     * @return  array
     */
    public static function getAll()
    {
        return self::$values;
    }
}
