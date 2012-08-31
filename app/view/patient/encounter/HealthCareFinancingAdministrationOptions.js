/**
 * Created by JetBrains PhpStorm.
 * User: GaiaEHR
 * Date: 3/23/12
 * Time: 2:06 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.encounter.HealthCareFinancingAdministrationOptions', {
    extend:'Ext.form.Panel',
    alias:'widget.hcafaoptions',
    mixins: {
        functions: 'App.classes.AbstractPanel'
    },
    pid:null,
    eid:null,
    initComponent:function () {
        var me = this;


        me.listeners = {
            afterrender:me.afterPanelRender
        };
        me.callParent(arguments);
    },

    afterPanelRender:function(){
        this.mixins.functions.getFormItems(this, 10);
    }

});