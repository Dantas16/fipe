<?php
/**
 * Interface para as classes de unificação especificas de cada API
 */
interface Unify_Interface
{
    public function __call($method, $args);

    public function __get($prop);

    public function unifyMarca($marca);

    public function unifyModelo($modelo);

    public function unifyAno($ano);

    public function unifyValor($valor);
}