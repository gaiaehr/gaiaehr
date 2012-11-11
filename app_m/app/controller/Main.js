/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.Main', {
    extend: 'Ext.app.Controller',

    config: {
        refs: {
            loginWindow: 'contianer'
        }
    },

    init:function(){
        console.log(this.getLoginWindow());
        //this.getViewport().add(Ext.create('App.view.Login').show());
    }


});