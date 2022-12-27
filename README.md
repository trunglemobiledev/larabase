## Larabase 🦪

Install
```
composer install
```
```
composer dump-autoload
```

Create .env from .env.example
```
cp .env.example .env
```

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Gen jwt key secret .env
```
php artisan jwt:secret
```

Laravel permission

```angular2html
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

```angular2html
php artisan optimize:clear
```

```
php artisan migrate
```



