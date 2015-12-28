<?php
/**
 * As APIs utilizadas pela aplicação, fornecem dados com formatos e propriedades diferentes,
 * isso torna inviável o uso de ambos ao mesmo tempo, seriam neccessárias programações e scripts diferentes para
 * trabalhar com cada API.
 * Pensando em resolver isso, essa classe de unificação, obtém os dados principais, obtém a respectiva biblioteca
 * que transforma em um único formato com propriedades iguais para trabalhar com dados de ambas as APIs, possibilitando
 * que um único script javascript, por exemplo, trabalhe com dados fornecidos por várias APIs.
 *
 * Cada API adicionada deve ter sua biblioteca de unificação de acordo com os dados fornecidos
 *
 * APIs:
 * http://fipeapi.appspot.com/
 * http://www.profissionaisdaweb.com.br/webservice-api-com-os-precos-de-automoveis-da-fipe-43.jsp
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com>
 */
class Unify
{

    /**
     * Cria uma instância da classe de unificação especifica para trabalhar com a API e passa os dados
     * @access public
     * @param String $api
     * @param String $acao [marca, modelo, ano]
     * @param String $dados
     * @return Boolean false or array
     **/
    public static function unifyApp($api, $acao, $dados)
    {
        if ($dados === false || $dados === "") { return false; }

        $class = 'Unify_'.$api;

        $lib = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'Unify' . DIRECTORY_SEPARATOR . $class . '.php';

        if (!file_exists($lib)) { return false; }

        require_once($lib);

        if (!class_exists($class)) { return false; }

        $appUnify = new $class();

        $method = "unify".ucfirst($acao);

        return $appUnify->$method($dados);
    }

}