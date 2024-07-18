bash "./public/deploy/install-grinway-symfony-bundles.sh"

php bin/console doctrine:schema:drop -f --full-database
php bin/console make:migration
php bin/console doctrine:migrations:migrate -q
php bin/console doctrine:fixture:load -q

yarn install
yarn run dev