<?php

namespace Models;
use ErrorException;

interface HttpRequestService {
    public static function sendRequest($UTILS, $uri, $params);
}

class HttpGetRequest implements HttpRequestService {
    public static function sendRequest($UTILS, $uri, $params) {
        return $UTILS::sendHTTPGET($uri, $params);
    }
}

class HttpPostRequest implements HttpRequestService {
    public static function sendRequest($UTILS, $uri, $params) {
        return $UTILS::sendHTTPPOST($uri, $params);
    }
}

class HttpRequestFactory {
    public static function createRequest($UTILS, $URI, $verb, $params) {
        switch ($verb) {
            case 'GET':
                // return new HttpGetRequest($UTILS, $URI, $params);
                return HttpGetRequest::sendRequest($UTILS, $URI, $params);
            case 'POST':
                return HttpPostRequest::sendRequest($UTILS, $URI, $params);
            case 'PATCH':
            case 'PUT':
            case 'DELETE':
            default:
                throw new ErrorException("Método {$verb} não implementado ainda");
        }
    }
}