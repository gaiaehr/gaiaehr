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

Ext.define('App.view.patient.encounter.AdministeredMedications', {
	extend: 'Ext.grid.Panel',
	requires: [
		'Ext.grid.plugin.RowEditing',
		'App.ux.LiveRXNORMSearch',
		'App.ux.LiveSigsSearch',
		'App.ux.LiveUserSearch',
		'App.ux.form.fields.DateTime'
	],
	xtype: 'administeredmedications',
	itemId: 'AdministeredMedicationsGrid',
	frame: true,
	store: Ext.create('App.store.patient.Medications', {
		autoSync: false
	}),
	columns: [
		{
			header: _('medication'),
			flex: 1,
			minWidth: 150,
			dataIndex: 'STR',
			editor: {
				xtype: 'rxnormlivetsearch',
				itemId: 'AdministeredMedicationsLiveSearch',
				displayField: 'STR',
				valueField: 'STR',
				action: 'medication',
				allowBlank: false
			},
			renderer: function(v, mets, record){
				var codes = '';
				if(record.data.RXCUI != ''){
					codes += ' <b>RxNorm:</b> ' + record.data.RXCUI;
				}
				if(record.data.NDC != ''){
					codes += ' <b>NDC:</b> ' + record.data.NDC;
				}
				codes = codes != '' ? (' (' + codes + ' )') : '';
				return v + codes;
			}
		},
		{
			text: _('directions'),
			dataIndex: 'directions',
			flex: 1,
			editor: {
				xtype: 'textfield'
			}
		},
		{
			text: _('administered_by'),
			dataIndex: 'administered_by',
			width: 150,
			editor: {
				xtype: 'userlivetsearch',
				acl: 'administer_medications',
				valueField: 'fullname',
				forceSelection: false,
				itemId: 'AdministeredMedicationsUserLiveSearch'
			}
		},
		{
			xtype: 'datecolumn',
			text: _('date'),
			dataIndex: 'administered_date',
			width: 120,
			format: g('date_time_display_format'),
			editor: {
				xtype: 'mitos.datetime'
			}
		}
	],
	plugins: Ext.create('Ext.grid.plugin.RowEditing', {
		autoCancel: false,
		errorSummary: false,
		clicksToEdit: 2
	}),
	tbar: [
		_('administered_medications'),
		'->',
		{
			text: _('medication'),
			itemId: 'AdministeredMedicationsAddBtn',
			action: 'encounterRecordAdd',
			iconCls: 'icoAdd'
		}
	]

});
