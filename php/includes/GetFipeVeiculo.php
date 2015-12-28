<?php
/**
 *  Obtém dados de APIs que trabalham com a tabela FIPE relativa apenas a CARROS
 *
 * http://fipeapi.appspot.com/
 * http://www.profissionaisdaweb.com.br/webservice-api-com-os-precos-de-automoveis-da-fipe-43.jsp
 *
 * Estende a classe 'WebConnection' que realiza conexões curl e streams
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com>
 */
require_once('WebConnection.php');
require_once('Cache.php');
require_once('Unify.php');

class GetFipeVeiculo extends WebConnection
{
    /** Diretório principal de Cache */
    const CACHE_MAIN = "cache";
    /** Diretório para cache de carros */
    const CACHE_DIR = "carros";

    /* Configurações sobre as API que fornecem os dados */
    private $api = array();

    /* marca, modelo ou ano */
    private $action = null;

    private $marca = null;
    private $modelo = null;
    private $ano = null;

    /* Objeto da classe Cache */
    private $cache;

    /**
     * Obtém a requisição via arguments, atribui os valores às propriedades e inicialia o método correto
     * @access public
     * @param String $request parâmetros solicitados
     * @return Json or Boolean false
     **/
    public function getFipe($request)
    {
        $this->getConfigsApi();

        $this->cache = new Cache(self::CACHE_MAIN);

        $this->action = ($request['action'] === null) ? 'marca' : $request['action'];
        $this->marca = $request['marca'];
        $this->modelo = $request['modelo'];
        $this->ano = $request['ano'];

        switch ($this->action):
            case 'marca':
                return $this->getBrand();
            break;
            case 'modelo':
                return $this->getModelo();
            break;
            case 'ano':
                return $this->getAno();
            break;
            case 'valor':
                return $this->getValor();
            break;
            default:
                return false;
            break;
        endswitch;

    }

    /**
     * Obtém o arquivo de configurações das APIS
     * @access private
     * @return Array or false
    */
    private function getConfigsApi()
    {
        $fileConf = realpath(__DIR__) . DIRECTORY_SEPARATOR . 'Configs' . DIRECTORY_SEPARATOR . 'api.json';
        if (!file_exists($fileConf)) { return false; }

        $configs = file_get_contents($fileConf);

        $api = json_decode($configs);

        if ($api === null) { return false; }

        $this->api = $api;
    }

    /**
     * Obtém uma lista com todas as marcas disponíveis
     * @access private
     * @return Boolean false or Json
    */
    private function getBrand()
    {
        $this->cache->alterFolder(self::CACHE_DIR.'/marcas');
        $this->cache->setFile('marca', 'marcas');

        //Se arquivo de cache pronto, retorna
        if ($this->cache->isReady()) {
            return $this->cache->readCache();
        }

        //Busca os dados na api que estiver disponível, iniciando pela primeira
        foreach ($this->api as $api) {
            $url = $api->main_url . $api->marcas_url;

            $res = parent::getResource($url);

            if ($res !== false) {
                $res = Unify::unifyApp($api->app, 'marca', $res);
                break;
            }
        }

        //Se falha (Nenhuma API disponível) retorna
        if ($res === false) { return false; }

        $res = json_encode($res);

        //Grava no cache
        $this->cache->writeCache($res);

        return $res;
    }

    /**
     * Limpa o direório de cache das Marcas "Força o sistema a buscar direto no webservice"
     * @access private
     * @return Void
    */
    private function delCacheBrand()
    {
        $this->cache->setStandardFolder(self::CACHE_MAIN);
        $this->cache->alterFolder(self::CACHE_DIR.'/marcas');
        $this->cache->clearCache();
    }

    /**
     * Obtém uma lista de modelos de acordo com uma marca pré-selecionada
     * @access private
     * @return Boolean false or Json
    */
    private function getModelo()
    {
        $this->cache->alterFolder(self::CACHE_DIR.'/modelos');
        $this->cache->setFile('modelo', 'modelos_'.$this->marca);

        if ($this->cache->isReady()) {
            return $this->cache->readCache();
        }

        foreach ($this->api as $api) {
            $url = $api->main_url . str_replace('ID', $this->marca, $api->modelos_url);

            $res = parent::getResource($url);

            if ($res !== false) {
                $res = Unify::unifyApp($api->app, 'modelo', $res);
                break;
            }
        }

        if ($res === false) {
            $this->delCacheBrand();
            return false;
        }

        $res = json_encode($res);

        $this->cache->writeCache($res);

        return $res;
    }

    /**
     * Limpa o diretório de cache dos modelos
     * @access private
     * @return Void
    */
    public function delCacheModelo()
    {
        $this->cache->setStandardFolder(self::CACHE_MAIN);
        $this->cache->alterFolder(self::CACHE_DIR.'/modelos');
        $this->cache->clearCache();
    }

    /**
     * Obtém os anos disponíveis a partir da marca e modelo informado
     * @accesss public
     * @return Boolean false or Json
    */
    private function getAno()
    {
        $this->cache->alterFolder('carros/anos');
        $this->cache->setFile('ano', 'anos_'.$this->marca.'_'.$this->modelo);

        if ($this->cache->isReady()) {
            return $this->cache->readCache();
        }

        foreach ($this->api as $api) {
            $ano_url = str_replace(array('IDMARCA', 'IDMODELO'), array($this->marca, $this->modelo), $api->ano_url);

            $url = $api->main_url . $ano_url;

            $res = parent::getResource($url);

            if ($res !== false) {
                $res = Unify::unifyApp($api->app, 'ano', $res);
                break;
            }
        }

        if ($res === false) {
            $this->delCacheBrand();
            $this->delCacheModelo();
            return false;
        }

        $res = json_encode($res);

        $this->cache->writeCache($res);

        return $res;
    }

    /**
     * Obtém o valor de um veículo a partir da marca, modelo e ano
     * @accesss public
     * @return Boolean false or Json
     */
    private function getValor()
    {
        $this->cache->alterFolder('carros/valor');
        $this->cache->setFile('valor', 'valor_'.$this->marca.'_'.$this->modelo.'_'.$this->ano);

        if ($this->cache->isReady()) {
            return $this->cache->readCache();
        }

        foreach ($this->api as $api) {
            $ident = array('IDMARCA', 'IDMODELO', 'IDANO');
            $subs = array($this->marca, $this->modelo, $this->ano);

            $url = $api->main_url . str_replace($ident, $subs, $api->valor_url);

            $res = parent::getResource($url);

            if ($res !== false) {
                $res = Unify::unifyApp($api->app, 'valor', $res);
                break;
            }
        }

        if ($res === false) {
            $this->delCacheBrand();
            $this->delCacheModelo();
            return false;
        }

        $res = json_encode($res);

        $this->cache->writeCache($res);

        return $res;
    }

}