# Test Backend Developer Loopa Digital

## O que será avaliado
 - Lógica de programação
 - Estruturação dos componentes desenvolvidos
 - Boas práticas de desenvolvimento (ex: SOLID)
 - Conhecimentos da linguagem
 - Conhecimentos do framework utilizado

## Exigências para desenvolvimento

 - A aplicação deve ser desenvolvida utilizando o framework **PHP Lumen**

## Como submeter o teste

 - Crie um fork deste projeto e submeta um Pull Request quando finalizar.
 - É essencial a criação de um arquivo READ.MD com instruções claras de como instalar e executar o seu projeto.
 
 ## O teste

Elaborar uma API que receba um arquivo e faça a leitura e interpretação dos dados contidos nas linhas do arquivo.

A leitura dos dados deve ser feita de acordo com sua posição e tamanho dentro da linha do arquivo de exemplo, seguindo especificações da tabela abaixo:

| Posição | Tamanho | Descrição                                                      |
| ------- | ------- | ---------                                                      |
|  1      |  3      | ID da venda                                                    |
|  4      |  8      | Data da venda (formato YYYYMMDD)                               |
| 12      | 10      | Valor da venda (os dois últimos números são as casas decimais) |
| 22      |  2      | Número de parcelas da venda                                    |
| 24      | 20      | Nome do cliente                                                |
| 44      |  8      | Cep do comprador                                               |

#### Exemplo de leitura

Ao se ler a linha `12320201012000011132703Comprador 1         06050190`, o resultado deve ser o seguinte:

| 1 > 3 | 4 > 8 | 12 > 10 | 22 > 2 | 24 > 20 | 44 > 8 |
| ----- | ----- | ------- | ------ | ------- | ------ |
| 123 | 20201012 | 0000111327 | 03 | Comprador 1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | 06050190 |

#### Interpretação

Após a leitura, os dados devem ser interpretados e transformados para o formato correto da informação. Exemplo:

| Base | Resultado | Regra |
| ---- | --------- | ----- |
| 123 | 123 | Nenhuma regra |
| 20201012 | 2020-10-12 | A data segue o formato YYYY-MM-DD |
| 0000111327 | 1113.27 | Valor é convertido para float, sendo os dois últmos dígitos as casas decimais
| 03 | 3 | O número de parcelas deve ser um inteiro |
| Comprador 1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; | Comprador 1 | Os últimos espaços removidos |
| 06050190 | (Endereço) | Cep será transformado no endereço conforme próximos passos |

- O CEP deverá ser consultado em uma API externa para trazer as informações do endereço e complementar os dados do cliente
- Pode-se utilizar a API de exemplo [viacep](https://viacep.com.br/ws/06330000/json/) (Ou qualquer outra se desejar)
- A venda deve ser desmembrada em parcelas de acordo com o número de parcelas informado no arquivo
- Cada parcela deve conter o número, valor e data experada seguindo as regras:
- A diferença da soma das parcelas para o valor da venda deve ser incluída na primeira parcela
- A data experada deve ser de 30 dias após a data da venda para cada parcela. Não pode cair em fim de semana.

### Dados de exemplo do conteúdo do arquivo

Arquivo de exemplo *sales.txt* está incluso no repositõrio

```
12320201012000011132703Comprador 1         06050190
32120201013000015637504Comprador 2         06330000
23120201014000026370003Comprador 3         01454000
```

### Exemplo de resposta da API

Usando de exemplo a terceira linha `23120201014000026370003Comprador 3         01454000`, a resposta da API deve seguir o seguinte formato.

```JSON
{
    "sales": [
        {
            "id": 231,
            "date": "2020-10-14",
            "amount": 2638.00,
            "customer": {
                "name": "Comprador 3",
                "address": {
                    "street": "Av Cidade Jardin",
                    "neighborhood": "Jardim Paulistano",
                    "city": "Sâo Paulo",
                    "state": "SP",
                    "postal_code": "01454-000"
                }
            },
            "installments": [
                {
                    "installment": 1,
                    "amount": 879.34,
                    "date": "2020-11-16"
                },
                {
                    "installment": 2,
                    "amount": 879.33,
                    "date": "2020-12-14"
                },
                {
                    "installment": 3,
                    "amount": 879.33,
                    "date": "2021-01-14"
                }
            ]
        }
    ]
}
```
