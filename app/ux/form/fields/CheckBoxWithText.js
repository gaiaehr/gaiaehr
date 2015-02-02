/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @class Ext.ux.form.field.DateTime
 * @extends Ext.form.FieldContainer
 * @author atian25 (http://www.sencha.com/forum/member.php?51682-atian25)
 * @author ontho (http://www.sencha.com/forum/member.php?285806-ontho)
 * @author jakob.ketterl (http://www.sencha.com/forum/member.php?25102-jakob.ketterl)
 *
 */
Ext.define('App.ux.form.fields.CheckBoxWithText', {
	extend: 'Ext.form.FieldContainer',
	mixins: {
		field: 'Ext.form.field.Field'
	},
	xtype: 'checkboxwithtext',
	layout: 'hbox',
	boxLabel: 'boxLabel',
	emptyText: '',
	readOnly: false,
//	combineErrors: true,
	msgTarget: 'under',
	width: 400,

	inputValue: '1',
	uncheckedValue: '0',

	initComponent: function(){
		var me = this;

		me.items = me.items || [];

		me.items = [
			{
				xtype:'checkbox',
				boxLabel: me.boxLabel,
				submitValue: false,
				inputValue: me.inputValue,
				width: 130,
				margin: '0 10 0 0'
			}
		];

		me.textField = me.textField || {
			xtype:'textfield'
		};

		Ext.apply(me.textField , {
			submitValue: false,
			flex: 1,
			hidden: true,
			emptyText: me.emptyText
		});

		me.items.push(me.textField);

		if(me.layout == 'vbox') me.height = 44;

		me.callParent();

		me.chekboxField = me.items.items[0];
		me.textField = me.items.items[1];

		me.chekboxField.on('change', me.setTextField, me);

		// this dummy is necessary because Ext.Editor will not check whether an inputEl is present or not
//		this.inputEl = {
//			dom: {},
//			swallowEvent: function(){
//			}
//		};
//
		me.initField();
	},

	setTextField: function(checkbox, value){
		if(value == 0 || value == 'off' || value == false){
			this.textField.reset();
			this.textField.hide();
		}else{
			this.textField.show();
		}
	},

	getValue: function(){
		var value = '',
			ckValue = this.chekboxField.getSubmitValue(),
			txtValue = this.textField.getSubmitValue() || '';

		if(ckValue)    value = ckValue + '~' + txtValue;
		return value;
	},

	getSubmitValue: function(){
		return this.getValue();
	},

	setValue: function(value){
		if(value && value.split){
			var val = value.split('~');
			this.chekboxField.setValue(val[0] || 0);
			this.textField.setValue(val[1] || '');
			return;
		}
		this.chekboxField.setValue(0);
		this.textField.setValue('');
	},

	// Bug? A field-mixin submits the data from getValue, not getSubmitValue
	getSubmitData: function(){
		var me = this,
			data = null;
		if(!me.disabled && me.submitValue && !me.isFileUpload()){
			data = {};
			data[me.getName()] = '' + me.getSubmitValue();
		}
		return data;
	},

	setReadOnly: function(value){
		this.chekboxField.setReadOnly(value);
		this.textField.setReadOnly(value);
	}
});