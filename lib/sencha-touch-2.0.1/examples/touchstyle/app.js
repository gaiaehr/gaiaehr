//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

Ext.application({
    name: 'TouchStyle',

    profiles: ['Tablet', 'Phone'],
    stores: ['Categories'],

    phoneStartupScreen: 'resources/loading/Default.png',
    tabletStartupScreen: 'resources/loading/Default~ipad.png',

    icon: {
        57: 'resources/icons/icon.png',
        72: 'resources/icons/icon-72.png',
        114: 'resources/icons/icon-114.png'
    }
});
