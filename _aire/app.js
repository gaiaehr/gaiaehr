//<debug>
Ext.Loader.setPath({
    'Ext': 'touch/src',
    'Ext.ux': 'ux',
    'App': 'app'
});
//</debug>
Ext.application({
    name: 'App',

    requires: [
        'Ext.direct.*',
        'Ext.device.*',
        'Ext.List',
        'Ext.MessageBox',
        'Ext.plugin.ListPaging',
        'Ext.plugin.PullRefresh',
        'Ext.carousel.Carousel',
        'App.view.Login',

        'App.view.MainTabletView',
        'App.view.MainPhoneView'
    ],

    controllers: ['Main','Login','Navigation','PatientSummary'],

    views: ['Login', 'MainTabletView', 'MainPhoneView'],

    stores: ['Patients'],

    icon: {
        '57': 'resources/icons/Icon.png',
        '72': 'resources/icons/Icon~ipad.png',
        '114': 'resources/icons/Icon@2x.png',
        '144': 'resources/icons/Icon~ipad@2x.png'
    },

    isIconPrecomposed: true,

    startupImage: {
        '320x460': 'resources/startup/320x460.jpg',
        '640x920': 'resources/startup/640x920.png',
        '768x1004': 'resources/startup/768x1004.png',
        '748x1024': 'resources/startup/748x1024.png',
        '1536x2008': 'resources/startup/1536x2008.png',
        '1496x2048': 'resources/startup/1496x2048.png'
    },

    launch: function() {
        var me = this;
        App.db = null;
        App.isNative = false;
        App.isPhone = Ext.os.deviceType == 'Phone';

        App.MsgOk = function(title, msg, callback){
            if(App.isNative){
                navigator.notification.alert(msg,function(){
                    if(typeof callback == 'function') callback();
                }, title, 'Ok');
            }else{
                Ext.Msg.alert(title, msg, function(btn){
                    if(typeof callback == 'function') callback(btn);
                });
            }
        };

        App.MsgOkCancel = function(title, msg, callback){
            if(App.isNative){
                navigator.notification.confirm(msg, function(buttonIndex){
                    var btn = buttonIndex == 1 ? 'yes' :'no';
                    if(typeof callback == 'function') callback(btn);
                }, title);
            }else{
                Ext.Msg.confirm(title, msg, function(btn){
                    if(typeof callback == 'function') callback(btn);
                });
            }
        };

        // Destroy the #appLoadingIndicator element
        Ext.fly('appLoadingIndicator').destroy();

        document.addEventListener("deviceready", function(){
            App.isNative = true;
            App.db = window.openDatabase("Database", "1.0", "GaiaEHRAireData", 200000);
            me.getApplication().getController('App.controller.Login').setServerData();
        }, false);

        // Initialize the main view
        Ext.Viewport.add(Ext.create('App.view.Login',{
            border: !App.isPhone ? 5 : 0,
            style: !App.isPhone ? 'border-color: black; border-style: solid; border-radius: 5px' : '',
            modal: !App.isPhone,
            centered: !App.isPhone,
            width: App.isPhone ? '100%' : 520,
            height: App.isPhone ? '100%' : 440
        }));
    },

    onUpdated: function() {
        Ext.Msg.confirm(
            "Application Update",
            "This application has just successfully been updated to the latest version. Reload now?",
            function(buttonId) {
                if (buttonId === 'yes') {
                    window.location.reload();
                }
            }
        );
    }
});
