/**
 *
 */
Ext.define('Device.view.Information', {
    extend: 'Ext.Container',

    requires: [
        'Ext.device.Device'
    ],

    config: {
        cls: 'information',
        scrollable: 'vertical',
        styleHtmlContent: true,
        items: [
            {
                html: 'This is a simple example showing some of the Device APIs available in Sencha Touch 2.<br/><br/>All of our device APIs work in a browser, in the Sencha Native Packager and PhoneGap.'
            },
            {
                cls: 'device-information',
                id: 'deviceInformation',
                tpl: '<strong>Name:</strong> {name}<br /><strong>UUID:</strong> {uuid}<br /><strong>Platform:</strong> {platform}'
            }
        ]
    },

    initialize: function() {
        var uuid = Ext.device.Device.uuid;
        if (uuid.length > 10) {
            uuid = uuid.substring(0, 16) + '...';
        }

        Ext.getCmp('deviceInformation').setData({
            name: Ext.device.Device.name,
            uuid: uuid,
            platform: Ext.device.Device.platform
        });
    }
});
