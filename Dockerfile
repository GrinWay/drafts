FROM php:8.2.26-apache

ENV PANTHER_NO_SANDBOX 1
ENV PANTHER_CHROME_ARGUMENTS='--disable-dev-shm-usage'
RUN apt-get update
RUN apt-get -qq install wget -y
RUN apt-get upgrade
RUN apt-get -qq install chromium -y
RUN apt-get -qq install chromium-driver -y

WORKDIR /app

COPY . .

RUN export PATH=/app/drivers:$PATH

#ENTRYPOINT ["php","./vendor/bin/bdi","detect","drivers"]

#CMD ["php","./bin/phpunit","--testsuit","all"]
###> recipes ###
###< recipes ###
