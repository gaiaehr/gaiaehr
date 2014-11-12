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

Ext.define('App.view.administration.practice.Facilities', {
	extend: 'Ext.grid.Panel',
	xtype: 'facilitiespanel',
	title: _('facilities'),

	initComponent: function(){
		var me = this;

		Ext.apply(me, {
			store: me.store = Ext.create('App.store.administration.Facility'),
			columns: [
				{
					text: _('name'),
					flex: 1,
					sortable: true,
					dataIndex: 'name'
				},
				{
					text: _('phone'),
					width: 100,
					sortable: true,
					dataIndex: 'phone'
				},
				{
					text: _('fax'),
					width: 100,
					sortable: true,
					dataIndex: 'fax'
				},
				{
					text: _('city'),
					width: 100,
					sortable: true,
					dataIndex: 'city'
				}
			],
			plugins: Ext.create('App.ux.grid.RowFormEditing', {
				autoCancel: false,
				errorSummary: false,
				clicksToEdit: 1,
				items: [
					{
						xtype: 'container',
						layout: 'column',
						defaults: {
							xtype: 'container',
							columnWidth: 0.5,
							padding: 5,
							layout: 'anchor',
							defaultType: 'textfield'
						},
						items: [
							{
								defaults: {
									anchor: '100%'
								},
								items: [
									{
										fieldLabel: _('name'),
										name: 'name',
										allowBlank: false
									},
									{
										fieldLabel: _('phone'),
										name: 'phone'
									},
									{
										fieldLabel: _('fax'),
										name: 'fax'
									},
									{
										fieldLabel: _('street'),
										name: 'street'
									},
									{
										fieldLabel: _('city'),
										name: 'city'
									},
									{
										fieldLabel: _('state'),
										name: 'state'
									},
									{
										fieldLabel: _('postal_code'),
										name: 'postal_code'
									},
									{
										fieldLabel: _('country_code'),
										name: 'country_code'
									},
									{
										xtype: 'fieldcontainer',
										layout: 'hbox',
										items: [
											{
												xtype: 'textfield',
												fieldLabel: _('ssn'),
												name: 'ssn',
												margin: '0 10 0 0'
											},
											{
												xtype: 'textfield',
												fieldLabel: _('ein'),
												labelWidth: 40,
												name: 'ein'
											}
										]
									}
								]
							},
							{
								items: [
									{
										fieldLabel: _('billing_attn'),
										name: 'attn',
										anchor: '100%'
									},
									{
										xtype: 'mitos.poscodescombo',
										fieldLabel: _('pos_code'),
										name: 'pos_code',
										anchor: '100%'
									},
									{
										fieldLabel: _('clia_number'),
										name: 'clia',
										anchor: '100%'
									},
									{
										fieldLabel: _('npi'),
										name: 'npi',
										anchor: '100%'
									},
									{
										fieldLabel: _('fda_number'),
										name: 'fda',
										anchor: '100%'
									},
									{
										xtype: 'checkbox',
										fieldLabel: _('active'),
										name: 'active'
									},
									{
										xtype: 'checkbox',
										fieldLabel: _('service_location'),
										name: 'service_location'
									},
									{
										xtype: 'checkbox',
										fieldLabel: _('billing_location'),
										name: 'billing_location'
									},
									{
										xtype: 'checkbox',
										fieldLabel: _('accepts_assignment'),
										name: 'accepts_assignment'
									}
								]
							}
						]
					}
				]
			}),
			tbar: Ext.create('Ext.PagingToolbar', {
				pageSize: 30,
				store: me.store,
				displayInfo: true,
				plugins: Ext.create('Ext.ux.SlidingPager', {
				}),
				items: ['-', {
					text: _('add_new_facility'),
					iconCls: 'save',
					scope: me,
					handler: me.addFacility
				}, '-', {
					text: _('show_active_facilities'),
					action: 'active',
					scope: me,
					handler: me.filterFacilitiesby
				}, '-', {
					text: _('show_inactive_facilities'),
					action: 'inactive',
					scope: me,
					handler: me.filterFacilitiesby
				}]

			})
		});

		me.callParent(arguments);
	},

	filterFacilitiesby: function(btn){

//		this.setTitle(_('facilities') + ' (' + Ext.String.capitalize(btn.action) + ')');

		this.store.load({
			filters: [
				{
					property: 'active',
					value: btn.action == 'active' ? 1 : 0
				}
			]
		});
	},

	addFacility: function(){
		var me = this,
			grid = me,
			store = grid.store;

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			active: 1,
			service_location: 1,
			billing_location: 0,
			accepts_assignment: 0
		});
		grid.editingPlugin.startEdit(0, 0);
	}
});
