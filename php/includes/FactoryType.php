<?php
/**
 * Cria uma instância da classe que irá trabalhar os dados de acordo com o tipo solicitado
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com> <contato@5gti.com.br>
 */
class FactoryType
{
    const VEICULO = "GetFipeVeiculo";
    const MOTO = "GetFipeMoto";
    const TRUCK = "GetFipeTruck";

    public function setType($type)
    {

        switch ($type):
            case 'veiculo':
                return $this->initType(self::VEICULO);
            break;
            case 'moto':
                return $this->initType(self::MOTO);
            break;
            case 'truck':
                return $this->initType(self::TRUCK);
            break;
            default:
                return false;
            break;
        endswitch;

    }

    public function initType($class)
    {
        $file = realpath(__DIR__) . DIRECTORY_SEPARATOR . $class . '.php';

        if (!file_exists($file)) { return false; }

        include_once($file);

        $class = ucfirst($class);

        if (!class_exists($class)){ return false; }

        return new $class();
    }

}