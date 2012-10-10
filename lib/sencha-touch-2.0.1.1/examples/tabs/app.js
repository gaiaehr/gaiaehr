//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

/**
 * This is a simple example that demonstrates the Ext.TabPanel component.
 *
 * It will simply create a Ext.tab.Panel component with three children and add it to the viewport.
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

    //next we require any components we are using in our application.
    requires: [
        'Ext.tab.Panel'
    ],

    /**
     * The launch function is called when the browser and the framework is ready
     * for the application to launch.
     *
     * All we are going to do is create a simple tabpanel with 3 items, and add
     * it to the global Ext.Viewport instance.
     */
    launch: function() {
        //we send a config block into the Ext.Viewport.add method which will
        //create our tabpanel
        Ext.Viewport.add({
            //first we define the xtype, which is tabpanel for the Tab Panel component
            xtype: 'tabpanel',

            //next we define the items that will appear inside our tab panel
            items: [
                {
                    //each item in a tabpanel requires the title configuration. this is displayed
                    //on the tab for this item
                    title: 'Tab 1',

                    //next we give it some simple html
                    items: {
                        html: '1',
                        centered: true
                    },

                    //then a custom cls so we can style it
                    cls: 'card1'
                },
                {
                    //title
                    title: 'Tab 2',

                    //the items html
                    items: {
                        html: '2',
                        centered: true
                    },

                    //custom cls
                    cls: 'card2'
                },
                {
                    //title
                    title: 'Tab 3',

                    //the items html
                    items: {
                        html: '3',
                        centered: true
                    },

                    //custom cls
                    cls: 'card3'
                }
            ]
        });
    }
});
