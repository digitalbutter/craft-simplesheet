# SimpleSheet plugin for Craft CMS 3.x

Provides an additional Spreadsheet Field Type for Craft CMS.

![Screenshot](resources/img/plugin-logo.png)

## TODO ##

- Remove unnecessary references, attributes, etc from code
- Remove local copy of HoT for admin section (change to CDN reference)
- Clean up settings and input templates
- Remove unnecessary Event from main plugin file
- Clean up code comments
- Add a plugin icon
- Update this README.MD

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require /simple-sheet

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for SimpleSheet.

## SimpleSheet Overview

-Insert text here-

## Configuring SimpleSheet

-Insert text here-

## Using SimpleSheet

Rendering a non-editable version of your spreadsheet in the frontend is a simple matter of using the `embed` method on your Simplesheet field. This will return a div container tag with your provided (or auto-generated ID), and will include the JS and CSS required to render the spreadsheet.

`        {{ mySimpleSheetField.embed({}) }}


## SimpleSheet Roadmap

Some things to do, and ideas for potential features:

* Release it
* Support for non-volatile display settings (text alignment, column width, etc)
* Cell colors
* Cell borders

Brought to you by [Daniel Jackson](https://github.com/dgjackson)
