<?php
/**
 * Unifica os dados dentro do padrão único para retorno de dados da aplicação
 *
 * Trabalha com os dados obtidos especificamente da API
 * http://fipeapi.appspot.com/
 *
 * @author Gutemberg Dantas, <gti5tecnologias@outlook.com>
 */

require_once("Unify_Interface.php");

class Unify_AppSpot implements Unify_Interface
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
     * @return Boolean false or Array
     * */
    public function unifyMarca($marca)
    {
        $marca = json_decode($marca);

        if (count($marca) < 1 || isset($marca->error)) { return false; }

        $this->unified = array_map(
                            function($obj){
                                return array('marca'=>$obj->fipe_name, 'id'=>$obj->id);
                            }, $marca);

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
        $modelo = json_decode($modelo);

        if (count($modelo) < 1 || isset($modelo->error)) { return false; }

        $this->unified = array_map(
            function($obj){
                return array('modelo'=>$obj->fipe_name, 'id'=>$obj->id);
            }, $modelo);

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
        $ano = json_decode($ano);

        if (count($ano) < 1 || isset($ano->error)) { return false; }

        $this->unified = array_map(
            function($obj){
                return array('ano'=>$obj->name, 'id'=>$obj->id);
            }, $ano);

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
        $valor = json_decode($valor);

        if (count($valor) < 1 || isset($valor->error)) { return false; }

        $this->unified = array('valor'=>$valor->preco, 'id'=>$valor->id);

        return $this->unified;
    }

}