# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [v0.9.5] - 2026-06-24
### Added
- Support for PSR-3 loggers in database drivers for query debugging.
- Support for passing a logger to `DriverFactory` and enabling debug logging via the connection configuration.

### Fixed
- A potential PHP notice/warning in `PostgresqlDescriptor` when checking the default value of single-column primary keys (safely handling cases where the default value is null).

## [v0.9.4] - 2026-06-17
### Added
- Injectable database descriptors to allow easier mocking in tests.

## [v0.9.3] - 2026-03-03
### Fixed
- Ensure connection is established when transactions begin.

## [v0.9.2] - 2026-03-02
### Fixed
- Faulty search query sending.

## [v0.9.1] - 2025-09-07
### Fixed
- Bug causing database connections to initiate when not needed.

## [v0.9.0] - 2025-04-05
### Added
- Access to the driver for transaction management through the database context.

### Changed
- Cleaned up documentation and type hints.

## [v0.8.0] - 2025-01-20
### Added
- Ability to pass PDO attributes to the connection.
- Type hints to align the library with modern PHP features.
- Migrated tests from TravisCI to GitHub Actions workflows.

## [v0.7.2] - 2018-12-02
### Added
- First release to document changes in a changelog.

### Removed
- Disabled boolean type temporarily due to inconsistent definition across database drivers.
