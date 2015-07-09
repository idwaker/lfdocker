FROM ubuntu

VOLUME ["/var/www/html"]

RUN apt-get update
RUN apt-get upgrade -y

RUN apt-get install -y apache2 php5 libapache2-mod-php5 php5-json

ADD . /var/www/html

# set apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid
ENV APACHE_RUN_DIR /var/run/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_SERVERADMIN admin@localhost
ENV APACHE_SERVERNAME localhost
ENV APACHE_SERVERALIAS docker.localhost
ENV APACHE_DOCUMENTROOT /var/www/html

EXPOSE 80
WORKDIR /var/www/html

RUN chown www-data:www-data -R /var/www/html

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

# /bin/sh doesnot work with source
RUN rm /bin/sh && ln -s /bin/bash /bin/sh
RUN source /etc/apache2/envvars

CMD ["apache2ctl", "-D FOREGROUND"]
