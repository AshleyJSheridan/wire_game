<?php

/**
* Generates a random hash
*/
class Utils_RandomHash
{

    /**
     * Insecure hash generator
     *
     * @param $seed int           
     * @param $prefix string           
     * @param $suffix string           
     * @return string
     */
    static function generateWithSeed ($seed, $prefix = '', $suffix = '')
    {
        return sha1($prefix . srand($seed) . $suffix);
    }

    static function generate ()
    {
        return sha1(uniqid('tiuti867t87687686', true) . 'a unique suffix');
    }

    static function validate ($hash)
    {
        return ! empty($hash) && preg_match('/^[A-Za-z0-9]{40}$/', $hash);
    }

}

