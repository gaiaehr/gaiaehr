//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

/**
 * A simple demo showing how to overlay floating components using Sencha Touch.
 */
Ext.application({
    glossOnIcon: false,
    icon: {
        57: 'resources/icons/icon.png',
        72: 'resources/icons/icon@72.png',
        114: 'resources/icons/icon@2x.png',
        144: 'resources/icons/icon@114.png'
    },

    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    requires: [
        'Ext.Button',
        'Ext.Panel',
        'Ext.Toolbar'
    ],

    launch:function () {
        var isPhone = Ext.os.deviceType == 'Phone',
            overlay;

        // Here we create the base of our example, which is a simple toolbar at the bottom with 2 buttons
        Ext.Viewport.add({
            layout:{
                type: 'vbox',
                pack: 'center',
                align: 'stretch'
            },
            items: [
                {
                    html: "<center>Test the overlay by using the buttons below.</center>"
                },
                {
                    xtype: 'toolbar',
                    docked: 'bottom',

                    // Insert some buttons and space them out
                    items: [
                        { text:'Show' },
                        { flex:1, xtype:'component' },
                        { text:'Show' },
                        { flex:1, xtype: 'component' },
                        { text:'Show' }
                    ]
                }
            ]
        });

        overlay = Ext.Viewport.add({
            xtype: 'panel',

            // We give it a left and top property to make it floating by default
            left: 0,
            top: 0,

            // Make it modal so you can click the mask to hide the overlay
            modal: true,
            hideOnMaskTap: true,

            // Make it hidden by default
            hidden: true,

            // Set the width and height of the panel
            width: isPhone ? 260 : 400,
            height: isPhone ? '70%' : 400,

            // Here we specify the #id of the element we created in `index.html`
            contentEl: 'content',

            // Style the content and make it scrollable
            styleHtmlContent: true,
            scrollable: true,

            // Insert a title docked at the top with a title
            items: [
                {
                    docked: 'top',
                    xtype: 'toolbar',
                    title: 'Overlay Title'
                }
            ]
        });

        // Add a new listener onto the viewport with a delegate of the `button` xtype. This adds a listener onto every
        // button within the Viewport, which includes the buttons we added in the toolbar above.
        Ext.Viewport.on({
            delegate: 'button',
            tap: function(button) {
                // When you tap on a button, we want to show the overlay by the button we just tapped.
                overlay.showBy(button);
            }
        });
    }
});
