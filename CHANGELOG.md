# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.4.0 - TBD

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 3.3.0 - 2021-12-08


-----

### Release Notes for [3.3.0](https://github.com/nucleos/NucleosMatomoBundle/milestone/3)

Feature release (minor)

### 3.3.0

- Total issues resolved: **0**
- Total pull requests resolved: **6**
- Total contributors: **1**

#### dependency

 - [595: Add symfony 6 support](https://github.com/nucleos/NucleosMatomoBundle/pull/595) thanks to @core23
 - [594: Drop symfony 4 support](https://github.com/nucleos/NucleosMatomoBundle/pull/594) thanks to @core23
 - [580: Drop PHP 7 support](https://github.com/nucleos/NucleosMatomoBundle/pull/580) thanks to @core23

#### Enhancement

 - [593: Drop node-sass](https://github.com/nucleos/NucleosMatomoBundle/pull/593) thanks to @core23
 - [590: Update tools and use make to run them](https://github.com/nucleos/NucleosMatomoBundle/pull/590) thanks to @core23

#### Bug

 - [352:  Throw LogicException when rendering block without template](https://github.com/nucleos/NucleosMatomoBundle/pull/352) thanks to @core23

## 3.2.0 - 2021-02-09



-----

### Release Notes for [3.2.0](https://github.com/nucleos/NucleosMatomoBundle/milestone/1)



### 3.2.0

- Total issues resolved: **0**
- Total pull requests resolved: **2**
- Total contributors: **1**

#### Enhancement

 - [298: Fix minor phpstan findings](https://github.com/nucleos/NucleosMatomoBundle/pull/298) thanks to @core23

#### dependency

 - [204: Add support for PHP 8](https://github.com/nucleos/NucleosMatomoBundle/pull/204) thanks to @core23

## 3.1.0

### Changes

### 🐛 Bug Fixes

- Add support for matomo 4 [@core23] ([#212])
- Move configuration to PHP [@core23] ([#65])

## 3.0.0

### Changes

- Renamed namespace `Core23\MatomoBundle` to `Nucleos\MatomoBundle` after move to [@nucleos]

  Run

  ```
  $ composer remove core23/matomo-bundle
  ```

  and

  ```
  $ composer require nucleos/matomo-bundle
  ```

  to update.

  Run

  ```
  $ find . -type f -exec sed -i '.bak' 's/Core23\\MatomoBundle/Nucleos\\MatomoBundle/g' {} \;
  ```

  to replace occurrences of `Core23\MatomoBundle` with `Nucleos\MatomoBundle`.

  Run

  ```
  $ find -type f -name '*.bak' -delete
  ```

  to delete backup files created in the previous step.

- Add missing strict file header [@core23] ([#34])
- Replace HTTPlug with PSR http client [@core23] ([#31])

### 📦 Dependencies

- Add missing twig/extra-bundle dependency [@core23] ([#50])
- Add missing twig intl extension [@core23] ([#39])
- Add support for symfony 5 [@core23] ([#25])
- Drop support for symfony 3 [@core23] ([#36])
- Drop Sonata CoreBundle dependency [@core23] ([#35])

[#212]: https://github.com/nucleos/NucleosMatomoBundle/pull/212
[#65]: https://github.com/nucleos/NucleosMatomoBundle/pull/65
[#50]: https://github.com/nucleos/NucleosMatomoBundle/pull/50
[#39]: https://github.com/nucleos/NucleosMatomoBundle/pull/39
[#36]: https://github.com/nucleos/NucleosMatomoBundle/pull/36
[#35]: https://github.com/nucleos/NucleosMatomoBundle/pull/35
[#34]: https://github.com/nucleos/NucleosMatomoBundle/pull/34
[#31]: https://github.com/nucleos/NucleosMatomoBundle/pull/31
[#25]: https://github.com/nucleos/NucleosMatomoBundle/pull/25
[@nucleos]: https://github.com/nucleos
[@core23]: https://github.com/core23
