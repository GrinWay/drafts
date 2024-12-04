run:
	clear && symfony server:stop && symfony server:start

stop:
	clear && symfony server:stop

open:
	clear && symfony open:local

dump:
	clear && symfony console server:dump

cc:
	clear && symfony console cache:clear

build:
	clear && yarn encore production --progress
