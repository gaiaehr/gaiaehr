/*!
 * Extensible 1.5.1
 * Copyright(c) 2010-2011 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */
Ext.Loader.setConfig({
    enabled: true,
    disableCaching: false,
    paths: {
        "Extensible": "../../src",
        "Extensible.example": ".."
    }
});

Ext.onReady(function() {

    var doit = function() {
        panel.removeAll();

        var store = Ext.create('Extensible.calendar.data.EventStore', {
            autoLoad: true,
            proxy: {
                type: 'rest',
                url: 'remote/php/app.php/events',
                noCache: false,

                reader: {
                    type: 'json',
                    root: 'data'
                }
            }
        });

        var cal = Ext.create('Extensible.calendar.CalendarPanel', {
            store: store,
            activeItem: 0,
            listeners: {
                afterrender: function() {
                    var date = new Date(2012, 0, 27, 0, 0, 0, 0);
                    cal.setStartDate(date);
                }
            }
        });

        panel.add(cal);
        panel.doLayout();
    }

    var panel = Ext.create('Ext.panel.Panel', {
        layout: 'fit',
        renderTo: Ext.getBody(),
        tbar: {
            items: [{
                text: 'doit',
                handler: doit
            }]
        },
        width: 800,
        height: 600
    });
});