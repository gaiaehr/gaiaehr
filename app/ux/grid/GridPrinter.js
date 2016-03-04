/**
 * @class GetIt.GridPrinter
 * @author Ed Spencer (edward@domine.co.uk)
 * Helper class to easily print the contents of a grid. Will open a new window with a table where the first row
 * contains the headings from your column model, and with a row for each item in your grid's store. When formatted
 * with appropriate CSS it should look very similar to a default grid. If renderers are specified in your column
 * model, they will be used in creating the table. Override headerTpl and bodyTpl to change how the markup is generated
 *
 * Usage:
 *
 * var grid = new Ext.grid.GridPanel({
 *   colModel: //some column model,
 *   store   : //some store
 * });
 *
 * Ext.ux.GridPrinter.print(grid);
 *
 */
Ext.ux.GridPrinter = {
    /**
     * Prints the passed grid. Reflects on the grid's column model to build a table, and fills it using the store
     * @param {Ext.grid.GridPanel} grid The grid to print
     */
    print: function(grid) {
        //We generate an XTemplate here by using 2 intermediary XTemplates - one to create the header,
        //the other to create the body (see the escaped {} below)
        var columns = grid.getColumnModel().config;

        //build a useable array of store data for the XTemplate
        var data = [];
        grid.store.data.each(function(item) {
            var convertedData = [];

            //apply renderers from column model
            for (var key in item.data) {
                var value = item.data[key];

                Ext.each(columns, function(column) {
                    if (column.dataIndex == key) {
                        convertedData[key] = column.renderer ? column.renderer(value) : value;
                    }
                }, this);
            }

            data.push(convertedData);
        });

        //use the headerTpl and bodyTpl XTemplates to create the main XTemplate below
        var headings = Ext.ux.GridPrinter.headerTpl.apply(columns);
        var body     = Ext.ux.GridPrinter.bodyTpl.apply(columns);

        var html = new Ext.XTemplate(
            '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
            '<html>',
            '<head>',
            '<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />',
            '<link href="' + Ext.ux.GridPrinter.stylesheetPath + '" rel="stylesheet" type="text/css" media="screen,print" />',
            '<title>' + grid.getTitle() + '</title>',
            '</head>',
            '<body>',
            '<table>',
            headings,
            '<tpl for=".">',
            body,
            '</tpl>',
            '</table>',
            '</body>',
            '</html>'
        ).apply(data);

        //open up a new printing window, write to it, print it and close
        var win = window.open('', 'printgrid');

        win.document.write(html);

        win.print();
        win.close();
    },

    /**
     * @property stylesheetPath
     * @type String
     * The path at which the print stylesheet can be found (defaults to '/stylesheets/print.css')
     */
    stylesheetPath: '/stylesheets/print.css',

    /**
     * @property headerTpl
     * @type Ext.XTemplate
     * The XTemplate used to create the headings row. By default this just uses <th> elements, override to provide your own
     */
    headerTpl:  new Ext.XTemplate(
        '<tr>',
        '<tpl for=".">',
        '<th>{header}</th>',
        '</tpl>',
        '</tr>'
    ),

    /**
     * @property bodyTpl
     * @type Ext.XTemplate
     * The XTemplate used to create each row. This is used inside the 'print' function to build another XTemplate, to which the data
     * are then applied (see the escaped dataIndex attribute here - this ends up as "{dataIndex}")
     */
    bodyTpl:  new Ext.XTemplate(
        '<tr>',
        '<tpl for=".">',
        '<td>\{{dataIndex}\}</td>',
        '</tpl>',
        '</tr>'
    )
};
