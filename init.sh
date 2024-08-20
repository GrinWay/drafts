bash "./public/deploy/install-grinway-symfony-bundles.sh"

php bin/console doctrine:database:drop -f
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate -q
php bin/console doctrine:fixture:load -q

yarn install
yarn run dev