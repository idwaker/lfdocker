FROM centos:6

MAINTAINER Diwaker Ghimire <idwaker@gmail.com>


# Install PHP and MySQL
RUN yum -y install php php-mysql php-intl php-pdo php-pear httpd mysql-server \
    python-setuptools git


# Clean up YUM when done.
RUN yum clean all


# install supervisor
RUN easy_install pip && pip install supervisor

ADD supervisord.conf /etc/supervisord.conf

# Add Composer
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar \
    /usr/local/bin/composer && chmod +x /usr/local/bin/composer

WORKDIR /srv

ADD run.sh /srv/run.sh
RUN chmod +x /srv/run.sh


# startup
CMD ["run.sh"]
