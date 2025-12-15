up:
	docker compose up -d --build

down:
	docker compose down

php:
	docker compose exec php bash

install:
	docker compose exec php composer create-project laravel/laravel=10.* .

perm:
	docker compose exec php bash -lc "chmod -R 775 storage bootstrap/cache || true"

key:
	docker compose exec php php artisan key:generate

migrate:
	docker compose exec php php artisan migrate

fresh:
	docker compose exec php php artisan migrate:fresh --seed
