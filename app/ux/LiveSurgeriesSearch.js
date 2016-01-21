/*
 GaiaEHR (Electronic Health Records)
 LiveSurgeriesSearch.js
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
Ext.define('App.ux.LiveSurgeriesSearch',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.surgerieslivetsearch',
	hideLabel : true,

	initComponent : function()
	{
		var me = this;

		Ext.define('liveSurgeriesSearchModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id'
			},
			{
				name : 'type'
			},
			{
				name : 'type_num'
			},
			{
				name : 'surgery'
			}],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : Medical.getSurgeriesLiveSearch
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
			model : 'liveSurgeriesSearchModel',
			pageSize : 10,
			autoLoad : false
		});

		Ext.apply(this,
		{
			store : me.store,
			displayField : 'surgery',
			valueField : 'id',
			emptyText : i18n('search_for_a_surgery') + '...',
			typeAhead : false,
            hideTrigger : true,
			minChars : 1,
			listConfig :
			{
				loadingText : i18n('searching') + '...',
				getInnerTpl : function()
				{
					return '<div class="search-item"><h3>{surgery}<span style="font-weight: normal"> ({type}) </span></h3></div>';
				}
			},
			pageSize : 10
		});

		me.callParent();
	}
});
