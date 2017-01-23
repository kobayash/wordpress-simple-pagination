#!/usr/bin/env bash

docker exec -it -u docker wordpress wp core download --locale=ja --force
docker exec -it -u docker wordpress wp core config --dbname=wordpress --dbuser=root --dbhost=mysql --dbpass=root
docker exec -it -u docker wordpress wp db create
docker exec -it -u docker wordpress wp core install --url=http://localhost --title=WordPress --admin_user=root --admin_email=root@example.com --skip-email --admin_password=root
docker exec -it -u docker wordpress ln -s /plugin ./wp-content/plugins/wordpress-simple-pagination
docker exec -it -u docker wordpress wp scaffold plugin wordpress-simple-pagination --ci=circle
docker exec -it -u docker wordpress ./wp-content/plugins/wordpress-simple-pagination/bin/install-wp-tests.sh wordpress_test root root mysql

docker exec -it -u docker wordpress phpunit --configuration wp-content/plugins/wordpress-simple-pagination/
