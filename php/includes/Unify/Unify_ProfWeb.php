<?php
/**
 * Unifica os dados dentro do padrão único para retorno de dados da aplicação
 *
 * Trabalha com os dados obtidos especificamente da API
 * http://www.profissionaisdaweb.com.br/webservice-api-com-os-precos-de-automoveis-da-fipe-43.jsp
 *
 * @author Gutemberg Dantas, <gti5tecnologias@outlook.com>
 */

require_once("Unify_Interface.php");

class Unify_ProfWeb implements Unify_Interface
{
    //Estrutura unificada
    private $unified = Array();

    /**
     * Chamadas a métodos inexistentes
    */
    public function __call($method, $args)
    {
        return false;
    }

    /**
     * Chamadas a propriedades inexistentes
    */
    public function __get($prop)
    {
        return false;
    }

    /**
     * Retorna o resultado final da unificação
     * @return Array
    */
    public function getUnifiedData()
    {
        return $this->unified;
    }

    /**
     * Trabalha a unificação da lista de Marcas
     * @access public
     * @param String $marca
     * @return Boolean or Array
     * */
    public function unifyMarca($marca)
    {
        $marca = simplexml_load_string($marca);

        if (!is_object($marca) || !isset($marca->marca)) { return false; }

        foreach ($marca->marca as $obj):
            array_push($this->unified, array('marca' => (string)$obj->marca_nome, 'id' => (int)$obj->marca_id));
        endforeach;

        return $this->unified;
    }

    /**
     * Trabalha a unificação da lista de modelos
     * @access public
     * @param String $modelo
     * @return Boolean false or Array
    */
    public function unifyModelo($modelo)
    {
        $modelo = simplexml_load_string($modelo);

        if (!is_object($modelo) || !isset($modelo->modelo)) { return false; }

        foreach ($modelo->modelo as $obj):
            array_push($this->unified, array('modelo' => (string)$obj->modelo_nome, 'id' => (string)$obj->modelo_id));
        endforeach;

        return $this->unified;
    }

    /**
     * Trabalha a unificação da lista de anos de um determinado modelo previamente selecionado
     * @access public
     * @param String $ano
     * @return Boolean false or Array
     * */
    public function unifyAno($ano)
    {
        $ano = simplexml_load_string($ano);

        if (!is_object($ano) || !isset($ano->preco)) { return false; }

        foreach ($ano->preco as $obj):
            $id = str_replace(" ", "_", $obj->preco_nome);
            array_push($this->unified, array('modelo' => (string)$obj->preco_nome, 'id' => $id));
        endforeach;

        return $this->unified;
    }

    /**
     * Trabalha a unificação de valores do veículo
     * @access public
     * @param String $valor
     * @return Boolean false or Array
     * */
    public function unifyValor($valor)
    {
        $valor = simplexml_load_string($valor);

        if (!is_object($valor) || !isset($valor->preco)) { return false; }

        foreach ($valor->preco as $obj):
            $preco = number_format((float)$obj->preco_preco, 2, ',', '.');
            array_push($this->unified, array('preco' => 'R$ '.$preco, 'id' => (string)$obj->preco_preco));
        endforeach;

        return $this->unified;
    }

}