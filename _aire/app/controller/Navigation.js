/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/10/12
 * Time: 11:37 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.controller.Navigation', {
    extend: 'Ext.app.Controller',

    requires:['Ext.data.proxy.JsonP'],

    config: {
        control: {
            patienLlist: {
                select: 'onPatientListSelect'
            }
        },

        refs: {
            mainTabletView: 'maintabletview',
            mainPhoneView: 'mainphoneview',
            patienLlist: 'patientlist'
        }
    },


    onPatientListSelect: function(view, model){
        Ext.Msg.alert('Patient', model.data.name, Ext.emptyFn);

    }
});