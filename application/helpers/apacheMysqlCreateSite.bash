#!/bin/bash
 
EXPECTED_ARGS=2
E_BADARGS=65
MYSQL=`which mysql`
name = $1
pass = $2
document_root = "public_html"
root_dir = "$HOME/vhosts/"
server_user = "www-data"
domain = "$1.local"
email = "mariomarinero@gmail.com"

Q1="CREATE DATABASE $1;"
Q2="GRANT ALL ON $name.* TO '$name.'@'localhost' IDENTIFIED BY '$pass';"
Q3="FLUSH PRIVILEGES;"
SQL="${Q1}${Q2}${Q3}"
 
if [ $# -ne $EXPECTED_ARGS ]
then
  echo "Usage: $0 siteName dbpass"
  exit $E_BADARGS
fi
 
$MYSQL -uroot -p -e "$SQL"


# This script is used to create virtual hosts.

# Create the web directory and a index.php test file
dir = "$root_dir/$name/"
mkdir -p "$dir/$document_root"
cd "$dir/$document_root"

# Set the owner and change permissions
chown -R $server_user $dir/
chmod -R '750' $dir

# Create a directory for your apache errors log
mkdir /var/log/apache2/$name/

# Creation the file with VirtualHost configuration in /etc/apache2/site-available/
echo "<VirtualHost *:80>
        ServerAdmin $email
        ServerName $domain
        ServerAlias www.$domain
        DocumentRoot $dir/$document_root
        <Directory />
                Options FollowSymLinks
                AllowOverride All
        </Directory>
        <Directory $dir/$document_root>
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
        ErrorLog /var/log/apache2/$name/error.log
        # Possible values include: debug, info, notice, warn, error, crit,
        # alert, emerg.
        LogLevel warn
        CustomLog /var/log/apache2/$name/access.log combined
    Alias /doc/ "'/usr/share/doc/'"
    <Directory "'/usr/share/doc/'">
        Options Indexes MultiViews FollowSymLinks
        AllowOverride All
        Order deny,allow
        Deny from all
        Allow from 127.0.0.0/255.0.0.0 ::1/128
    </Directory>
</VirtualHost>" > /etc/apache2/sites-available/$name

# Add the host to the hosts file
#echo 127.0.0.1 $sn >> /etc/hosts

# Enable the site
a2ensite $name

# Reload Apache2
/etc/init.d/apache2 reload
