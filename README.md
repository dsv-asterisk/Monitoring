# dongle
painel de monitoramento chan_dongle


##                PAINEL DE MONITORAMENTO DONGLE                     ##
                                               Por:Israel Azevedo  


Instalação simples do serviço de monitoramento, basta mover para o
diretório /var/www/ descompactar o arquivo modens.tar dentro do www

# CASO DEBIAN 8 ou CENTOS NO DIRETÓRIO #

/var/www/html/

tar -xvf dongle.tar


entre no diretório modens

# DEBIAN 7
cd /var/www/dongle

# DEBIAN 8
cd /var/www/html/dongle

# CentOS

cd /var/www/html/dongle

# INSTALANDO SUDO
Instale o sudo:
# Debian / Ubuntu
apt-get install sudo

# CentOS
 yum install sudo

# EDITE
Edite o arquivo /etc/sudoers colocando os seguintes parametros na linha abaixo da permissão de ROOT:

www-data ALL = NOPASSWD : /var/spool/asterisk/outgoing
www-data ALL=NOPASSWD:ALL

Crie um usuario para o manager do Asterisk com o seguintes parametros

PERMISSÕES AMI

read = system,call,log,verbose,command,agent,user,config,command,dtmf,reporting,cdr,dialplan,originate,all
write = system,call,log,verbose,command,agent,user,config,command,dtmf,reporting,cdr,dialplan,originate,all

TIMEOUT DE ESCRITA

writetimeout = 5000

# CONFIGURANDO O ACESSO
E adicione as credenciais no arquivo " config.php "


Por fim, Dê permissão de root para o diretorio modens para que possa executar todas as funções.

# Debian 7
chmod -R 0777 /var/www/dongle

# Debian 8 ou CentOS
chmod -R 0777 /var/www/html/dongle


# FIM

Pronto! Só rodar no Browser.

# http://IP_do_seu_servidor/dongle/
