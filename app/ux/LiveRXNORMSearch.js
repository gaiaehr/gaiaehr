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
Ext.define('App.ux.LiveRXNORMSearch',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.rxnormlivetsearch',
	hideLabel : true,

	initComponent : function()
	{
		var me = this;

		Ext.define('liveRXNORMSearchModel',
		{
			extend : 'Ext.data.Model',
			fields : [
				{name : 'RXCUI', type:'auto'},
				{name : 'CODE', type:'auto'},
				{name : 'STR', type:'auto'},
				{name : 'DST', type:'auto'},
				{name : 'DRT', type:'auto'},
				{name : 'DDF', type:'auto'},
				{name : 'DDFA', type:'auto'},
				{name : 'RXN_QUANTITY', type:'auto'},
				{name : 'SAB', type:'auto'},
				{name : 'RXAUI', type:'auto'}
			],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : Rxnorm.getRXNORMLiveSearch
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
			model : 'liveRXNORMSearchModel',
			pageSize : 10,
			autoLoad : false
		});

		Ext.apply(this,
		{
			store : me.store,
			displayField : 'STR',
			valueField : 'RXCUI',
			emptyText : i18n('search') + '...',
			typeAhead : false,
            hideTrigger : true,
			minChars : 3,
			listConfig :
			{
				loadingText : i18n('searching') + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl : function()
				{
					return '<div class="search-item"><h3>{STR}<span style="font-weight: normal"> ({RXCUI}) </span></h3></div>';
				}
			},
			pageSize : 10
		}, null);

		me.callParent();
	}
}); 