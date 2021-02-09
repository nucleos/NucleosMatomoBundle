# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 3.2.0 - TBD

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
