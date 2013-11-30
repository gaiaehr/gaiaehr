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

Ext.define('App.view.patient.encounter.ICDs', {
    extend:'Ext.form.FieldSet',
    alias:'widget.icdsfieldset',
    title: i18n('icds_live_search'),
    padding:'10 15',
    margin:'0 0 3 0',
    layout:'anchor',
	requires: [ 'App.ux.LiveICDXSearch' ],
    autoFormSync:true,
    initComponent:function () {
        var me = this;

        me.items = [
            {
                xtype:'liveicdxsearch',
                itemId:'liveicdxsearch',
                emptyText:me.emptyText,
                name:'icdxCodes',
                listeners:{
                    scope:me,
                    select:me.onLiveIcdSelect,
                    blur:function(field){
                        field.reset();
                    }
                }
            },
            {
                xtype:'container',
                itemId:'idcsContainer',
                action:'idcsContainer'
                //manageOverflow:1
            }
        ];

        me.callParent(arguments);
    },

    onLiveIcdSelect:function(field, model){
        this.addIcd(model[0].data.code, model[0].data.code_text);
        field.reset();
        if(this.autoFormSync) this.syncFormStore();
    },


    removeIcds:function(){
        this.getIcdContainer().removeAll(false);
    },

    loadIcds:function(records){
        var me = this;
        me.removeIcds();
		me.loading = true;
	    for(var i=0; i < records.length; i++){
            me.addIcd(records[i].code, records[i].long_desc);
        }
	    me.loading = false;
	    me.getIcdLiveSearch().reset();
    },

    addIcd:function(code, toolTip){
	    var me = this;
	    me.getIcdContainer().add({
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
                },
	            destroy:function(){
		            if(me.autoFormSync && !me.loading) me.syncFormStore();
	            }
            }
        });
    },

	syncFormStore:function(){
		var form = this.up('form').getForm(),
			record = form.getRecord(),
			store = record.store;
		record.set(form.getValues());
		store.sync();
	},

    getIcdContainer:function(){
        return this.getComponent('idcsContainer');
    },

    getIcdLiveSearch:function(){
        return this.getComponent('liveicdxsearch');
    }

});