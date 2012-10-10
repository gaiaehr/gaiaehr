//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

Ext.application({
    name: 'Twitter',
    requires: ['Twitter.proxy.Twitter'],

    profiles: ['Phone', 'Tablet'],
    models: ['Search', 'Tweet'],
    stores: ['Searches'],

    tabletStartupScreen: 'resources/loading/tablet_startup.png',
    phoneStartupScreen: 'resources/loading/phone_startup.png',

    icon: {
        57: 'resources/icons/icon.png',
        72: 'resources/icons/icon-72.png',
        114: 'resources/icons/icon-114.png'
    },

    launch: function() {
        Ext.getBody().removeCls('loading');
    }
});

