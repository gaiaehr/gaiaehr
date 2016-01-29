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

Ext.define('App.ux.LiveSnomedProcedureSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.snomedliveproceduresearch',
	hideLabel: true,
	displayField: 'FullySpecifiedName',
	valueField: 'ConceptId',
	emptyText: _('search') + '...',
	typeAhead: false,
	hideTrigger: true,
	minChars: 3,
	initComponent: function(){
		var me = this;

		Ext.define('liveSnomedProcedureSearchModel', {
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
					defaultValue: 'SNOMED-CT'
				},
				{
					name: 'Occurrence',
					type: 'int'
				}
			],
			idProperty: 'ConceptId',
			proxy: {
				type: 'direct',
				api: {
					read: 'SnomedCodes.liveProcedureCodeSearch',
					update: 'SnomedCodes.updateLiveProcedureCodeSearch'
				},
				reader: {
					totalProperty: 'totals',
					root: 'data'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'liveSnomedProcedureSearchModel',
			pageSize: 25,
			autoLoad: false
		});

		Ext.apply(this, {
			store: me.store,
			listConfig: {
				loadingText: _('searching') + '...',
				getInnerTpl: function(){
					return '<div class="search-item"><h3>{FullySpecifiedName}<span style="font-weight: normal"> ({ConceptId}) </span></h3></div>';
				}
			},
			pageSize: 25
		});

		me.callParent();

		me.on('select', function(cmb, records){
			records[0].set({
				Occurrence: records[0].data.Occurrence + 1
			});
			records[0].save();
		});
	}
});
