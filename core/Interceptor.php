<?php
class Interceptor {
    
    /**
     * 拒绝链接
     * 
     */
    public static function rejectAction () {
        header('HTTP/1.1 403 Forbidden');
        exit;
    }
    
   /**
    * notFound
    *
    */
    public static function notFoundAction () {
        header('HTTP/1.1 404 Not Found');
        exit;
    }
}