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

Ext.define('App.view.miscellaneous.AddressBook', {
	extend: 'App.ux.RenderPanel',
	requires: [
		'App.store.miscellaneous.AddressBook',
		'Ext.toolbar.Paging',
		'Ext.ux.SlidingPager'
	],
	pageTitle: _('address_book'),

	initComponent: function(){
		var me = this;

		me.grid = Ext.create('Ext.grid.Panel', {
			store: me.store = Ext.create('App.store.miscellaneous.AddressBook'),
			columns: [
				{
					header: _('name'),
					width: 200,
					dataIndex: 'fullname'
				},
				{
					header: _('primary_phone'),
					dataIndex: 'phone',
					width: 120
				},
				{
					header: _('cell_phone'),
					dataIndex: 'mobile',
					width: 120
				},
				{
					header: _('fax'),
					dataIndex: 'fax',
					width: 120
				},
				{
					header: _('email'),
					dataIndex: 'email',
					width: 120
				},
				{
					header: _('direct_address'),
					dataIndex: 'direct_address',
					width: 120
				},
				{
					header: _('notes'),
					dataIndex: 'notes',
					flex: 1
				}
			],
			plugins: [
				{
					ptype: 'rowformediting',
					items: [
						{
							xtype: 'container',
							layout: 'column',
							defaults: {
								xtype: 'container',
								layout: 'anchor',
								margin: 5
							},
							items: [
								{
									width: 500,
									defaults: {
										labelWidth: 80,
										anchor: '100%'
									},
									items: [
										{
											xtype: 'fieldcontainer',
											fieldLabel: _('name'),
											layout: 'hbox',
											defaults: {
												margin: '0 5 0 0'
											},
											items: [
												{
													xtype: 'textfield',
													emptyText: _('first_name'),
													name: 'fname',
													width: 130
												},
												{
													xtype: 'textfield',
													emptyText: _('middle_name'),
													name: 'mname',
													width: 50
												},
												{
													xtype: 'textfield',
													emptyText: _('last_name'),
													name: 'lname',
													flex: 1,
													margin: 0
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											fieldLabel: _('address'),
											layout: 'anchor',
											defaults: {
												anchor: '100%',
												margin: '0 0 5 0'
											},
											items: [
												{
													xtype: 'textfield',
													emptyText: _('street'),
													name: 'street'
												},
												{
													xtype: 'textfield',
													name: 'street_cont'
												},
												{
													xtype: 'container',
													layout: 'hbox',
													defaults: {
														margin: '0 5 0 0'
													},
													items: [
														{
															xtype: 'textfield',
															emptyText: _('city'),
															name: 'city',
															flex: 1
														},
														{
															xtype: 'textfield',
															emptyText: _('state'),
															name: 'state',
															width: 120
														},
														{
															xtype: 'textfield',
															emptyText: _('zip'),
															name: 'zip',
															width: 100,
															margin: 0
														}
													]
												},
												{
													xtype: 'textfield',
													emptyText: _('country'),
													name: 'country',
													margin: '5 0 0 0'
												}
											]
										},
										{
											xtype: 'textfield',
											fieldLabel: _('notes'),
											name: 'notes',
											margin: '5 0 5 0'
										}
									]
								},
								{
									width: 300,
									layout: 'anchor',
									defaults: {
										anchor: '100%',
										labelWidth: 80,
										margin: '0 0 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											fieldLabel: _('phone') + ' (1)',
											name: 'phone'
										},
										{
											xtype: 'textfield',
											fieldLabel: _('phone') + ' (2)',
											name: 'phone2'
										},
										{
											xtype: 'textfield',
											fieldLabel: _('cell_phone'),
											name: 'mobile'
										},
										{
											xtype: 'textfield',
											fieldLabel: _('fax'),
											name: 'fax'
										},
										{
											xtype: 'textfield',
											fieldLabel: _('email'),
											name: 'email',
											margin: '5 0 5 0',
											vtype: 'email'
										},
										{
											xtype: 'textfield',
											fieldLabel: _('url'),
											name: 'url'
										}
									]
								},
								{
									width: 300,
									layout: 'anchor',
									defaults: {
										anchor: '100%',
										labelWidth: 100,
										margin: '0 0 5 0'
									},
									items: [
										{
											xtype: 'textfield',
											fieldLabel: _('direct_address'),
											name: 'direct_address'
										}
									]
								}
							]
						}
					]
				}
			],
			tbar: [
				{
					text: _('add_contact'),
					iconCls: 'icoAdd',
					scope: me,
					handler: me.onAddContact
				}
			],
			bbar: Ext.create('Ext.PagingToolbar', {
				store: me.store,
				displayInfo: true,
				plugins: Ext.create('Ext.ux.SlidingPager', {})
			})
		});

		me.pageBody = [ me.grid ];

		me.callParent(arguments);
	},

	onAddContact: function(){
		var me = this,
			grid = me.grid,
			store = grid.getStore(),
			plugin = grid.editingPlugin,
			record;
		plugin.cancelEdit();
		record = store.add({})[0];
		plugin.startEdit(record, 0);

	},

	onActive: function(callback){
		this.grid.getStore().load();
		callback(true);
	}
});
