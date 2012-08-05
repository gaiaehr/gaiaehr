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
    title:'ICDs Live Sarch',
    padding:'10 15',
    margin:'0 0 3 0',
    layout:'anchor',
	requires: [ 'App.classes.LiveICDXSearch' ],
    initComponent:function () {
        var me = this;

        Ext.define('Ext.ux.CustomTrigger', {
            extend: 'Ext.form.field.Trigger',
            alias: 'widget.customtrigger',
            hideLabel    : true,
            triggerTip:'Click to clear selection.',
            qtip:'Clearable Combo Box',
            trigger1Class:'x-form-select-trigger',
            trigger2Class:'x-form-clear-trigger',

            onTriggerClick: function() {
                this.destroy();
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
    },


    removeIcds:function(){
        this.getIcdContainer().removeAll();
    },

    loadIcds:function(records){
        var me = this,
            field = me.getIcdLiveSearch();
        me.removeIcds();
        Ext.each(records, function(record){
            me.addIcd(record.code, record.code_text);
        });
        field.reset();
    },

    addIcd:function(code, text){
        this.getIcdContainer().add({
            xtype:'customtrigger',
            value:code,
            width:70,
            style:'float:left',
            name:'icdxCodes',
            listeners:{
                afterrender:function(btn){
                    Ext.create('Ext.tip.ToolTip', {
                        target: btn.id,
                        html: text
                    });
                    btn.setEditable(false);
                }
            }
        });
    },

    getIcdContainer:function(){
        return this.getComponent('idcsContainer');
    },

    getIcdLiveSearch:function(){
        return this.getComponent('liveicdxsearch');
    }

});