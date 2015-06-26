<?php namespace Matiux\Types;

use Matiux\Types\Exceptions\TypeUtilitiesException;

class String
{
    private $_str;
    private $_strLength;
    private $_lowerAlphabet = [];
    private $_upperAlphabet = [];

    public function __construct($string = '')
    {
        if ('' != $string)
            $this->init($string);
    }

    public function set($string) {

        $this->init($string);
    }

    private function init($string)
    {
        $string                     = $this->checkType($string);

        $this->_str                 = $string;
        $this->_strLength           = $this->strLength();
        $this->_lowerAlphabet       = $this->lowerAlphabet();
        $this->_upperAlphabet       = $this->upperAlphabet();
    }

    private function lowerAlphabet()
    {
        $alphabet = [];

        for ($alpha = 'a'; $alpha != 'aa'; $alpha++)
            $alphabet[] = $alpha;

        return $alphabet;
    }

    private function upperAlphabet()
    {
        $alphabet = [];

        for ($alpha = 'A'; $alpha != 'AA'; $alpha++)
            $alphabet[] = $alpha;

        return $alphabet;
    }

    private function checkType($string)
    {
        $type = gettype($string);

        switch($type)
        {
            case 'array':
                throw new TypeUtilitiesException('String non accetta "array"');
                break;
            case 'resource':
                throw new TypeUtilitiesException('String non accetta "resource"');
                break;
            case 'NULL':
                throw new TypeUtilitiesException('String non accetta "NULL"');
                break;
            case 'object':
                throw new TypeUtilitiesException('String non accetta "object"');
                break;
            case 'unknown type':
                throw new \Exception('String non accetta "unknown type"');
                break;
            case 'boolean':
                return  $string ? 'true' : 'false';
                break;
            default:
                return (string) $string;
                break;
        }
    }

    /**
     * Checks if the letters in the string are all lower case. Checks only the letters
     *
     * @return bool
     */
    public function isLower() {

        return $this->isUpperLower('upper');
    }

    /**
     * Checks if the letters in the string are all upper case. Checks only the letters
     *
     * @return bool
     */
    public function isUpper() {

        return $this->isUpperLower('lower');
    }

    private function isUpperLower($case)
    {
        $case = '_'.$case.'Alphabet';

        for($i = 0; $i < $this->_strLength; $i++)
            foreach($this->$case as $letter)
                if($this->_str[$i] == $letter)
                    return false;

        return true;
    }

    /**
     * Returns:
     * true if the pattern matches given subject
     * false if it does not
     * 0 if an error occurred.
     *
     * @param $pattern
     * @return int
     */
    public function contains($pattern)
    {
        $pattern = "/$pattern/";

        $res = preg_match($pattern, $this->_str);

        switch ($res) {

            case 1:
                return true;
                break;
            case 0:
                return false;
                break;
            case FALSE:
                return 0;
                break;
            default:
                return 0;
                break;
        }
    }

    private function strLength()
    {
        $len = 0;

        while (@$this->_str[$len] != '')
            $len++;

        return $len;
    }

    public function length()
    {
        return $this->_strLength;
    }

    /**
     * If $return = true, the lowercase string will be returned, but internally it remains original
     * If $return = false, the method will return nothing and the lowercase string will be internally setted
     *
     * @param bool $return
     * @return string
     */
    public function lower($return = false)
    {
        if (!$return)
            $this->_str = strtolower($this->_str);
        else
            return strtolower($this->_str);
    }

    /**
     * If $return = true, the replaced string will be returned, but internally it remains original
     * If $return = false, the method will return nothing and the replaced string will be internally setted
     * @param $search
     * @param $replace
     * @param bool $return
     * @return mixed
     */
    public function replace($search, $replace, $return = false)
    {
        $str = str_replace($search, $replace, $this->_str);

        if (!$return)
            $this->_str = $str;
        else
            return $str;
    }

    public function __toString()
    {
        return $this->_str;
    }

    /**
     * If $return = true, the md5 string will be returned, but internally it remains original
     * If $return = false, the method will return nothing and the md5 string will be internally setted
     *
     * @param bool $return
     * @return string
     */
    public function md5($return = false)
    {
        if (!$return)
            $this->_str = md5($this->_str);
        else
            return md5($this->_str);
    }

    /**
     * Given a dotted notation string (key.1.values), this method search in $arr the path indicated by the string and return it.
     * If $force is true, the path elements will be created if not exists in array
     *
     *
     * @param array $arr
     * @param bool $force
     * @return array
     * @throws TypeUtilitiesException
     */
    public function pathToArray(array $arr, $force = false)
    {
        $keys           = explode('.', $this->_str);

        while (NULL != ($key = array_shift($keys))) {

            if (is_numeric($key))
                $key = intval($key);

            if ( ! isset($arr[$key])) {

                if ($force) {

                    if ( ! isset($arr[$key])) {
                        $arr[$key] = [];
                    }

                } else {

                    throw new TypeUtilitiesException("Path {$this} doesn't exist");
                }
            }

            $arr = &$arr[$key];
        }

        return $arr;
    }

    /**
     * Given a dotted notation string (key.1.values), this method search in $arr the path indicated by the string, set the $value, and return it.
     *
     * @param array $arr
     * @param $value
     * @return array
     */
    public function insertInArrayByPath(array $arr, $value)
    {
        $startPointer   = &$arr;
        $keys           = explode('.', $this->_str);

        while (NULL != ($key = array_shift($keys))) {

            if (is_numeric($key))
                $key = intval($key);

            if (!isset($arr[$key]))
                $arr[$key] = [];

            $arr = &$arr[$key];
        }

        $arr = $value;

        return $startPointer;
    }
}
