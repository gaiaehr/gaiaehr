//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

Ext.application({
    name: 'GeoCon',
    icon: 'resources/icons/icon.png',
    glossOnIcon: false,
    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    models: [
        'Bill',
        'Committee',
        'Legislator',
        'Vote'
    ],

    views : [
        'Main'
    ],

    controllers: [
        'SplashScreen',
        'Legislator',
        'Committee'
    ],

    stores: [
        'Bills',
        'Legislators',
        'Committees',
        'Votes',
        'States',
        'Districts'
    ],

    launch: function() {
        Ext.create('GeoCon.view.Main');
    }
});

