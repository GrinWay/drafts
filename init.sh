bash "./public/deploy/install-grinway-symfony-bundles.sh"

echo 'APP_ADMIN_MAILER_LOGIN'
php bin/console secrets:set APP_ADMIN_MAILER_LOGIN
echo 'APP_ADMIN_MAILER_PASS'
php bin/console secrets:set APP_ADMIN_MAILER_PASS

echo 'APP_ADMIN_GOOGLE_LOGIN'
php bin/console secrets:set APP_ADMIN_GOOGLE_LOGIN
echo 'APP_ADMIN_GOOGLE_PASS'
php bin/console secrets:set APP_ADMIN_GOOGLE_PASS

echo 'APP_ADMIN_EMAIL'
php bin/console secrets:set APP_ADMIN_EMAIL

echo 'APP_TO_TEST_EMAIL'
php bin/console secrets:set APP_TO_TEST_EMAIL

echo 'CACHE_0_DECRYPTION_KEY'
bin/console secrets:set CACHE_0_DECRYPTION_KEY

echo 'CACHE_1_DECRYPTION_KEY'
bin/console secrets:set CACHE_1_DECRYPTION_KEY

#php bin/console doctrine:migrations:migrate -q --env=dev

#yarn install && yarn run dev