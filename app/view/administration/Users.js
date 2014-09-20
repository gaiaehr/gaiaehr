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

Ext.define('App.view.administration.Users', {
	extend: 'App.ux.RenderPanel',
	requires: [
		'App.ux.form.fields.plugin.PasswordStrength',
		'App.ux.combo.ActiveSpecialties'
	],
	pageTitle: i18n('users'),

	initComponent: function(){
		var me = this;

		me.userStore = Ext.create('App.store.administration.User', {
			autoSync: false
		});

		me.userGrid = Ext.create('Ext.grid.Panel', {
			itemId: 'AdminUserGridPanel',
			store: me.userStore,
			columns: [
				{
					text: 'id',
					sortable: false,
					dataIndex: 'id',
					width: 25
				},
				{
					width: 100,
					text: i18n('username'),
					sortable: true,
					dataIndex: 'username'
				},
				{
					width: 200,
					text: i18n('name'),
					sortable: true,
					dataIndex: 'fullname'
				},
				{
					flex: 1,
					text: i18n('aditional_info'),
					sortable: true,
					dataIndex: 'notes'
				},
				{
					text: i18n('active'),
					sortable: true,
					dataIndex: 'active',
					renderer: me.boolRenderer
				},
				{
					text: i18n('authorized'),
					sortable: true,
					dataIndex: 'authorized',
					renderer: me.boolRenderer
				},
				{
					text: i18n('calendar_q'),
					sortable: true,
					dataIndex: 'calendar',
					renderer: me.boolRenderer
				}
			],
			plugins: [
				me.formEditing = Ext.create('App.ux.grid.RowFormEditing', {
					clicksToEdit: 1,
					items: [
						{
							xtype: 'container',
							itemId: 'UserGridEditFormContainer',
							layout: 'hbox',
							items: [
								{
									xtype: 'container',
									itemId: 'UserGridEditFormContainerLeft',
									items: [
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											items: [
												{
													width: 280,
													xtype: 'textfield',
													fieldLabel: i18n('username'),
													name: 'username',
													allowBlank: false,
													validateOnBlur: true,
													vtype: 'usernameField'
												},
												{
													width: 275,
													xtype: 'textfield',
													fieldLabel: i18n('password'),
													name: 'password',
													inputType: 'password',
													vtype: 'strength',
													strength: 24,
													plugins: {
														ptype: 'passwordstrength'
													}
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											fieldLabel: i18n('name'),
											items: [
												{
													width: 50,
													xtype: 'mitos.titlescombo',
													name: 'title'
												},
												{
													width: 145,
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
													name: 'lname'
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											items: [
												{
													width: 100,
													xtype: 'checkbox',
													fieldLabel: i18n('active'),
													name: 'active'
												},
												{
													width: 100,
													xtype: 'checkbox',
													fieldLabel: i18n('authorized'),
													name: 'authorized'
												},
												{
													width: 75,
													xtype: 'checkbox',
													fieldLabel: i18n('calendar_q'),
													name: 'calendar'
												},
												{
													width: 275,
													xtype: 'gaiaehr.combo',
													fieldLabel: i18n('type'),
													name: 'doctor_type',
													list: 121,
													loadStore: true
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											items: [
												{
													width: 280,
													xtype: 'mitos.facilitiescombo',
													fieldLabel: i18n('default_facility'),
													name: 'facility_id'
												},
												{
													width: 275,
													xtype: 'mitos.authorizationscombo',
													fieldLabel: i18n('authorizations'),
													name: 'see_auth'
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											items: [
												{
													width: 555,
													xtype: 'mitos.rolescombo',
													fieldLabel: i18n('access_control'),
													name: 'role_id',
													allowBlank: false
												}
//												{
//													width: 275,
//													xtype: 'textfield',
//													fieldLabel: i18n('taxonomy'),
//													name: 'taxonomy'
//												}
											]
										}
									]
								},
								{
									xtype: 'container',
									itemId: 'UserGridEditFormContainerRight',
									items: [
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											items: [
												{
													width: 280,
													xtype: 'textfield',
													fieldLabel: i18n('federal_tax_id'),
													name: 'fedtaxid'
												},
												{
													width: 275,
													xtype: 'textfield',
													fieldLabel: i18n('fed_drug_id'),
													name: 'feddrugid'
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											layout: {
												type: 'hbox'
											},
											fieldDefaults: {
												labelAlign: 'right'
											},
											items: [
												{
													width: 280,
													xtype: 'textfield',
													fieldLabel: i18n('upin'),
													name: 'pin'
												},
												{
													width: 275,
													xtype: 'textfield',
													fieldLabel: i18n('npi'),
													name: 'npi',
													maxLength: 10,
													vtype: 'npi'
												}
											]
										},
										{
											width: 558,
											xtype: 'activespecialtiescombo',
											fieldLabel: i18n('specialties'),
											name: 'specialty',
											margin: '5 0',
											labelAlign: 'right',
											multiSelect: true
										},
										{
											width: 558,
											xtype: 'textfield',
											fieldLabel: i18n('additional_info'),
											name: 'notes',
											labelAlign: 'right'
										}
									]
								}
							]
						}
					]
				})
			],
			dockedItems: [
				{
					xtype: 'toolbar',
					dock: 'top',
					items: [
						{
							xtype: 'button',
							text: i18n('add_new_user'),
							iconCls: 'save',
							scope: me,
							handler: me.onNewUser
						}
					]
				}
			]
		});

		me.pageBody = [ me.userGrid ];
		me.callParent(arguments);

	},

	onNewUser: function(){
		var me = this;

		me.formEditing.cancelEdit();
		me.userStore.insert(0, {
			create_date: new Date(),
			update_date: new Date(),
			create_uid: app.user.id,
			update_uid: app.user.id,
			active: 1,
			authorized: 0,
			calendar: 0
		});
		me.formEditing.startEdit(0, 0);
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		this.userStore.load();
		callback(true);
	}
});
