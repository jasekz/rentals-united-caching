# Rentals United Caching

[![Software License][ico-license]](LICENSE)


Synchronize [Rentals United](http://rentalsunited.com/) data with your local database.  

## Installation

NOTE: If you haven't set up a database yet for your app, please do that first as per Laravel docs -  http://laravel.com/docs/5.0/database.

Via composer
```
composer require jasekz/rentals-united-caching
```
```
composer update
```

Then in your `config/app.php` add 
```php
    'Jasekz\RentalsUnitedCaching\RentalsUnitedCachingServiceProvider'
```    
to the `providers` array and
```php
    'RentalsUnited' => 'Jasekz\RentalsUnitedCaching\RentalsUnitedCachingFacade'
```
to the `aliases` array.

Finally, run 

    artisan vendor:publish
    
followed by

    artisan migrate

Now in your .env file, define your Rentals United credentials and path to store the downloaded XML files (temporary storage):
```php
RENTALS_UNITED_USERNAME=<your Rentals United username/email>
RENTALS_UNITED_PASSWORD=<your Rentals United password>
XML_CACHE_DIR='/path/to/cache/directory/'
```
## Usage Examples
- artisan **rentals_united:cache_all** // truncate all tables and cache everything
- artisan **rentals_united:cache_properties --id=4** // cache property (ID) 4
- artisan **rentals_united:cache_properties --id=4,5** // cache properties (ID) 4 & 5
- artisan **rentals_united:cache_properties --id=new** // find and cache all new properties
- artisan **rentals_united:update_change_log --since="-1 month"** // check for updates 'since' given date/time
- artisan **rentals_united:update_change_log --since="2014-03-26 12:51:00"** // check for updates 'since' given date/time
- artisan **rentals_united:update_properties --since="-1 month"** // update properties which changed 'since' given date/time
- artisan **rentals_united:update_properties --since="2014-03-26 12:51:00"** // update properties which changed 'since' given date/time


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.



[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
