<?php

class Utils_Locale
{

    public static function getLanguage ()
    {
        
        $locale = new Zend_Locale();
        $language = array_keys($locale->getDefault(Zend_Locale::BROWSER, true));
        
        return $language[0];
    
    }

    public static function getIpAddress ()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        
        $http_client_ip = $request->getServer('HTTP_CLIENT_IP');
        $http_x_forwarded_for = $request->getServer('HTTP_X_FORWARDED_FOR');
        $remote_addr = $request->getServer('REMOTE_ADDR');
        
        // zend framework friendly
        if (! empty($http_client_ip)) {
            $ip = $http_client_ip;
        } elseif (! empty($http_x_forwarded_for)) {
            $ip = $http_x_forwarded_for;
        } else {
            $ip = $remote_addr;
        }
        
        return $ip;
    
    }

    public static function isLikePostcode ($query)
    {
        $query = str_replace(' ', '', $query);
        $query = str_replace("\t", '', $query);
        $query = strtoupper($query);
        
        if (strlen($query) > 7) {
            return false;
        }
        
        if (preg_match(
        "((GIR0AA)|(TDCU1ZZ)|(ASCN1ZZ)|(BIQQ1ZZ)|(BBND1ZZ)" .
         "|(FIQQ1ZZ)|(PCRN1ZZ)|(STHL1ZZ)|(SIQQ1ZZ)|(TKCA1ZZ)|(SANTA1)" . "|([A-Z][0-9][0-9][A-Z][A-Z])" .
         "|([A-Z][A-Z][0-9][0-9][A-Z][A-Z])" . "|([A-Z][A-Z][0-9][0-9][0-9][A-Z][A-Z])" .
         "|([A-Z][A-Z][0-9][A-Z][0-9][A-Z][A-Z]))", $query)) {
            return true;
        } else {
            return false;
        }
    }

    public static function formatPostcode ($query)
    {
        $query = str_replace(' ', '', $query);
        $query = str_replace("\t", '', $query);
        $query = strtoupper($query);
        $queryArr = array();
        for ($i = 0; $i < strlen($query); $i ++) {
            $queryArr[] = $query[$i];
        }
        $part2 = array_slice($queryArr, - 3, 3);
        $part1 = array_slice($queryArr, 0, - 3);
        $query = implode('', $part1) . ' ' . implode('', $part2);
        return $query;
    }

    /**
     *
     *
     *
     *
     * Get Location IP in User
     *
     * @param $ip unknown_type           
     * @return string boolean
     */
    public static function getLocationByIp ($ip = false)
    {
        
        $location = new Zend_Session_Namespace('location');
        $city = $location->city;
        
        if ($city != null) {
            if ($city > 0) {
                return $city;
            } else {
                return false;
            }
        }
        if ($ip == false) {
            $ip = self::getIpAddress();
        }
        
        $ipLocator = new API_Ip2Location();
        $city = $ipLocator->getCity($ip);
        
        // IP location service error
        if ($ipLocator->getError()) {
            $location->city = - 1;
            return false;
        }
        
        // no city name returned
        if (! isset($city['cityName'])) {
            $location->city = - 2;
            return false;
        }
        
        // city name too short
        if (strlen($city['cityName']) < 3) {
            $location->city = - 3;
            return false;
        }
        
        // city name ok
        $location->city = $city['cityName'];
        
        return $city['cityName'];
    }

}

?>
