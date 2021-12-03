# LaravelDefaults

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require sevenlab/laravel-defaults
```

After installation, you can publish the config file to your application's config directory.
```bash
$ php artisan vendor:publish --provider="SevenLab\LaravelDefaults\LaravelDefaultsServiceProvider"
```

## Usage
By default every request will be logged. This can be disabled by setting the `log_after_request` config value to `false`.

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email info@7lab.nl instead of using the issue tracker.

## Credits

- [Beau van Rouwendal][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/sevenlab/laravel-defaults.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sevenlab/laravel-defaults.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/sevenlab/laravel-defaults/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/sevenlab/laravel-defaults
[link-downloads]: https://packagist.org/packages/sevenlab/laravel-defaults
[link-travis]: https://travis-ci.org/sevenlab/laravel-defaults
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/sevenlab
[link-contributors]: ../../contributors
