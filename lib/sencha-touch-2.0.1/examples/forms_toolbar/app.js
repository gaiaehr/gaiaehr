//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

/**
 * This is a simple demonstration of using form fields inside toolbar components.
 */

// Define our application
Ext.application({
    // Setup your icon and startup screens
    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    glossOnIcon: false,
    icon: {
        57: 'resources/icons/icon.png',
        72: 'resources/icons/icon@72.png',
        114: 'resources/icons/icon@2x.png',
        144: 'resources/icons/icon@114.png'
    },

    // Require any components we will use in our example
    requires: [
        'Ext.field.Text',
        'Ext.field.Search',
        'Ext.field.Select',
        'Ext.Button'
    ],

    /**
     * The launch method is called when the browser is ready, and the application can launch.
     *
     * In this method we will create 3 fields: text, search and select. Will will insert each of these
     * fields into a toolbar and display them in the Viewport.
     */
    launch: function() {
        var textField, searchField, selectField;

        // Create a text field with a name and palceholder
        textField = Ext.create('Ext.field.Text', {
            name: 'name',
            placeHolder: 'Text'
        });

        // Create a search field with a name and a placeholder
        searchField = Ext.create('Ext.field.Search', {
            name: 'search',
            placeHolder: 'Search'
        });

        // Create a select field with a name and some options
        selectField = Ext.create('Ext.field.Select', {
            name: 'options',
            options: [
                { text: 'Option 1 should be very very very long',  value: '1' },
                { text: 'Option 2', value: '2' }
            ]
        });

        // Add a new item into the Viewport. This item will container our toolbars.
        Ext.Viewport.add({
            // Some html to explain the demo, and style the HTML
            html: 'This is a simple example of fields within toolbars.',
            styleHtmlContent: true,

            // Give this container details for each of the items added. This means we don't have to
            // set the xtype for each item.
            defaults: {
                xtype: 'toolbar'
            },

            // Add threee toolbars, each containing a field and a spacer at each side so the field is
            // centered
            items: [
                {
                    // Dock the first toolbar at the top
                    docked: 'top',
                    items: [
                        { xtype: 'spacer' },
                        textField,
                        { xtype: 'spacer' }
                    ]
                },
                {
                    // Dock the second toolbar at the top
                    docked: 'top',
                    ui: 'light',
                    items: [
                        { xtype: 'spacer' },
                        searchField,
                        { xtype: 'spacer' }
                    ]
                },
                {
                    // Dock the bottom toolbar at the top
                    docked: 'bottom',
                    ui: 'light',
                    items: [
                        { xtype: 'spacer' },
                        selectField,
                        { xtype: 'spacer' }
                    ]
                }
            ]
        });
    }
});

