//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

/**
 * A demo application containing the Ext.Video component.
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
        'Ext.Panel',
        'Ext.Video'
    ],

    launch: function() {
        Ext.Viewport.add({
            xtype: 'video',
            url: [
                'resources/media/BigBuck.m4v',
                'resources/media/BigBuck.webm'
            ],
            loop: true,
            posterUrl: 'resources/images/cover.jpg'
        });
    }
});
