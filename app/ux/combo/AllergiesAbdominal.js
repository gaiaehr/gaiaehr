/*
 GaiaEHR (Electronic Health Records)
 AllergiesAbdominal.js
 Allergies Abdominal Combo Box xtype
 Copyright (C) 2012 Ernesto Rodriguez

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
Ext.define('App.ux.combo.AllergiesAbdominal',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.mitos.allergiesabdominalcombo',
	initComponent : function()
	{
		var me = this;

		Ext.define('allergiesabdominalModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'option_name',
				type : 'string'
			},
			{
				name : 'option_value',
				type : 'string'
			}],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : CombosData.getOptionsByListId
				},
				extraParams :
				{
					list_id : 82
				}
			}
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'allergiesabdominalModel',
			autoLoad : true
		});

		Ext.apply(this,
		{
			editable : false,
			queryMode : 'local',
			displayField : 'option_name',
			valueField : 'option_value',
			emptyText : i18n('select'),
			store : me.store
		}, null);
		me.callParent(arguments);
	}
}); 