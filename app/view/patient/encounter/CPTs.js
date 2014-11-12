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

Ext.define('App.view.patient.encounter.CPTs', {
    extend:'Ext.form.FieldSet',
    alias:'widget.cptsfieldset',
    title: _('services_live_search'),
    padding:'10 15',
    margin:'0 0 3 0',
    layout:'anchor',
	requires: [ 'App.ux.LiveCPTSearch' ],
    autoFormSync:true,
    initComponent:function () {
        var me = this;

        Ext.define('Ext.ux.CustomTrigger', {
            extend: 'Ext.form.field.Trigger',
            alias: 'widget.customtrigger',
            hideLabel    : true,
            triggerTip: _('click_to_clear_selection'),
            qtip: _('clearable_combo_box'),
            trigger1Class:'x-form-select-trigger',
            trigger2Class:'x-form-clear-trigger',

            onTriggerClick: function() {
                this.destroy();
                if(me.autoFormSync) me.syncFormStore();
            },

            onRender:function (ct, position) {
                this.callParent(arguments);
                var id = this.getId();
                this.triggerConfig = {
                    tag:'div', cls:'x-form-twin-triggers', style:'display:block;', cn:[
                        {tag:"img", style:Ext.isIE ? 'margin-left:0;height:21px' : '', src:Ext.BLANK_IMAGE_URL, id:"trigger2" + id, name:"trigger2" + id, cls:"x-form-trigger " + this.trigger2Class}
                    ]};
                this.triggerEl.replaceWith(this.triggerConfig);
                this.triggerEl.on('mouseup', function () {
                        this.onTriggerClick()
                    },
                    this);
                var trigger2 = Ext.get("trigger2" + id);
                trigger2.addClsOnOver('x-form-trigger-over');
            }
        });

        me.items = [
            {
                xtype:'livecptsearch',
                itemId:'livesearch',
                emptyText:me.emptyText,
                name:'cptCodes',
                listeners:{
                    scope:me,
                    select:me.onLiveCodeSelect,
                    blur:function(field){
                        field.reset();
                    }
                }
            },
            {
                xtype:'container',
                itemId:'codesContainer',
                action:'codesContainer'
                //manageOverflow:1
            }
        ];
        me.callParent(arguments);
    },

    syncFormStore:function(){
        var form = this.up('form').getForm(),
            record = form.getRecord(),
            store = record.store;
        record.set(form.getValues());
        store.sync();
    },

    onLiveCodeSelect:function(field, model){
        this.addCode(model[0].data.code, model[0].data.code_text);
        field.reset();
        if(this.autoFormSync) this.syncFormStore();
    },


    removeCodes:function(){
        this.getCodesContainer().removeAll();
    },

    loadCodes:function(records){
        var me = this,
            field = me.getIcdLiveSearch();
        me.removeCodes();
        for(var i=0; i < records.length; i++){
            me.addCode(records[i].code, records[i].long_desc);
        }
        field.reset();
    },

    addCode:function(code, toolTip){
        var me = this;
        me.getCodesContainer().add({
            xtype:'customtrigger',
            value:code,
            width:100,
            style:'float:left',
            margin:'0 5 0 0',
            name:me.name,
            listeners:{
                afterrender:function(btn){
                    Ext.create('Ext.tip.ToolTip', {
                        target: btn.id,
                        html: toolTip
                    });
                    btn.setEditable(false);
                }
            }
        });
    },

    getCodesContainer:function(){
        return this.getComponent('codesContainer');
    },

    getIcdLiveSearch:function(){
        return this.getComponent('livesearch');
    }

});