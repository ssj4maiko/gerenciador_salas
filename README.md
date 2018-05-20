# gerenciador_salas

Este código foi testado com PHP 7.2, a versão mais recente do Laravel que foi utilizada talvez não funcione em versões mais antigas.

No momento, o código está fixo para a url public/ se rodar via Apache, caso contrário, tem-se de editar as configurações.

Comandos para iniciar:

Para preparação do Banco de Dados, usar o SQL em anexo ou rodar o comando abaixo para um estado em branco (Precisa se criar um banco chamado "gerenciador_salas" manualmente antes)
>	php artisan migrate

O SQL em anexo possui 2 salas, algumas reservas para o dia (Possui dados no dia 19/05/2018), e 2 usuarios, user1 e user2, usuario e senha são iguais. Começar os dados em branco também não é problema.

Para iniciar os pacotes
> composer install