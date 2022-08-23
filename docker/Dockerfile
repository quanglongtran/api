FROM wyveo/nginx-php-fpm:php81
# FROM laravel:php81

LABEL maintainer tql0928159331@gmail.com 

WORKDIR /usr/share/nginx/api

RUN chmod -R 777 .

RUN apt update

RUN apt install neovim nodejs npm composer -y

RUN echo 'Xin chào Long' > /diary.txt

RUN echo "`date`" >> /diary.txt