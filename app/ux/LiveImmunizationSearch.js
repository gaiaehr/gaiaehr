/*
 GaiaEHR (Electronic Health Records)
 LiveImmunizationSearch.js
 UX
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
Ext.define('App.ux.LiveImmunizationSearch',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.immunizationlivesearch',
	hideLabel : true,

	initComponent : function()
	{
		var me = this;

		Ext.define('liveImmunizationSearchModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id',
				type : 'int'
			},
			{
				name : 'cvx_code',
				type : 'string'
			},
			{
				name : 'name',
				type : 'string'
			},
			{
				name : 'description',
				type : 'string'
			},
			{
				name : 'note',
				type : 'string'
			},
			{
				name : 'update_date',
				type : 'date',
				dateFormat : 'Y-m-d H:i:s'
			}],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : Immunizations.getImmunizationLiveSearch
				},
				reader :
				{
					totalProperty : 'totals',
					root : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'liveImmunizationSearchModel',
			pageSize : 10,
			autoLoad : false
		});

		Ext.apply(this,
		{
			store : me.store,
			displayField : 'name',
			valueField : 'cvx_code',
			emptyText : i18n('search_for_a_immunizations') + '...',
			typeAhead : true,
			minChars : 1,
			listConfig :
			{
				loadingText : i18n('searching') + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl : function()
				{
					return '<div class="search-item">CVX - {cvx_code}: <span style="font-weight: normal">{name}</span></div>';
				}
			},
			pageSize : 10
		}, null);

		me.callParent();
	}
}); 