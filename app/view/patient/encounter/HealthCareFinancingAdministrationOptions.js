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
    pid:null,
    eid:null,
    initComponent:function(){
        var me = this;
        me.callParent();
        me.loadHCFAForm();
    },
    loadHCFAForm:function(){
        var me = this;
        me.getFormItems(this, 10, function(panel){
            var formFields = panel.getForm().getFields(),
                modelFields =  [
                    {
                        name:'id',
                        type:'int'
                    },
                    {
                        name:'pid',
                        type:'int'
                    },
                    {
                        name:'eid',
                        type:'int'
                    },
                    {
                        name:'uid',
                        type:'int'
                    }
                ];
            for(var i = 0; i < formFields.items.length; i++){
                modelFields.push({
                    name:formFields.items[i].name,
                    type:'auto'
                });
            }
            Ext.define('App.model.patient.HCFAOptions', {
                extend:'Ext.data.Model',
                fields:modelFields,
                proxy:{
                    type:'direct',
                    api:{
                        update:Encounter.updateEncounterHCFAOptions
                    }
                },
                belongsTo:{
                    model:'App.model.patient.Encounter',
                    foreignKey:'eid'
                }
            });
        });
    }

});