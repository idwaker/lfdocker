
# clone project files
git clone https://github.com/idwaker/lfdocker.git .

# install dependencies
composer install

# import database schema
mysql -uroot < dump.sql


# run supervisord
supervisord -c /etc/supervisord.conf
