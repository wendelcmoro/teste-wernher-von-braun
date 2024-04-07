# teste-wernher-von-braun

Teste técnico Wernher Von Braun

# Conteúdos

1. [Requisitos](#Requisitos)<br>
2. [Descrição breve da implementação do projeto](#Descrição-breve-da-implementação-do-projeto)<br>
3. [Execução da API](#Execução-da-API)<br>
   3.1 [Instalando dependências](##Instalando-dependências)<br>
   3.2 [Configurando ambiente local](##Configurando-ambiente-local)<br>
   3.3 [Instalando chave secreta do app e criando tabelas pelas migrations](##Instalando-chave-secreta-do-app-e-criando-tabelas-pelas-migrations)<br>
   3.4 [Iniciando servidor de API](##Iniciando-servidor-de-API)<br>
   3.4 [Estrutura dos diretórios e detalhes da autenticação](##Estrutura-dos-diretórios-e-detalhes-da-autenticação)<br>
4. [Execução do servidor Telnet fake](#Execução-do-servidor-Telnet-fake)<br>

# 1 Requisitos

-   PHP 8.2.17
-   Composer 2.7.2
-   MariaDB 10.5.23(Foi adicionado um arquivo para montar um container docker para o banco, o .env.example já está mapeado para o uso deste container)

# 2 Descrição breve da implementação do projeto

O projeto foi feito utilizando o framework Laravel, este foi escolhido por conveniência e facilidade para implementar APIs restful, além de ter uma documentação ampla para expandir features.

Para simular uma conexão telnet com um dispositivo pré-cadastrado, foi criado um arquivo no diretório raíz do projeto com nome **telnetConnectionFake.php**, este arquivo simula a conexão esperando na url local **127.0.0.1:!2000**.

É importante ressaltar, que além dos requisitos mostrados na seção anterior, é possível que outros pacotes como php-pdo sejam necessários serem instalados.

# 3 Execução da API

## 3.1 Instalando dependências

Executar o comando no diretório raíz:

```console
composer i
```

## 3.2 Configurando ambiente local

Existe um arquivo **.env.example**, copiar este arquivo para um **.env** e substituir pelas variáveis do seu banco de dados local:

```console
cp .env.example .env
```

Por padrão, este arquivo já vem configurado com uma conexão de exemplo.

## 3.3 Instalando chave secreta do app e criando tabelas pelas migrations

Em seguida, precisamos instalar a chave secreta do nosso app, e criar as tabelas do banco, para isso podemos executar os comandos à seguir:

```console
php artisan key:generate
php artisan migrate:fresh --seed
```

O comando do migrate, irá criar todas as tabelas definidas no diretório **database/migrations**, e além disso o parâmetro **seed** irá semear nosso banco com um usuário fake para autenticação, e criar um dispositivo fake para podermos simular a conexão com nosso arquivo telnet fake.

## 3.4 Iniciando servidor de API

Para iniciarmos nosso servidor localmente, basta executarmos o comando à seguir:

```console
php artisan serve
```

**Observação:** Agora para autenticarmos na nossa API, podemos conectar via **Postman** ou qualquer outra ferramenta pela rota http://127.0.0.1:8000/api/login utilizando o método **POST**, esta rota espera o parâmetro **username**. Nosso banco de dados, pelo comando **seed** executado anteriormente, irá criar um usuário com nome **teste**, basta fornecer este usuário e poderemos autenticar com este usuário.

## 3.5 Estrutura dos diretórios e detalhes da autenticação

O projeto segue o padrão do Laravel, utilizando a estrutura do **MVC**:

-   Rotas da API, estão definidas no arquivo **routes/api.php** pelo padrão FACADE. Para mantermos a consistência de autenticação, apenas a rota **login** é liberada sem autenticação, enquanto as rotas específicas do dispositivo necessitam estarem autenticadas para serem acessadas.
-   Models estão definidos no diretório **app/Models**.
-   Controladores podem ser encontrados em **app/Models**. Estes estão divididos em 2, um para autenticação e outro para dispositivos.
-   A autenticação é feita seguindo o padrão **JWT**, ou seja, ao realizar o login o projeto retorna um token de acesso que deve ser fornecido nos cabeçalhos da requisição

# 4 Execução do servidor Telnet fake

Considerando o dispositivo fake que foi cadastrado, podemos iniciar o nosso servidor Telnet fake utilizando o comando a seguir na raíz do projeto:

```console
php telnetConnectionFake.php
```

Com isso, ao chamar a rota http://127.0.0.1:8000/api/device, e se nosso dispositivo tiver com a url padrão definida pelo semeador, o comando telnet será executado.

<!-- # 4 Usando o Docker para montar o banco

No diretório principal deste projeto, foi disponibilizado um arquivo **yml**, este yml está configurado para criar um container docker do MariaDB 10.5, o arquivo **.env.example** já está mapeado para conectar neste banco. Para executar o container basta executar o seguinte comando se estiver com docker instalado em sua máquina local:

```console
sudo docker-compose up -d -->

```

```
