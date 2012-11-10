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
        refs: {
            loginWindow: '[action="loginWindow"]',
            loginForm: '[action="loginForm"]'
        }
    },

    init:function(){
        console.log(this.getLoginWindow());
        console.log(this.getLoginForm());
    }


});