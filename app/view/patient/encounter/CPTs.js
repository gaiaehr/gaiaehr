/**
 * Created by JetBrains PhpStorm.
 * User: GaiaEHR
 * Date: 3/23/12
 * Time: 2:06 AM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.patient.encounter.CPTs', {
    extend:'Ext.form.FieldSet',
    alias:'widget.cptsfieldset',
    title: i18n('services_live_search'),
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
            triggerTip: i18n('click_to_clear_selection'),
            qtip: i18n('clearable_combo_box'),
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