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

Ext.define('App.ux.combo.EncounterSupervisors', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.encountersupervisorscombo',
	initComponent: function(){
		var me = this;

		Ext.define('EncounterSupervisorsComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'option_name',
					type: 'string'
				},
				{
					name: 'option_value',
					type: 'int'
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'CombosData.getEncounterSupervisors'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'EncounterSupervisorsComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable: false,
			queryMode: 'local',
			displayField: 'option_name',
			valueField: 'option_value',
			emptyText: i18n('select'),
			store: me.store
		});

		me.callParent(arguments);
	}
}); 