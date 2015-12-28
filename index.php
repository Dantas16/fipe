<?php
/**
 * Alternativa para requisições em JSON bloqueadas pelo browser ou alguma outra limitação entre servidores diferentes
 *
 * Trabalha as requisições para as APIs que trabalham com a tabela FIPE
 *
 * Considere:
 *  MARCA = Marca do veículo, moto ou caminhão, pode ser (fiat, ford, chevrolet, cherry, etc.)
 *  MODELO = veiculo (pálio, corolla, uno, etc.), moto (cg150, biz100, xt600, etc.), caminhão (mb2324, etc.)
 *  ANO = Os anos disponíveis para a marca e modelo selecionado
 *
 * URLS:
 * Para obter somente as marcas disponíveis
 *  Ex: dominio/diretoriodoapp/TYPE = [veiculo, moto ou truck]
 *  Exemplo de como obter a lista de marcas de veiculos:
 *      http://seudominio.com.br/fipe/veiculo ou http://seudominio.com.br/fipe/veiculo/action/marca
 *  Se marcas de motos é só substituir 'veiculo' por 'moto' ou 'truck' no caso de caminhões
 *
 * Modelos de uma marca específica:
 *  Para essa ação, o sistema precisa saber qual a ID da marca selecionada
 *  'action' é uma palavra reservada que identifica a ação solicitada e pode assumir os valores:[marca, modelo, ano]
 *  Ex: dominio/TYPE/action/modelo/MARCAID/
 *  Exemplos:
 *      1. Obtém os modelos de veículos disponíveis para a marca com ID = 21
 *          http://seudominio.com.br/fipe/veiculo/action/modelo/21
 *      2. Obtém os modelos de caminhões disponíveis para a marca com ID = 32
 *          http://seudominio.com.br/fipe/truck/action/modelo/32
 *
 * Anos disponíveis para um modelo específico:
 *  Nesse caso o sistema precisa saber qual a ID da marca e do modelo selecionado, veja os exemplos abaixo.
 *  Ex: dominio/TYPE/action/ano/MARCAID/MODELOID
 *  Exemplos:
 *      1. Obtém os anos disponíveis para um modelo específico de moto, onde a MARCAID = 23 e MODELOID = 332
 *          http://seudominio.com.br/fipe/moto/action/ano/53/332
 *      2. Obtém os anos disponíveis para um modelo específico de veiculo, onde a MARCAID = 10 e MODELOID = 44
 *          http://seudominio.com.br/fipe/veiculo/action/ano/10/44
 *
 * Valor de acordo com a marca, modelo e ano selecionado:
 *  É necessário informar a MARCAID, MODELOID, ANOID, conforme os exemplos abaixo.
 *  Ex: dominio/TYPE/action/valor/MARCAID/MODELOID/ANOID
 *  Exemplos:
 *      1. Obtém o valor para um veículo com MARCAID = 15, MODELOID = 16, ANOID = 5
 *
 *
 * @author Gutemberg Dantas <gti5tecnologias@outlook.com>
 * www.5gti.com.br <contato@5gti.com.br>
*/

//Valida o method para aceitar apenas GET
$method = filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_STRING);
if ($method !== 'GET') {
    http_response_code(401); //Bad request
    echo "Somente method GET"; die;
}

//URL, as requisições são identificadas na url
$url = (isset($_GET['url'])) ? filter_var(strip_tags($_GET['url']), FILTER_SANITIZE_STRING) : 'veiculo';
    $url = rtrim($url, '/');
    $url = explode('/', $url);

$type = strtolower(array_shift($url));

//Factory, retorna uma instância da classe que irá trabalhar os dados de acordo com o tipo
include_once('php/includes/FactoryType.php');
$factory = new FactoryType();

$app = $factory->setType($type); //Aplicação de acordo com o tipo

if ($app === false) {
    http_response_code(503); //Service unavailable
    echo "Servico indisponivel"; die;
}

//Configurações e funções adicionais
include_once('php/Configs.php');

//Tenta obter os dados de acordo com a requisição
$dataAPI = $app->getFipe($getParam($url));

if ($dataAPI === false) {
    http_response_code(503);
    echo "APIs indisponiveis"; die;
}

echo $dataAPI;