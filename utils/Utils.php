<?php

class Utils
{
    public static function checkErrorResponse($response, $curl = null)
    {

        if ($curl) {
            if (curl_errno($curl)) {
                throw new Exception("Curl error: " . curl_error($curl));
            }
        }

        if (!is_array($response)) {
            $response = json_decode($response, true);
        }

        if (isset($response['success']) && true === (bool) $response['success'])
            return false;

        if (isset($response['error'])) {
            $error = $response['error'];
            #Erros que não precisam bloquear
            if ($error['message'] == 'Duplicado(s) apagados!') {
                echo "Duplicado encontrado, pulando...";
                return false;
            }
            throw new Exception("Código erro: {$error['code']} -> Mensagem erro: {$error['message']}");
        }

        var_dump('Resposta com erro: ', $response);
        throw new Exception('Erro desconhecido');
    }

    /**
     * Manda requisição GET, já realizando os tratamentos dos parâmetros
     * Espera o urlencode da fonte, não faz por aqui
     * @param mixed $URI
     * @param mixed $params
     * @param mixed $endpoint
     * @return mixed
     */
    public static function sendHTTPGET($URI, $params, $endpoint = '/webservice.php?')
    {
        $params = implode('&', $params);
        $url = "{$URI}{$endpoint}{$params}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        self::checkErrorResponse($response, $curl);
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * Manda requisição POST, já realizando os tratamentos dos parâmetros
     * Espera o urlencode da fonte, não faz por aqui
     * @param mixed $URI
     * @param mixed $params
     * @param mixed $endpoint
     * @return mixed
     */
    public static function sendHTTPPOST($URI, $params, $endpoint = '/webservice.php')
    {
        $params = implode('&', $params);
        $url = "{$URI}{$endpoint}";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded'
            ],
        ));
        $response = curl_exec($curl);
        self::checkErrorResponse($response, $curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}
