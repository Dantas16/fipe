<?php
/**
 * Configurações e funções úteis
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com>
 * www.5gti.com.br <contato@5gti.com.br>
 */

/**
 * Valida e identifica os parâmetros, retorna um Array Associativo
 * @param Array $url
 * @return Array $param
*/
$getParam = function($url) {
    $param = array(
        'action' => null,
        'marca' => null,
        'modelo' => null,
        'ano' => null,
    );

    if (count($url) < 2) { return $param; }

    $param['action'] = (array_shift($url)==='action') ? array_shift($url) : null;

    $param['marca'] = array_shift($url);
    $param['modelo'] = array_shift($url);
    $param['ano'] = array_shift($url);

    return $param;
};