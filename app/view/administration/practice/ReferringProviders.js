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

Ext.define('App.view.administration.practice.ReferringProviders', {
	extend: 'Ext.grid.Panel',
	xtype: 'referringproviderspanel',
	requires: [
		'Ext.ux.SlidingPager'
	],
	title: _('referring_providers'),

	initComponent: function(){
		var me = this;

		me.store = Ext.create('App.store.administration.ReferringProviders', {
			autoSync: false,
			remoteSort: true,
			sorters: [
				{
					property: 'lname',
					direction: 'ASC'
				}
			]
		});
		
		Ext.apply(me, {
			columns: [
				{
					width: 200,
					text: _('name'),
					sortable: true,
					renderer:function(v, meta, record){
						return record.data.title + ' ' + record.data.lname + ', ' + record.data.fname + ' ' + record.data.mname;
					}
				},
				{
					flex: 1,
					text: _('email'),
					sortable: true,
					dataIndex: 'email'
				},
				{
					flex: 1,
					text: _('phone_number'),
					sortable: true,
					dataIndex: 'phone_number'
				},
				{
					flex: 1,
					text: _('cell_number'),
					sortable: true,
					dataIndex: 'cel_number'
				},
				{
					flex: 1,
					text: _('aditional_info'),
					sortable: true,
					dataIndex: 'notes'
				},
				{
					text: _('active'),
					sortable: true,
					dataIndex: 'active',
					renderer: me.boolRenderer
				}
			],
			plugins: [
				me.formEditing = Ext.create('App.ux.grid.RowFormEditing', {
					clicksToEdit: 1,
					items: [
						{
							xtype: 'fieldcontainer',
							fieldLabel: _('first_middle_last'),
							labelWidth: 130,
							labelAlign: 'right',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							msgTarget: 'under',
							items: [
								{
									width: 50,
									xtype: 'mitos.titlescombo',
									name: 'title'
								},
								{
									width: 150,
									xtype: 'textfield',
									name: 'fname',
									allowBlank: false
								},
								{
									width: 100,
									xtype: 'textfield',
									name: 'mname'
								},
								{
									width: 150,
									xtype: 'textfield',
									name: 'lname',
									allowBlank: false
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							items: [
								{
									xtype: 'textfield',
									name: 'email',
									fieldLabel: _('email'),
									labelWidth: 130,
									labelAlign: 'right'
								},
								{
									xtype: 'textfield',
									fieldLabel: _('taxonomy'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'taxonomy'
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: _('upin'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'upin'
								},
								{
									xtype: 'textfield',
									fieldLabel: _('npi'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'npi'
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: _('lic'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'lic'
								},
								{
									xtype: 'textfield',
									fieldLabel: _('ssn'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'ssn'
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: _('phone_number'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'phone_number'
								},
								{
									xtype: 'textfield',
									fieldLabel: _('fax_number'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'fax_number'
								}

							]
						},
						{
							xtype: 'fieldcontainer',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: _('cell_number'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'cel_number'
								},
								{
									xtype: 'checkbox',
									fieldLabel: _('active'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'active'
								}

							]
						},
						{
							xtype: 'fieldcontainer',
							layout: {
								type: 'hbox',
								defaultMargins: {
									top: 0,
									right: 5,
									bottom: 0,
									left: 0
								}
							},
							items: [
								{
									xtype: 'textfield',
									fieldLabel: _('username'),
									labelWidth: 130,
									labelAlign: 'right',
									minLength: 5,
									maxLength: 15,
									name: 'username'
								},
								{
									xtype: 'textfield',
									fieldLabel: _('password'),
									labelWidth: 130,
									labelAlign: 'right',
									minLength: 8,
									maxLength: 15,
									name: 'password',
									inputType: 'password',
									vtype: 'strength',
									strength: 24,
									plugins: {
										ptype: 'passwordstrength'
									}
								},
								{
									xtype: 'checkbox',
									fieldLabel: _('authorized'),
									labelWidth: 130,
									labelAlign: 'right',
									name: 'authorized'
								}

							]
						},
						{
							height: 50,
							xtype: 'textareafield',
							name: 'notes',
							width: 600,
							fieldLabel: _('notes'),
							labelWidth: 130,
							labelAlign: 'right',
							emptyText: _('additional_info')
						}
					]
				})
			],

			dockedItems: [
				{
					xtype: 'toolbar',
					dock: 'top',
					items: [
						'->',
						{
							xtype: 'button',
							text: _('referring_provider'),
							iconCls: 'icoAdd',
							itemId: 'referringProviderAddBtn',
						}
					]
				},
				{
					xtype: 'pagingtoolbar',
					dock: 'bottom',
					pageSize: 25,
					store: me.store,
					displayInfo: true,
					plugins: Ext.create('Ext.ux.SlidingPager')
				}
			]
		});

		me.callParent(arguments);

	}

});
