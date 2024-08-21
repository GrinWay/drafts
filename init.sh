bash "./public/deploy/install-grinway-symfony-bundles.sh"

php bin/console doctrine:migrations:migrate -q --env=dev

yarn install
yarn run dev