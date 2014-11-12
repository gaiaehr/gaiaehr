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
Ext.define('App.ux.form.fields.CheckBoxWithFamilyRelation', {
	extend: 'App.ux.form.fields.CheckBoxWithText',
	alias: 'widget.checkboxwithfamilyhistory',
	textField: {
		xtype: 'gaiaehr.combo',
		fieldLabel: _('relation'),
		labelAlign: 'right',
		labelWidth: 80,
		list: 109,
		allowBlank: false,
		loadStore: true
	},

	initComponent:function(){
		this.inputValue = this.code || '1';
		this.callParent();
	},

	getValue: function(){
		var value = '',
			ckValue = this.chekboxField.getSubmitValue(),
			txtValue;

		if(ckValue != '0'){
			var store = this.textField.getStore(),
				rec = store.getById(this.textField.getSubmitValue());
			txtValue = rec ? rec.data.code_type + ':' + rec.data.code : '0';
		}else{
			txtValue = '0';
		}

		if(ckValue)    value = ckValue + '~' + txtValue;
		return value;
	},

	setValue: function(value){

		if(value && value.split){
			var val = value.split('~');
			this.chekboxField.setValue(val[0] || 0);

			if(val[1] != '0' && val[1].split){
				var relation = val[1].split(':');
				this.textField.select(relation[1] || relation[0] || '');
			}else{
				this.textField.setValue('');
			}

			return;
		}
		this.chekboxField.setValue(0);
		this.textField.setValue('');
	}
});