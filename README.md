# Requirement

* "php": ">=8.0"
* "filament/filament": "^2.0|^3.0"
* "laravel/framework": "^8.0|^9.0|^10.0|^11.0"
* "filament/forms": "^2.0|^3.0"
* "mokhosh/filament-jalali": "^2.0|^3.0"

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

Add Plugin To Filament, Open File Panel Provider

```php
public function panel( Panel $panel ) : Panel
{

    return $panel
    // ...
    ->plugins([
    
        BalePlugin::make(), # <--- Add This Line
    
    ]);
    
}
```

# History

Please see [History](history.md) for more information on what has been changed recently.


# Security

If you discover any security related issues, please email aliw1382@gmail.com instead of using the issue tracker.

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
