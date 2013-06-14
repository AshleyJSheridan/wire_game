<?php

class Utils_String
{

    private static function c ($str, $checkCase)
    {
        return $checkCase ? $str : strtolower($str);
    }

    public static function hasSuffix ($suffix, $string, $checkCase = true)
    {
        $offset = - 1 * strlen($suffix);
        return (self::c(substr($string, $offset), $checkCase) === self::c($suffix, $checkCase));
    }

    public static function removeSuffix ($suffix, $string, $checkCase = true)
    {
        if (self::hasSuffix($suffix, $string, $checkCase)) {
            return substr($string, 0, - 1 * strlen($suffix));
        } else {
            return $string;
        }
    }

    public static function hasPrefix ($prefix, $string, $checkCase = true)
    {
        $len = strlen($prefix);
        return (self::c(substr($string, 0, $len), $checkCase) === self::c($prefix, $checkCase));
    }

    public static function removePrefix ($prefix, $string, $checkCase = true)
    {
        if (self::hasPrefix($prefix, $string, $checkCase)) {
            return substr($string, strlen($prefix));
        } else {
            return $string;
        }
    }

    public static function removePrefixes (array $prefixes, $string, $checkCase = true)
    {
        $tmp = null;
        foreach ($prefixes as $prefix) {
            $tmp = self::removePrefix($prefix, $string, $checkCase);
            if (strlen($tmp) != strlen($string)) {
                return $tmp;
            }
        }
        return $string;
    }

    public static function Length ($str)
    {
        if (strlen($str) > 200) {
            $str = strip_tags($str);
            for ($i = 200; $i > 0; $i --) {
                if (preg_match('/\s/', $str[$i])) {
                    break;
                }
            }
            $snip = substr($str, 0, $i) . '...';
            
            return $snip;
        } else {
            return $str;
        }
    }

}
?>
