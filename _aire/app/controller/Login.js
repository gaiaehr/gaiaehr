/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.Login', {
    extend: 'Ext.app.Controller',
    config: {
        control: {
            settingsForm: {
                initialize: 'setSettingsValues'
            },
            loginButton: {
                tap: 'doLogin'
            },
            logoutButton: {
                tap: 'doLogout'
            }
        },
        refs: {
            mainPhoneView: 'mainphoneview',
            mainTabletView: 'maintabletview',
            settingsForm: 'formpanel[action=settings]',
            loginForm: 'formpanel[action=login]',
            loginWindow: 'loginWindow',
            loginButton: 'button[action=login]',
            logoutButton: 'button[action=logout]',
            pvtKeyField: 'textfield[action=pvtKey]'
        }
    },
    doLogin: function(){



        var me = this,
            values = this.getLoginForm().getValues(),
            server = this.getSettingsForm().getValues();
        values.site = server.site;
        App.app.server = server;
        Ext.Viewport.mask({xtype: 'loadmask', message: 'Be Right Back!'});

        Ext.direct.Manager.addProvider({
            id:'ServerProvider',
            url: server.url+'/data/appRounter.php',
            type: 'remoting',
            namespace : 'DataProvider',
            actions: {
                authProcedures: [
                    {
                        "name": "login",
                        "len": 1
                    }
                ],
                PoolArea: [
                    {
                        "name": "getPatientsByPoolAreaAccess",
                        "len": 0
                    }
                ]
           }
        });

        DataProvider.authProcedures.login(values, function(response){
            Ext.Viewport.unmask();
            if(response.success){
                App.app.server = server;
                App.app.server.token = response.token;
                me.getLoginWindow().destroy();
                if(App.app.isPhone){
                    Ext.Viewport.add(Ext.create('App.view.MainPhone'));
                }else{
                    Ext.Viewport.add(Ext.create('App.view.MainTablet'));
                }
            }else{
                App.MsgOk('Oops!', Ext.String.capitalize(response.type) + ': ' + response.message, Ext.emptyFn);
            }
        });
    },
    doLogout:function(){
        var me = this;
        App.MsgOkCancel('Logout...', 'Are you sure?', function(btn){
            if(btn == 'yes'){
                if(App.app.isPhone){
                    Ext.Viewport.remove(me.getMainPhoneView());
                }else{
                    Ext.Viewport.remove(me.getMainTabletView());
                }
                Ext.Viewport.add(Ext.create('App.view.Login',{
                    border: !App.app.isPhone ? 5 : 0,
                    style: !App.app.isPhone ? 'border-color: black; border-style: solid; border-radius: 5px' : '',
                    modal: !App.app.isPhone,
                    centered: !App.app.isPhone,
                    width: App.app.isPhone ? '100%' : 520,
                    height: App.app.isPhone ? '100%' : 440
                }));
            }
        });
    },
    setSettingsValues: function(){
        if(App.app.server){
            this.getSettingsForm().setValues(App.app.server);
        }
    }
});