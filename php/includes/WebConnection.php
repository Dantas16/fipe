<?php
/**
 * Realiza conexões na web
 * Make connection on web
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com>
 */
abstract class WebConnection
{

    /**
     * Realiza a conexão com o webservice da API
     * @access public
     * @param String $url
     * @return Boolean false if fails
    */
    protected function getResource($url)
    {
        $rsc = curl_init($url);

        curl_setopt($rsc, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($rsc, CURLOPT_FOLLOWLOCATION, true);

        $result = curl_exec($rsc);

        $http_code = curl_getinfo($rsc, CURLINFO_HTTP_CODE);

        curl_close($rsc);

        if ($http_code !== 200){ return false; }

        $result = mb_convert_encoding($result, 'UTF-8');

        return $result;
    }

}