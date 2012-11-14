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
            loginButton:{
                tap:'doLogin'
            }
        },
        refs: {
            settingsForm: 'formpanel[action=settings]',
            loginForm: 'formpanel[action=login]',
            loginWindow: 'loginWindow',
            loginButton: 'button[action=login]',
            pvtKeyField: 'textfield[action=pvtKey]'
        }
    },

    doLogin:function(){

        var me = this,
            values = this.getLoginForm().getValues();
        values = Ext.Object.merge(values, this.getSettingsForm().getValues(values));

        Ext.ns('App.data');
        App.data = {
//            url:values.url+'data/router.php',
            url:'http://192.168.1.100/gaiaehr/data/appRounter.php',
//            url:'http://www.gaiaehr.org/demo/data/router.php',
            type:'remoting',
            actions:{
                authProcedures:[
                    {
                        "name":"login",
                        "len":1
                    }
                ]
            }
        };
        Ext.direct.Manager.addProvider(App.data);

        authProcedures.login({authUser:'admin',authPass:'pass',lang:'en_US',site:App.app.server.site}, function(provider, response){
            say(provider);
            say(response);
        });

//        say(values);
//        Ext.Viewport.mask();
//        Ext.data.JsonP.request({
//            scope:me,
//            url: values.url+'data/restRouter.php',
//            params: values,
//            success: function(result, request) {
//                Ext.Viewport.unmask();
//                if(result.success){
//                    me.getLoginWindow().destroy();
//                    if(App.app.isPhone){
//                        Ext.Viewport.add(Ext.create('App.view.MainPhone'));
//                    }else{
//                        Ext.Viewport.add(Ext.create('App.view.MainTablet'));
//                    }
//                }else{
//                    Ext.Msg.alert('Oops!', Ext.String.capitalize(result.type)+': '+result.message, Ext.emptyFn);
//                }
//            },
//            failure:function(result, request){
//                Ext.Msg.alert('Oops!', Ext.String.capitalize(result.type)+': '+result.message, Ext.emptyFn);
//            }
//        });


     },

    setSettingsValues:function(){
        if(App.app.server){
//            this.getSettingsForm().hide();
            this.getSettingsForm().setValues(App.app.server);
        }
    }
});