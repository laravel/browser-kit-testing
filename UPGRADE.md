# Upgrade Guide

## Upgrading To 7.0 From 6.x

### Minimum Versions

The following required dependency versions have been updated:

- The minimum PHP version is now v8.1
- The minimum Laravel version is now v10.0
- The minimum PHPUnit version is now v10.0

### Service Mocking

The deprecated `MocksApplicationServices` trait has been removed from the library. This trait provided testing methods such as `expectsEvents` and `expectsJobs`.

If your application uses these methods, we recommend you transition to `Event::fake` and `Bus::fake`, respectively. You can learn more about mocking via fakes in the corresponding documentation for the component you are attempting to fake.


## Upgrading To 6.0 From 5.x

### Minimum Laravel Version

Laravel 7.0 is now the minimum supported version of the framework.

### Minimum PHP Version

PHP 7.2 is now the minimum supported version of the language.

### Minimum PHPUnit Version

PHPUnit 8.5 is now the minimum supported version of the library.

### Keep Cookies Between Redirects

PR: https://github.com/laravel/browser-kit-testing/pull/107

When using the `followRedirects` method, previously defined cookies will now be preserved.


## Upgrading To 5.0 From 4.x

### PHPUnit 8

Browser Kit Testing now provides optional support for PHPUnit 8, which requires PHP >= 7.2 Please read through the entire list of changes in [the PHPUnit 8 release announcement](https://phpunit.de/announcements/phpunit-8.html). Using PHPUnit 8 will require Laravel 5.8, which will be released at the end of February 2019.

You may also continue using PHPUnit 7, which requires a minimum of PHP 7.1.

### Minimum Laravel version

Laravel 5.7 is now the minimum supported version of the framework and you should upgrade to continue using Browser Kit Testing.

### `setUp` and `tearDown` changes

The `setUp` and `tearDown` methods now require the `void` return type. If you were overwriting these methods you should add it to the method signatures.
