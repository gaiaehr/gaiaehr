/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
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