/**
 * Created by JetBrains PhpStorm.
 * User: GaiaEHR
 * Date: 3/23/12
 * Time: 2:06 AM
 * To change this template use File | Settings | File Templates.
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