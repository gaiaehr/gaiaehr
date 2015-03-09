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

Ext.define('App.ux.combo.Facilities', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.mitos.facilitiescombo',
	editable: false,
	queryMode: 'local',
	valueField: 'id',
	displayField: 'name',
	emptyText: _('select'),
	initComponent: function(){
		var me = this;

		Ext.define('FacilitiesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'name', type: 'string'}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'CombosData.getFacilities'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'FacilitiesComboModel',
			autoLoad: true
		});

		me.callParent(arguments);
	}
});