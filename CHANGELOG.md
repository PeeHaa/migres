# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased] - 0000-00-00

### Added

*None*

### Changed

- Constraint rollbacks are now being introspected based on the `pg_constraint` table

### Deprecated

*None*

### Removed

*None*

### Fixed

- Foreign key constraints can now be rolled back properly

### Security

*None*

## [0.2.0] - 2019-08-07

### Added

- Label lengths are validated (63 bytes max)

### Changed

*None*

### Deprecated

*None*

### Removed

*None*

### Fixed

*None*

### Security

*None*

## [0.1.1] - 2019-31-07

### Added

- **Experimental** API for foreign keys

### Changed

*None*

### Deprecated

*None*

### Removed

*None*

### Fixed

- The binary now correctly finds the composer autoloader when added to a project

### Security

*None*
