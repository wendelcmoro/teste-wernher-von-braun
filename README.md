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
   3.5 [Estrutura dos diretórios e detalhes da autenticação](##Estrutura-dos-diretórios-e-detalhes-da-autenticação)<br>
4. [Execução do servidor Telnet fake](#Execução-do-servidor-Telnet-fake)<br>
5. [Usando o Docker para montar o banco](#Usando-o-Docker-para-montar-o-banco)<br>
6. [Sugestões de melhorias e avanços futuros](#Sugestões-de-melhorias-e-avanços-futuros)<br>
   6.1 [Execução paralela para conexão de dispositivos](##Execução-paralela-para-conexão-de-dispositivos)<br>
   6.2 [Estrutura do projeto com docker](##Estrutura-do-projeto-com-docker)<br>
   6.3 [Identificador dos dispositivos](##Identificador-dos-dispositivos)<br>
   6.4 [Testes automatizados](##Testes-automatizados)<br>
   6.5 [Adicionar documentação com Swagger](##Adicionar-documentação-com-Swagger)<br>
   6.6 [Padronizar resposta e envio do telnet](##Padronizar-resposta-e-envio-do-telnet)<br>

# 1 Requisitos

-   **PHP** 8.2.17(**módulos utilizados na máquina local de desenvolvimento:** bz2, calendar, Core, ctype, curl, date, dom, exif, fileinfo, filter, ftp, gd, gettext, hash, iconv, igbinary, intl, json, libxml, mbstring, mcrypt, msgpack, mysqli, mysqlnd, openssl, pcntl, pcre, PDO, pdo_mysql, pdo_sqlite, Phar, posix, random, readline, redis, Reflection, session, shmop, SimpleXML, soap, sockets, sodium, SPL, sqlite3, standard, sysvmsg, sysvsem, sysvshm, tokenizer, xml, xmlreader, xmlwriter, xsl, Zend OPcache, zip, zlib)
-   **Composer** 2.7.2
-   **MariaDB** 10.5.23(Foi adicionado um arquivo docker para montar um container do banco, ver **seção 5**)

# 2 Descrição breve da implementação do projeto

O projeto foi feito utilizando o framework Laravel, este foi escolhido por conveniência e facilidade para implementar APIs restful, além de ter uma documentação ampla para expandir features.

Para simular uma conexão telnet com um dispositivo pré-cadastrado, foi criado um arquivo no diretório raíz do projeto com nome **telnetConnectionFake.php**, este arquivo simula a conexão esperando na url local **127.0.0.1:12000**.

Adicionalmente, foi adicionado um arquivo docker-compose para criar um container do MariaDB, o uso deste é opcional caso já tenha o **MySQL** ou **MariaDB** na máquina local, note que é necessário alterar as configurações do arquivo **.env** para isto.

# 3 Execução da API

## 3.1 Instalando dependências

Executar o comando no diretório raíz:

```console
composer i
```

## 3.2 Configurando ambiente local

Existe um arquivo **.env.example**, copiar este arquivo para um **.env** e substituir as variáveis do seu banco de dados local:

```console
cp .env.example .env
```

Por padrão, este arquivo já vem configurado com uma conexão de exemplo para o container docker.

## 3.3 Instalando chave secreta do app e criando tabelas pelas migrations

Em seguida, precisamos instalar a chave secreta do nosso app, e criar as tabelas do banco, para isso podemos executar os comandos à seguir:

```console
php artisan key:generate
php artisan migrate:fresh --seed
```

O comando do migrate, irá criar todas as tabelas definidas no diretório **database/migrations**, e além disso o parâmetro **seed** irá semear nosso banco com um usuário teste para autenticação, e criar um dispositivo de teste para podermos simular a conexão telnet.

## 3.4 Iniciando servidor de API

Para iniciarmos nosso servidor localmente, basta executarmos o comando à seguir:

```console
php artisan serve
```

**Observação:** A url padrão da nossa API será dada pelo formato http://127.0.0.1:8000/api/$endpoint. Para acessarmos na nossa API, podemos nos conectar via **Postman** ou qualquer outra ferramenta pela rota http://127.0.0.1:8000/api/login utilizando o método **POST**, esta rota espera o parâmetro **username**. Nosso banco de dados, pelo comando **seed** executado anteriormente, irá criar um usuário com nome **teste**.

## 3.5 Estrutura dos diretórios e detalhes da autenticação

O projeto segue o padrão do Laravel, utilizando a estrutura do **MVC**:

-   Rotas da API, estão definidas no arquivo **routes/api.php** pelo padrão estrutural FACADE. Para mantermos a consistência de autenticação, apenas a rota **login** é liberada sem autenticação, enquanto as rotas específicas do dispositivo necessitam de autenticação.
-   Models estão definidos no diretório **app/Models**.
-   Controladores podem ser encontrados em **app/Http/Controllers**. Estes estão divididos em 2, um para autenticação e outro para dispositivos.
-   A autenticação é feita via **JWT**, ao realizar o login o projeto retorna um token de acesso que deve ser fornecido nos cabeçalhos da requisição.

# 4 Execução do servidor Telnet fake

Considerando o dispositivo fake que foi cadastrado, podemos iniciar o nosso servidor Telnet fake utilizando o comando a seguir na raíz do projeto:

```console
php telnetConnectionFake.php
```

Com isso, ao chamar a rota http://127.0.0.1:8000/api/device, e se nosso dispositivo tiver com a url padrão definida pelo semeador, o comando telnet será executado. Por padrão, a porta deste processo é **12000**.

# 5 Usando o Docker para montar o banco

No diretório principal deste projeto, foi disponibilizado um arquivo **yml**, este yml está configurado para criar um container docker do MariaDB 10.5, o arquivo **.env.example** já está mapeado para conectar neste banco. Para executar o container basta executar o seguinte comando se estiver com docker instalado em sua máquina local:

```console
sudo docker-compose up -d
```

# 6 Sugestões de melhorias e avanços futuros

## 6.1 Execução paralela para conexão de dispositivos

Como primeira melhoria, poderia ser possível executar a parte do código que acessa as urls de cada dispositivo para processar isso de forma paralela, isso aumentaria a eficiência e desempenho do método.

## 6.2 Estrutura do projeto com docker

Essa sugestão seria uma melhor conveniência no ambiente de desenvolvimento(e também produção), que seria já criar algum tipo de script para montar o projeto da API em um container docker com todos os requisitos do projeto já instalados.

## 6.3 Identificador dos dispositivos e porta telnet

Alterar requisitos, pela documentação o identificador do dispositivo é um tipo **string**, uma mudança seria alterar esse campo para um inteiro.

Adicionalmente, o projeto por padrão usa a porta 12000 para conexão telnet, em caso de necessidade seria interessante trazer a porta da conexão ou pela url ou adicionar um campo para o dispositivo com a porta.

## 6.4 Testes automatizados

Adicionar automatização das rotas com testes unitários.

## 6.5 Adicionar documentação com Swagger

Adicionar uma documentação da API e dos endpoints utilizando o **swagger**.

## 6.6 Padronizar resposta e envio do telnet

Adicionalmente, a conexão apenas envia texto simples, seria interessante adicionar algum tipo de padrão para a conexão como JSON, XML, etc.
