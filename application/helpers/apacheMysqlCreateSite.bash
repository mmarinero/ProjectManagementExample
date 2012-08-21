#!/bin/bash
 
EXPECTED_ARGS=2
E_BADARGS=65
MYSQL=`which mysql`
USERNAME = $1
PASS = $2
document_root = "public_html"
Q1="CREATE DATABASE $1;"
Q2="GRANT ALL ON $USERNAME.* TO '$USERNAME.'@'localhost' IDENTIFIED BY '$PASS';"
Q3="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}${Q3}"
 
if [ $# -ne $EXPECTED_ARGS ]
then
  echo "Usage: $0 siteName dbpass"
  exit $E_BADARGS
fi
 
$MYSQL -uroot -p -e "$SQL"


# This script is used to create virtual hosts.

dir = "$HOME/vhosts/$USERNAME/"
domain = "$1.local"
email = "mariomarinero@gmail.com"

# Create the web directory and a index.php test file
mkdir -p "$dir/$document_root"
cd "$dir/$document_root"

# Set the owner and change permissions
chown -R ftpuser:www-data $homedir/
chown -R ftpuser:www-data $homedir/$sn/
chmod -R '750' $dir

# Create a directory for your apache errors log
mkdir /var/log/apache2/$USERNAME/

# Creation the file with VirtualHost configuration in /etc/apache2/site-available/
echo "<VirtualHost *:80>
        ServerAdmin $se
        ServerName $sn
        ServerAlias www.$sn
        DocumentRoot $homedir/$sn/public_html/
        <Directory />
                Options FollowSymLinks
                AllowOverride All
        </Directory>
        <Directory $homedir/$sn/public_html/>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>
        ScriptAlias /cgi-bin/ /usr/lib/cgi-bin/
        <Directory "'/usr/lib/cgi-bin'">
                AllowOverride All
                Options +ExecCGI -MultiViews +SymLinksIfOwnerMatch
                Order allow,deny
                Allow from all
        </Directory>
        ErrorLog /var/log/apache2/$sn/error.log
        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn
        CustomLog /var/log/apache2/$sn/access.log combined
    Alias /doc/ "'/usr/share/doc/'"
    <Directory "'/usr/share/doc/'">
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order deny,allow
        Deny from all
        Allow from 127.0.0.0/255.0.0.0 ::1/128
    </Directory>
</VirtualHost>" > /etc/apache2/sites-available/$sn

# Add the host to the hosts file
echo 127.0.0.1 $sn >> /etc/hosts

# Enable the site
a2ensite $sn

# Reload Apache2
/etc/init.d/apache2 reload
