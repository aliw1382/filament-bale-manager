# Requirement

* php: >=8.0
* filament/filament: *
* filament/forms: *
* laravel/framework: >= 9.*
* mokhosh/filament-jalali: *

# Installation

```bash
$ composer require aliw1382/filament-bale-manager
```

Add Provider To `config/app.php` File

```php
Aliw1382\FilamentBaleManager\Providers\FilamentBaleManagerServiceProvider::class,
```

And Run This Command

```bash
$ php artisan vendor:publish --provider=Aliw1382\FilamentBaleManager\Providers\FilamentBaleManagerServiceProvider
```


# History

Please see [History](history.md) for more information on what has been changed recently.


# Security

If you discover any security related issues, please email aliw1382@gmail.com instead of using the issue tracker.

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
