<?php
class BaseController {
	
    /**
     * notFound
     * 
     */
    public function notFound() {
        header('HTTP/1.1 404 Not Found');
        exit;
    }
}
