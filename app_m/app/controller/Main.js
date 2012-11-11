/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.Main', {
    extend: 'Ext.app.Controller',

    requires:['Ext.data.proxy.JsonP'],

    config: {
        control: {
            loginButton: {
                tap: 'doLogin'
            },
            logoutButton: {
                tap: 'doLogout'
            }
        },

        refs: {
            loginWindow: 'loginWindow',
            loginButton: 'button[action=login]',
            logoutButton: 'button[action=logout]'
        }
    },


    doLogin:function(){
        this.getLoginWindow().hide();
        if(App.app.isPhone){
            Ext.Viewport.add(Ext.create('App.view.MainPhone'));
        }else{
            Ext.Viewport.add(Ext.create('App.view.MainTablet'));
        }
    }
});