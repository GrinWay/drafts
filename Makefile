run:
	clear && symfony server:stop && symfony server:start

stop:
	clear && symfony server:stop

open:
	clear && symfony open:local

dump:
	clear && php "./bin/console" server:dump

cc:
	clear && php bin/console cache:clear

build:
	clear && yarn encore production --progress
