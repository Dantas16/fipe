# Fipe
PHP APP que trabalha com webservices que disponibilizam dados da tabela FIPE

Essa aplicação tem como finalidade disponibilizar dados da tabela FIPE obtidos através de webservices e servir como alternativa a limitações entre servidores no uso de javascript e json.

A aplicação foi desenvolvida visando, também, obter dados de diversos webservices e fornecer um serviço de cache para melhor desempenho.

## Instruções

**Considere:**<br>
*MARCA* = Marca do veículo, moto ou caminhão;<br>
*MODELO* = veiculo, moto ou caminhão;<br>
*ANO* = Os anos disponíveis para um modelo específico.<br>
<br>
**URL:**<br>
*Para obter somente as marcas disponíveis:*<br>
  Ex: dominio/diretoriodoapp/TYPE = [veiculo, moto ou truck]<br>
  Exemplo de como obter a lista de marcas de veiculos:<br>
    http://seudominio.com.br/fipe/veiculo ou http://seudominio.com.br/fipe/veiculo/action/marca<br>
    Se marcas de motos é só substituir 'veiculo' por 'moto' ou 'truck' no caso de caminhões<br>
 <br>
*Modelos de uma marca específica:*<br>
   Para essa ação, o sistema precisa saber qual a ID da marca selecionada<br>
   'action' é uma palavra reservada que identifica a ação solicitada e pode assumir os valores:[marca, modelo, ano]<br>
   Ex: dominio/TYPE/action/modelo/MARCAID/<br>
   Exemplos:<br>
       1. Obtém os modelos de veículos disponíveis para a marca com ID = 21<br>
           http://seudominio.com.br/fipe/veiculo/action/modelo/21<br>
       2. Obtém os modelos de caminhões disponíveis para a marca com ID = 32<br>
           http://seudominio.com.br/fipe/truck/action/modelo/32<br>
 <br>
*Anos disponíveis para um modelo específico:*<br>
  Nesse caso o sistema precisa saber qual a ID da marca e do modelo selecionado, veja os exemplos abaixo.<br>
    Ex: dominio/TYPE/action/ano/MARCAID/MODELOID<br>
    Exemplos:<br>
        1. Obtém os anos disponíveis para um modelo específico de moto, onde a MARCAID = 23 e MODELOID = 332<br>
          http://seudominio.com.br/fipe/moto/action/ano/53/332<br>
        2. Obtém os anos disponíveis para um modelo específico de veiculo, onde a MARCAID = 10 e MODELOID = 44<br>
          http://seudominio.com.br/fipe/veiculo/action/ano/10/44<br>
<br>
*Valor de acordo com a marca, modelo e ano selecionado:*<br>
  É necessário informar a MARCAID, MODELOID, ANOID, conforme os exemplos abaixo.<br>
    Ex: dominio/TYPE/action/valor/MARCAID/MODELOID/ANOID<br>
    Exemplos:<br>
      1. Obtém o valor para um veículo com MARCAID = 15, MODELOID = 16, ANOID = 5<br>
<br><br>
Gutemberg Dantas<br>
**www.5gti.com.br**<br>
*gti5tecnologias@outlook.com*<br>
*contato@5gti.com.br*<br>
