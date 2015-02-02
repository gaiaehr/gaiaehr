/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 11/1/11
 * Time: 12:37 PM
 */
Ext.define('App.ux.form.fields.MultiText', {
	extend: 'Ext.form.FieldContainer',
	xtype: 'multitextfield',
	layout: {
		type:'vbox',
		align: 'stretch'
	},
	name: null,
	numbers: true,
	initComponent: function(){
		var me = this;

		me.lastField = null;

		me.callParent();
		me.addField();
	},

	addField:function(value){
		var me = this;

		me.lastField = me.add({
			xtype:'textfield',
			name: '_' + me.name,
			anchor: '100%',
			value: value || '',
			labelWidth: 20,
			enableKeyEvents: true
		});
		if(me.numbers) me.lastField.setFieldLabel((me.items.items.indexOf(me.lastField) + 1).toString());
		me.lastField.on('keyup', this.onFieldKeyUp, this);

	},

	onFieldKeyUp:function(field, e){
		if(e.getKey() == e.TAB) return;

		var me = this,
			index = me.items.items.indexOf(field),
			totals =  me.items.items.length,
			isLast = (index + 1) == totals,
			isNextToLast = (index + 2) == totals;

		if(isLast && field.getValue().length > 0 && this.lastField == field && !(e.getKey() == e.DELETE || e.getKey() == e.BACKSPACE)){
			this.addField();
			me.doLayout();
		}else if(isNextToLast && field.getValue().length == 0 && me.lastField != field){
			me.remove(me.lastField);
			me.doLayout();
			me.lastField = field;
		}
	},

	setValue:function(data){
		var me = this;

		me.removeAll(true);
		if(Ext.isString(data)){
			me.addField(data);
		}else if(Ext.isArray(data)){
			for(var i=0; i < data.length; i++){
				me.addField(data[i]);
			}
		}
		me.addField(data[i]);
	},

	getValue:function(){
		var me = this,
			values = me.up('form').getForm().getValues()['_' + me.name];
		if(values[values.length - 1] == '') Ext.Array.erase(values, values.length - 1, 1);
		return values;
	}
});