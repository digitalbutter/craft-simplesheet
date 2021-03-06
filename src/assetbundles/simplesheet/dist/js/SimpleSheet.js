/**
 * SimpleSheet plugin for Craft CMS
 *
 * SimpleSheet Field JS
 *
 * @author    Daniel Jackson
 * @copyright Copyright (c) 2020 Daniel Jackson
 * @link      https://github.com/dgjackson
 * @package   SimpleSheet
 * @since     1SimpleSheetSimpleSheet
 */

 ;(function ( $, window, document, undefined ) {

    var pluginName = "SimpleSheet",
        defaults = {
        };

    // Plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init = this.init.bind(this);
        this.initSimplesheet = this.initSimplesheet.bind(this);
        this.updateSimplesheet = this.updateSimplesheet.bind(this);

        this.init();
    }

    Plugin.prototype = {

        init: function(id) {
            var _this = this;

            $(function () {
                _this.initSimplesheet();
            });
        },

        initSimplesheet: function() {
            var _this = this;

            const sheetContainer = document.getElementById(this.options.namespace + '-simplesheet');

            this.simplesheet = new Handsontable(sheetContainer, {
                startCols: 26,
                startRows: 100,
                colHeaders: true,
                contextMenu: true,
                dropdownMenu: true,
                rowHeaders: true,
                filters: true,
                manualColumnMove: true,
                manualRowMove: true,
                manualColumnResize: true,
                manualRowResize: true,
                tableClassName: 'simplesheet',
                width: '100%',
                height: '100%',
                stretchH: 'all',
                columnSorting: {
                    indicator: true,
                },
                licenseKey: 'non-commercial-and-evaluation',
            });

            // If there is existing data available, load it into the spreadsheet
            let sheetData = JSON.parse(document.getElementById(this.options.namespace).value);
            if (sheetData.data !== null) {
                _this.simplesheet.loadData(sheetData.data);
            }

            // Register our data change hooks after the Simplesheet instance has been created
            Handsontable.hooks.add('afterChange', this.updateSimplesheet);
            Handsontable.hooks.add('afterColumnMove', this.updateSimplesheet);
            Handsontable.hooks.add('afterRowMove', this.updateSimplesheet);
            Handsontable.hooks.add('afterRemoveCol', this.updateSimplesheet);
            Handsontable.hooks.add('afterRemoveRow', this.updateSimplesheet);
            Handsontable.hooks.add('afterCreateRow', this.updateSimplesheet);
            Handsontable.hooks.add('afterCreateCol', this.updateSimplesheet);
            Handsontable.hooks.add('afterColumnSort', this.updateSimplesheet);
            
            // If the user is currently editing a cell and the 'Save' keyboard
            // shortcut (ctrl+s/cmd+s) is pressed, we want to commit the current
            // cell changes.
            Craft.cp.on('beforeSaveShortcut', $.proxy(function (e, _this) {
                if (this.simplesheet.getActiveEditor() !== undefined) {
                    if (this.simplesheet.getActiveEditor().isOpened()) {
                        this.simplesheet.destroyEditor();
                        this.updateSimplesheet();
                    }
                }
            }, this));
        },

        /**
         * Syncs our hidden form field with the Simplesheet instance whenever a change
         * is made.
         */
        updateSimplesheet: function() {
            var _this = this;

            document.getElementById(this.options.namespace).value = JSON.stringify(
                { data: _this.simplesheet.getData() }
            );
        },

    };

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName,
                new Plugin( this, options ));
            }
        });
    };

})( jQuery, window, document );
