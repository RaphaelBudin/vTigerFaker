<?php
interface HttpRequestService {
    public function sendRequest($uri, $params);
}

class HttpGetRequest implements HttpRequestService {
    public function sendRequest($uri, $params) {
        global $UTILS;
        return $UTILS::sendHTTPGET($uri, $params);
    }
}

class HttpPostRequest implements HttpRequestService {
    public function sendRequest($uri, $params) {
        global $UTILS;
        return $UTILS::sendHTTPPOST($uri, $params);
    }
}

class HttpRequestFactory {
    public static function createRequest($verb) {
        switch ($verb) {
            case 'GET':
                return new HttpGetRequest();
            case 'POST':
                return new HttpPostRequest();
            case 'PATCH':
            case 'PUT':
            case 'DELETE':
            default:
                throw new ErrorException("Método {$verb} não implementado ainda");
        }
    }
}