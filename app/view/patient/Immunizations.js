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

Ext.define('App.view.patient.Immunizations', {
	extend: 'Ext.panel.Panel',
	requires: [
		'App.ux.combo.CVXManufacturersForCvx',
		'App.ux.LiveImmunizationSearch',
		'App.ux.grid.RowFormEditing',
		'App.store.patient.CVXCodes',
		'App.ux.form.fields.DateTime'
	],
	xtype: 'patientimmunizationspanel',
	title: i18n('immunizations'),
	layout:'border',
	border:false,
	items:[
		{
			xtype: 'grid',
			region:'center',
			itemId: 'patientImmunizationsGrid',
			selModel: Ext.create('Ext.selection.CheckboxModel'),
			columnLines: true,
			store: this.store = Ext.create('App.store.patient.PatientImmunization', {
				groupField: 'vaccine_name',
				sorters: [
					'vaccine_name',
					'administered_date'
				],
				remoteFilter: true,
				autoSync: false
			}),
			features: Ext.create('Ext.grid.feature.Grouping', {
				groupHeaderTpl: i18n('immunization') + ': {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})'
			}),
			columns: [
				{
					text: i18n('code'),
					dataIndex: 'code',
					width: 50,
					renderer:function(v, meta, record){

						say(record.data);

						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					text: i18n('immunization_name'),
					dataIndex: 'vaccine_name',
					flex: 1,
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					text: i18n('lot_number'),
					dataIndex: 'lot_number',
					width: 100,
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					text: i18n('amount'),
					dataIndex: 'administer_amount',
					width: 100,
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					text: i18n('units'),
					dataIndex: 'administer_units',
					width: 100,
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					text: i18n('notes'),
					dataIndex: 'note',
					flex: 1,
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					text: i18n('administered_by'),
					dataIndex: 'administered_by',
					width: 150,
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				},
				{
					xtype: 'datecolumn',
					text: i18n('date'),
					format: 'Y-m-d',
					width: 100,
					dataIndex: 'administered_date',
					renderer:function(v, meta, record){
						if(!record.data.is_error) return v;
						return '<span class="is_error_data">' + v + '</span>'
					}
				}
			],
			plugins: Ext.create('App.ux.grid.RowFormEditing', {
				autoCancel: false,
				errorSummary: false,
				clicksToEdit: 2,
				items: [
					{
						xtype: 'container',
						layout: 'hbox',
						items:[
							{
								xtype: 'container',
								layout: 'vbox',
								items: [
									{
										/**
										 * Line one
										 */
										xtype: 'fieldcontainer',
										layout: 'hbox',
										itemId: 'line1',
										defaults: {
											margin: '0 10 0 0',
											xtype: 'textfield'
										},
										items: [
											{
												xtype: 'immunizationlivesearch',
												itemId: 'immunizationsearch',
												fieldLabel: i18n('name'),
												name: 'vaccine_name',
												valueField:'name',
												hideLabel: false,
												allowBlank: false,
												enableKeyEvents: true,
												width: 625
											}
										]

									},
									{
										/**
										 * Line two
										 */
										xtype: 'fieldcontainer',
										layout: 'hbox',
										defaults: {
											margin: '0 10 0 0',
											xtype: 'textfield'
										},
										items: [

											{

												xtype: 'numberfield',
												fieldLabel: i18n('amount'),
												name: 'administer_amount',
												width: 160
											},
											{

												xtype: 'textfield',
												fieldLabel: i18n('units'),
												name: 'administer_units',
												labelWidth: 50,
												width: 125

											},
											{
												fieldLabel: i18n('administration_site'),
												width: 320,
												labelWidth: 130,
												xtype: 'gaiaehr.combo',
												list: 119,
												queryMode: 'local',
												loadStore: true,
												name: 'administration_site'
											}
										]

									},
									{
										/**
										 * Line three
										 */
										xtype: 'fieldcontainer',
										layout: 'hbox',
										defaults: {
											margin: '0 10 0 0',
											xtype: 'textfield'
										},
										items: [
											{
												fieldLabel: i18n('route'),
												xtype: 'gaiaehr.combo',
												list: 6,
												queryMode: 'local',
												loadStore: true,
												width: 295,
												name: 'route'
											},
											{
												fieldLabel: i18n('date_administered'),
												width: 320,
												labelWidth: 115,
												xtype: 'mitos.datetime',
												dateTimeFormat: 'Y-m-d',
												name: 'administered_date'
											}
										]

									},
									{
										fieldLabel: i18n('administered_by'),
										xtype: 'textfield',
										name: 'administered_by',
										margin: '0 10 5 0',
										width: 625
									},
									{
										fieldLabel: i18n('notes'),
										xtype: 'textfield',
										name: 'note',
										width: 625
									}
								]
							},
							{
								xtype: 'container',
								items:[
									{
										xtype:'fieldset',
										title:i18n('substance_data'),
										defaults: {
											margin: '0 0 5 0',
											width: 250
										},
										items:[
											{
												fieldLabel: i18n('lot_number'),
												xtype: 'textfield',
												name: 'lot_number'
											},
											{
												fieldLabel: i18n('exp_date'),
												xtype: 'datefield',
												format: 'Y-m-d',
												name: 'exp_date'
											},
											{
												xtype: 'cvxmanufacturersforcvxcombo',
												fieldLabel: i18n('manufacturer'),
												margin: '0 0 8 0',
												name: 'manufacturer'
											}
										]
									},
									{
										xtype: 'checkboxfield',
										boxLabel: i18n('entered_in_error'),
										name: 'is_error'
									}
								]
							}

						]
					}
				]
			}),
			tbar:[
				'->',
				{
					text: i18n('add_new'),
					action: 'encounterRecordAdd',
					itemId: 'addImmunizationBtn',
					iconCls: 'icoAdd'
				}
			],
			bbar: [
				'-',
				{
					xtype: 'button',
					text: i18n('submit_hl7_vxu'),
					disabled: true,
					itemId: 'submitVxuBtn'
				},
				'-',
				'->',
				{
					text: i18n('review'),
					itemId: 'reviewImmunizationsBtn',
					action: 'encounterRecordAdd'
				}
			]
		},
		{
			xtype:'grid',
			title:i18n('immunization_list'),
			itemId: 'cvxGrid',
			collapseMode:'mini',
			region:'east',
			collapsible:true,
			collapsed:true,
			width:300,
			split:true,
			store: Ext.create('App.store.patient.CVXCodes'),
			columns: [
				{
					text: i18n('code'),
					dataIndex: 'cvx_code',
					width: 50
				},
				{
					text: i18n('immunization_name'),
					dataIndex: 'name',
					flex: 1
				}
			]
		}
	]
});