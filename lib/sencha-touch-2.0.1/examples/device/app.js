//<debug>
Ext.Loader.setPath({
    'Ext': '../../src'
});
//</debug>

Ext.application({
    name: 'Device',

    stores: ['Images'],

    views: [
        'Main',
        'Information',
        'Camera',
        'Connection',
        'Notification',
        'Orientation',
        'Geolocation',
        'Push'
    ],

    controllers: [
        'Application',
        'Camera',
        'Notification',
        'Connection',
        'Push'
    ],

    launch: function() {
        Ext.create('Device.view.Main');
    }
});
