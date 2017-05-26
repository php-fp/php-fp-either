# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0]

### Added

- `Either::left` to construct a `Left` instance
- `Either::right` to construct a `Right` instance

### Removed

- `Left` and `Right` can no longer be constructed using `new`

### Changed

- `::of()` and `::tryCatch()` have been marked `final` to prevent extension

## [1.0.0]

- Initial release
