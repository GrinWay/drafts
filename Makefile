run:
	cls && symfony server:stop && symfony server:start --no-tls
	
stop:
	cls && symfony server:stop

dump:
	cls && php "./bin/console" server:dump
	
build:
	cls && yarn encore production --progress