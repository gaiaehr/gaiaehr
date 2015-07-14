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
 */

Ext.define('App.ux.LiveSnomedSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.snomedlivesearch',
	hideLabel: true,
	displayField: 'FullySpecifiedName',
	valueField: 'ConceptId',
	initComponent: function(){
		var me = this;

		Ext.define('liveSnomedSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'ConceptId',
					type: 'string'
				},
				{
					name: 'FullySpecifiedName',
					type: 'string'
				},
				{
					name: 'CodeType',
					type: 'string',
					defaultValue: 'SNOMED'
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'SnomedCodes.liveCodeSearch'
				},
				reader: {
					totalProperty: 'totals',
					root: 'data'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'liveSnomedSearchModel',
			pageSize: 25,
			autoLoad: false
		});

		Ext.apply(this, {
			store: me.store,
			emptyText: _('search') + '...',
			typeAhead: false,
			hideTrigger: true,
			minChars: 3,
			listConfig: {
				loadingText: _('searching') + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl: function(){
					return '<div class="search-item"><h3>{FullySpecifiedName}<span style="font-weight: normal"> ({ConceptId}) </span></h3></div>';
				}
			},
			pageSize: 25
		});

		me.callParent();
	}
});