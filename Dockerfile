FROM ggmartinez/laravel:php-82

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 /app/storage /app/bootstrap/cache

EXPOSE 9002

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9002"]
