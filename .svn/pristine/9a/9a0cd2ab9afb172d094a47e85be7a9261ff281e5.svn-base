<?php
/**
 * JSON 兼容中文
 * 
 */
class Json {
    
    /**
     * 将数组转成JSON字符串（可兼容中文）
     * @access public
     * @param array $array
     * @param boolean $zh （是否兼容中文）
     * @param boolean $zh_key （是否处理数组中的中文key）
     * @return string
     * 
     */
    public static function encode ($array, $zh = true, $zh_key = false) {
        if($zh == false){
            return json_encode($array);
        }
        self::_json_zh($array, $zh_key);
        return urldecode(json_encode($array));
    }
    
    /**
     * 将JSON字符串转成数组
     *
     * @param  string  $json
     * @param  boolean $assoc
     * @return array
     */
    public static function decode ($json, $assoc = false) {
        return json_decode($json, $assoc);
    }
    
    private static function _json_zh(&$array,$zh_key=true){
        foreach ($array as $key => $value){
            if(is_array($value)){
                self::_json_zh($array[$key],$zh_key);
            }else {
                if(!is_bool($value) && !is_numeric($value) && !is_null($value))
                    $array[$key] = urlencode(preg_replace('/(\\\\|")/', '\\\$1', $value));
            }
            if($zh_key && is_string($key)){
                $new_key = urlencode($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
    }
}