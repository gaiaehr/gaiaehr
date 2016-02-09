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
Ext.define('App.ux.LiveImmunizationSearch', {
	extend: 'Ext.form.ComboBox',
	xtype: 'immunizationlivesearch',
	hideLabel: true,
	displayField: 'name',
	valueField: 'cvx_code',
	emptyText: _('immunization_search') + '...',
	typeAhead: true,
	minChars: 1,
	initComponent: function(){
		var me = this;

		Ext.define('liveImmunizationSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'cvx_code', type: 'string'},
				{name: 'name', type: 'string'},
				{name: 'description', type: 'string'},
				{name: 'note', type: 'string'},
				{name: 'status', type: 'string'},
				{name: 'update_date', type: 'date', dateFormat: 'Y-m-d H:i:s'}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'Immunizations.getImmunizationLiveSearch'
				},
				reader: {
					totalProperty: 'totals',
					root: 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'liveImmunizationSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store: me.store,
			listConfig: {
				loadingText: _('searching') + '...',
				getInnerTpl: function(){
					return '<div class="search-item">CVX - {cvx_code}: <span style="font-weight: normal;" class="list-status-{status}">{name} ({status})</span></div>';
				}
			},
			pageSize: 10
		});

		me.callParent();
	}
});
