<?php

namespace Models;

use ErrorException;

class Auth
{
    public $username = '';
    public $access_key = '';
    public $uri = '';
    private $sessionName = '';
    public $serverTime = '';
    public $expireTime = '';
    public $userId = '';

    public function __construct($uri, $username, $access_key)
    {
        $this->username = $username;
        // $this->access_key = urlencode($access_key);
        $this->access_key = $access_key;
        $this->uri = $this->formatURI($uri);
    }

    /**
     * Retorna SessionName da autenticação
     * @return string SessionName
     */
    public function doLogin()
    {
        global $UTILS;
        $tokenString = $this->getChallenge()['token'];
        $access_key = md5($tokenString . $this->access_key);
        // $this->access_key = md5("{$tokenString}{$this->access_key}");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://modelo.nsgl01.crm.netsac.com.br/webservice.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "operation=login&username=raphael_budin&accessKey={$access_key}",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $response = curl_exec($curl);
        $UTILS::checkErrorResponse($response, $curl);
        curl_close($curl);
        $response = json_decode($response, true);
        $response = $response['result'];
        $this->userId = $response['userId'];
        $this->sessionName = $response['sessionName'];
        echo "SessionName: " . $this->sessionName;
        return $this->sessionName;
    }

    private function getChallenge()
    {
        global $UTILS;
        $url = "{$this->uri}/webservice.php?operation=getchallenge&username=raphael_budin";
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        // $response = json_decode(curl_exec($curl), true);
        $response = curl_exec($curl);
        $UTILS::checkErrorResponse($response, $curl);
        curl_close($curl);

        $response = json_decode($response, true);
        $response = $response['result'];
        $this->expireTime = $response['expireTime'];
        $this->serverTime = $response['serverTime'];
        curl_close($curl);
        return $response;
    }

    public static function formatURI($uri)
    {
        if (!preg_match('/^https?:\/\//i', $uri)) {
            $uri = sprintf('http://%s', $uri);
        }
        if (strripos($uri, '/') !== strlen($uri) - 1) {
            $uri .= '/';
        }
        return $uri;
    }
}
