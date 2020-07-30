#
# For our MySQL database we use the latest version of MariaDB.
#
FROM        mariadb:10.4.12
MAINTAINER  ACSEO <contact@acseo.fr>

RUN         apt-get -qq update --fix-missing && \
            apt-get -qq install -y zip unzip curl

#
# As mysql executable will create files on our local machine, we need to sync
# mysql userid / groupid with the one using docker on the host machine to avoid
# permission issues.
#
COPY 		./docker/services/database/conf/my.cnf /etc/mysql/my.cnf
RUN         chmod og-w /etc/mysql/my.cnf
RUN         usermod -u 1000 mysql
RUN         groupmod -g 1000 mysql
