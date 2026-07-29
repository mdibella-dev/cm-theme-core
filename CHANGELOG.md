# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

<br>

## [Unreleased]

### Added
- Add more human readable time in column 'last modified'
- Add plugin's CSS class to admin body 
- Add more labels to post types and taxonomies
- Add more CSS to backend tables and pages

### Changed
- Rename project
- Folder and file structure
- Change namespace 'CM_theme' to 'congressomat'
- Change namespace structure
- Hide publishing actions in post types
- Overhaul labels and german translation
- Changelog style
- Reorder admin menu and add separator between event and exhibition settings

### Fixed
- Fix partnership filter in exhibtion list 
- Fix not working links to edit pages

### Removed
- setup.php (code merged into plugin.php)
- Remove slug column from exhibition_package and location

<br>

## [2.1.0] - 2023-09-26

### Changed
- Convert procedural taxonomy and post lists code into classes
- Remove class variables
- Reorganize folder structure of custom post types and taxonomies

<br>

## [2.0.0] - 2023-08-25

### Added
- Abstract Shortcode class

### Changed
- Convert procedural shortcode code into classes
- Remove support for shortcode partner-table

### Fixed
- Remove PHP warning "Undefined variable $output"
- Remove PHP warning "Undefined property $name"

<br>

## [1.0.0] - 2023-07-25

- Initial commit
