/*
 GaiaEHR (Electronic Health Records)
 Allergies.js
 Allergies Combo Box xtype
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
Ext.define('App.ux.combo.Allergies',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.mitos.allergiescombo',
	initComponent : function()
	{
		var me = this;

		Ext.define('AllergiesComboModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id',
				type : 'int'
			},
			{
				name : 'allergy_name'
			},
			{
				name : 'allergy_type',
				type : 'string'
			}],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : CombosData.getAllergiesByType
				}
			}
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'AllergiesComboModel',
			autoLoad : false
		});

		Ext.apply(this,
		{
			editable : false,
			queryMode : 'local',
			displayField : 'allergy_name',
			valueField : 'allergy_name',
			emptyText : _('select'),
			store : me.store
		}, null);
		me.callParent(arguments);
	}
}); 