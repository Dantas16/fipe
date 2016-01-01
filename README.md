# Fipe
APP PHP para trabalhar com webservices que disponibilizam dados da tabela FIPE

A finalidade dessa aplicação é servir como alternativa para requisições em JSON bloqueadas pelo browser ou alguma outra limitação entre servidores diferentes

Trabalha as requisições para as APIs que trabalham direto com a tabela FIPE

## Instruções
Considere:
  MARCA = Marca do veículo, moto ou caminhão, pode ser (fiat, ford, chevrolet, cherry, etc.)
  MODELO = veiculo (pálio, corolla, uno, etc.), moto (cg150, biz100, xt600, etc.), caminhão (mb2324, etc.)
  ANO = Os anos disponíveis para a marca e modelo selecionado

URLS:
Para obter somente as marcas disponíveis
  Ex: dominio/diretoriodoapp/TYPE = [veiculo, moto ou truck]
  Exemplo de como obter a lista de marcas de veiculos:
    http://seudominio.com.br/fipe/veiculo ou http://seudominio.com.br/fipe/veiculo/action/marca
    Se marcas de motos é só substituir 'veiculo' por 'moto' ou 'truck' no caso de caminhões
 
Modelos de uma marca específica:
   Para essa ação, o sistema precisa saber qual a ID da marca selecionada
   'action' é uma palavra reservada que identifica a ação solicitada e pode assumir os valores:[marca, modelo, ano]
   Ex: dominio/TYPE/action/modelo/MARCAID/
   Exemplos:
       1. Obtém os modelos de veículos disponíveis para a marca com ID = 21
           http://seudominio.com.br/fipe/veiculo/action/modelo/21
       2. Obtém os modelos de caminhões disponíveis para a marca com ID = 32
           http://seudominio.com.br/fipe/truck/action/modelo/32
 
Anos disponíveis para um modelo específico:
  Nesse caso o sistema precisa saber qual a ID da marca e do modelo selecionado, veja os exemplos abaixo.
    Ex: dominio/TYPE/action/ano/MARCAID/MODELOID
    Exemplos:
        1. Obtém os anos disponíveis para um modelo específico de moto, onde a MARCAID = 23 e MODELOID = 332
            http://seudominio.com.br/fipe/moto/action/ano/53/332
        2. Obtém os anos disponíveis para um modelo específico de veiculo, onde a MARCAID = 10 e MODELOID = 44
            http://seudominio.com.br/fipe/veiculo/action/ano/10/44

Valor de acordo com a marca, modelo e ano selecionado:
  É necessário informar a MARCAID, MODELOID, ANOID, conforme os exemplos abaixo.
    Ex: dominio/TYPE/action/valor/MARCAID/MODELOID/ANOID
    Exemplos:
      1. Obtém o valor para um veículo com MARCAID = 15, MODELOID = 16, ANOID = 5


Gutemberg Dantas
www.5gti.com.br
gti5tecnologias@outlook.com
contato@5gti.com.br
