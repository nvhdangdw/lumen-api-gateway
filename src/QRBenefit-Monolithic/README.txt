
- Setup step by step
	+ git clone https://huynhphamptn@bitbucket.org/tuongnguyen1312/qr-code-prototype.git ( example with me )
	+ Run command line ( root folder )
		- composer install
	+ Update config for your local ( .env file)
		APP_URL=http://localhost:8000
		DB_CONNECTION=mysql
		DB_HOST=127.0.0.1
		DB_PORT=3306
		DB_DATABASE=qr-code-prototype
		DB_USERNAME=root
		DB_PASSWORD=
	+ Run command line ( root folder )
		- php artisan config:cache
		- php artisan migrate

	+ Run script sql (phpmyadmin)
		INSERT INTO `group_users` (`id`, `name`, `created_at`, `updated_at`) VALUES
		(1, 'ADMIN', NULL, NULL),
		(2, 'STORE', NULL, NULL);

	+ Run command line ( root folder )
		- php artisan serve

	admin/login : huynh.pham@ptnglobalcorp.com/12356

	install JWT
	+ Run command line ( root folder )
		- php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
		- php artisan jwt:secret