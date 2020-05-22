# Chatbot APIs

## URIs
  POST     | api/login
  
  POST     | api/register 
  
  PATCH    | api/users/{user}/set-currency 
  
  GET|HEAD | api/users/{user}/transactions 
  
  POST     | api/users/{user}/transactions

## Steps
1) composer install
2) php artisan make:migration
3) composer dump-autoload
4) php artisan db:seed
5) php artisan passport:install
6) php artisan serve
