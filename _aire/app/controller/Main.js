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
//            patienLlist: {
//                show: 'onMainPanelShow'
//            }
        },

        refs: {
            mainTabletView: 'maintabletview',
            mainPhoneView: 'mainphoneview',
            patienLlist: 'patientlist'
        }
    },


    onMainPanelShow: function(){
        //this.getPatienLlist().getStore().load();
    }
});