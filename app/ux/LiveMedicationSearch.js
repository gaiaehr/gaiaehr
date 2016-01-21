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

Ext.define('App.ux.LiveMedicationSearch', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.medicationlivetsearch',

	initComponent: function(){
		var me = this;

		Ext.define('liveMedicationsSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{ name: 'id' },
				{ name: 'PROPRIETARYNAME' },
				{ name: 'PRODUCTNDC' },
				{ name: 'NONPROPRIETARYNAME' },
				{ name: 'ACTIVE_NUMERATOR_STRENGTH' },
				{ name: 'ACTIVE_INGRED_UNIT' }
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'Rxnorm.getRXNORMLiveSearch'
				},
				reader: {
					totalProperty: 'totals',
					root: 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'liveMedicationsSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store: me.store,
			displayField: 'PROPRIETARYNAME',
			valueField: 'id',
			emptyText: _('search_for_a_medication') + '...',
			typeAhead: false,
			hideTrigger: true,
			minChars: 1,
			listConfig: {
				loadingText: _('searching') + '...',
				getInnerTpl: function(){
					return '<div class="search-item"><h3>{PROPRIETARYNAME}<span style="font-weight: normal"> ({NONPROPRIETARYNAME}) </span></h3>{ACTIVE_NUMERATOR_STRENGTH} | {ACTIVE_INGRED_UNIT}</div>';
				}
			},
			pageSize: 10
		});

		me.callParent();
	}
});
