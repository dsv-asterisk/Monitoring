
#                PAINEL DE MONITORAMENTO DONGLE                     #
                                               Por:Israel Azevedo  


### Descrição: ###
- Painel de Monitoramento de Dongles.

### Colaborador(es): ###
- [Renato Siqueira](https://github.com/RenatoSiqueira)

### Instalação ###
- Descompactar o arquivo .zip dentro do diretório /var/www/ 

### Detalhamento ###
- Pasta CSS: Contém o css pré-concatenado-compilado e semi-minificado.
- Pasta Fonts: Contém os arquivos do font-awesome
- Pasta Img: Contém o logo da empresa
- Pasta JS: Contém o js pré-concatenado-compilado e semi-minificado.
- Pasta Original: Contém o arquivo original desenvolvido pelo Israel
- Pasta Resource: Contém os arquivos originais do font-awesome
- Pasta Source: 
  - JS: Contém todos os arquivos originais utilizados.
  - SCSS: Contém todos os arquivos originais utilizados.

### Arquivos de Produção ###
- Para uso em produção, os seguintes poderão ser apagados:
  - Pasta Original
  - Pasta Resource
  - Pasta Source

#### CASO DEBIAN 8 ou CENTOS NO DIRETÓRIO #####
$ cd /var/www/html/

$ tar -xvf master.zip

entre no diretório modens

##### DEBIAN 7 
$ cd /var/www/dongle

##### DEBIAN 8
$ cd /var/www/html/dongle

##### CentOS
$ cd /var/www/html/dongle

#### INSTALANDO SUDO ####
##### Debian / Ubuntu
$ apt-get install sudo

##### CentOS
$ yum install sudo

#### EDITE SUDOERS ####
Edite o arquivo /etc/sudoers colocando os seguintes parametros na linha abaixo da permissão de ROOT:

www-data ALL = NOPASSWD : /var/spool/asterisk/outgoing
www-data ALL=NOPASSWD:ALL

Crie um usuario para o manager do Asterisk com o seguintes parametros

PERMISSÕES AMI

read = system,call,log,verbose,command,agent,user,config,command,dtmf,reporting,cdr,dialplan,originate,all
write = system,call,log,verbose,command,agent,user,config,command,dtmf,reporting,cdr,dialplan,originate,all

TIMEOUT DE ESCRITA

writetimeout = 5000

#### CONFIGURANDO O ACESSO ####
E adicione as credenciais no arquivo "config.php "

#### Finalizando ####
Dê permissão de root para o diretorio modens para que possa executar todas as funções.

##### Debian 7
$ chmod -R 0777 /var/www/dongle

##### Debian 8 ou CentOS
$ chmod -R 0777 /var/www/html/dongle

#### ACESSO ####
##### http://IP_do_seu_servidor/dongle/
