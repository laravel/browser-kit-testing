# Release Notes

## [Unreleased](https://github.com/laravel/browser-kit-testing/compare/v6.2.3...6.x)


## [v6.2.3 (2020-11-30)](https://github.com/laravel/browser-kit-testing/compare/v6.2.2...v6.2.3)

### Fixed
- Fix seeJson with null data ([#160](https://github.com/laravel/browser-kit-testing/pull/160))


## [v6.2.2 (2020-11-24)](https://github.com/laravel/browser-kit-testing/compare/v6.2.1...v6.2.2)

### Fixed
- Use TestResponse as return value in PHPDoc ([#150](https://github.com/laravel/browser-kit-testing/pull/150))
- Fix missing `$cookies` argument ([#151](https://github.com/laravel/browser-kit-testing/pull/151))


## [v6.2.1 (2020-11-10)](https://github.com/laravel/browser-kit-testing/compare/v6.2.0...v6.2.1)

### Fixed
- Add missing import for CookieValuePrefix ([#148](https://github.com/laravel/browser-kit-testing/pull/148))


## [v6.2.0 (2020-10-30)](https://github.com/laravel/browser-kit-testing/compare/v6.1.0...v6.2.0)

### Added
- PHP 8 Support ([#146](https://github.com/laravel/browser-kit-testing/pull/146))


## [v6.1.0 (2020-08-25)](https://github.com/laravel/browser-kit-testing/compare/v6.0.0...v6.1.0)

### Added
- Support Laravel 8 ([#140](https://github.com/laravel/browser-kit-testing/pull/140))

### Security
- Cookie handling fixes ([#137](https://github.com/laravel/browser-kit-testing/pull/137), [#139](https://github.com/laravel/browser-kit-testing/pull/139))


## [v6.0.0 (2020-03-03)](https://github.com/laravel/browser-kit-testing/compare/v5.1.4...v6.0.0)

### Added
- Allow PHPUnit 9 ([#121](https://github.com/laravel/browser-kit-testing/pull/121))

### Changed
- Bumped minimum dependencies to Laravel 7.0 ([#111](https://github.com/laravel/browser-kit-testing/pull/111))
- Dropped support for PHP 7.1 ([d0152a0](https://github.com/laravel/browser-kit-testing/commit/d0152a091a3ada16b2fa70fab1f7e4e42eb539cf))
- Dropped support for PHPUnit 7.x
- Keep cookies between redirects ([#107](https://github.com/laravel/browser-kit-testing/pull/107))
- Utilise `illuminate/testing` ([#126](https://github.com/laravel/browser-kit-testing/pull/126))

### Removed
- Remove deprecated `seeJsonSubset` method ([#116](https://github.com/laravel/browser-kit-testing/pull/116))


## [v5.2.0 (2020-10-30)](https://github.com/laravel/browser-kit-testing/compare/v5.1.4...v5.2.0)

### Added
- PHP 8 Support ([#147](https://github.com/laravel/browser-kit-testing/pull/147))


## [v5.1.4 (2020-08-25)](https://github.com/laravel/browser-kit-testing/compare/v5.1.3...v5.1.4)

### Security
- Cookie handling fixes ([#137](https://github.com/laravel/browser-kit-testing/pull/137), [#139](https://github.com/laravel/browser-kit-testing/pull/139))


## [v5.1.3 (2019-07-30)](https://github.com/laravel/browser-kit-testing/compare/v5.1.2...v5.1.3)

### Changed
- Updated version constraints for Laravel 6.0 ([73b18b2](https://github.com/laravel/browser-kit-testing/commit/73b18b2835db45b08f80c0a04cb0a74f5f384d95))


## [v5.1.2 (2019-03-12)](https://github.com/laravel/browser-kit-testing/compare/v5.1.1...v5.1.2)

### Fixed
- Implement abstract `shouldReport` method ([#93](https://github.com/laravel/browser-kit-testing/pull/93#issuecomment-468863285))


## [v5.1.1 (2019-02-16)](https://github.com/laravel/browser-kit-testing/compare/v5.1.0...v5.1.1)

### Fixed
- Fix PHPUnit 8 strict comparison ([48a7a39](https://github.com/laravel/browser-kit-testing/commit/48a7a39de5603a604a70b94671a8e89b4bb42b99))


## [v5.1.0 (2019-02-12)](https://github.com/laravel/browser-kit-testing/compare/v5.0.0...v5.1.0)

### Added
- Laravel 5.8 support ([d1be15a](https://github.com/laravel/browser-kit-testing/commit/d1be15aca3d4a1a659533600f5dfcf22a9d85aca))


## [v5.0.0 (2019-02-05)](https://github.com/laravel/browser-kit-testing/compare/v4.2.1...v5.0.0)

### Added
- Provide PHPUnit 8 compatibility ([d4f4894](https://github.com/laravel/browser-kit-testing/commit/d4f48946b29e412f477296ddb63738d0ce59a960))

### Changed
- Update minimum Laravel version ([1185004](https://github.com/laravel/browser-kit-testing/commit/1185004ceed0b841a5cc4367fcb492526a81e68a))
- Update Symfony dependencies to latest version ([b60a346](https://github.com/laravel/browser-kit-testing/commit/b60a346e783163d29a1ccc4f488b40534abb06c4))
