<?php
ini_set('opcache.enable', '0');

$ip = "http://192.168.2.187:9980";
$mqttip = "192.168.2.187";

    Function AntiCrack($strings){
        if (iconv_strlen($strings,'UTF-8') > 0) {
            $strings = str_replace("'","",$strings);
            $strings = str_replace('"','',$strings);
            $strings = str_replace("$","",$strings);
            $strings = str_replace("<","",$strings);
            $strings = str_replace(">","",$strings);
            $strings = str_replace(";","",$strings);
            $strings = str_replace("-","",$strings);
            $strings = str_replace(",","",$strings);
        }
        return $strings;
    }

    Function AntiCrack2($strings){
        if (iconv_strlen($strings,'UTF-8') > 0) {
            $strings = str_replace("'","",$strings);
            $strings = str_replace('"','',$strings);
            $strings = str_replace("$","",$strings);
            $strings = str_replace("<","",$strings);
            $strings = str_replace(">","",$strings);
            $strings = str_replace(";","",$strings);
            $strings = str_replace(",","",$strings);
        }
        return $strings;
    }
    
    Function get($url) {
        $url = $GLOBALS['ip'].$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    Function getParam($url, $param) { 
        $url = $GLOBALS['ip'].$url.'/'.$param;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }

    Function getArray($url, $params=array()) { 
        $url = $GLOBALS['ip'].$url.'?'.http_build_query($params, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
?>
