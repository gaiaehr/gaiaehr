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

Ext.define('App.view.miscellaneous.MyAccount', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('my_account'),

	requires: [
		'App.ux.combo.Titles',
		'App.ux.window.Window',
		'App.ux.combo.Facilities',
		'App.ux.form.fields.plugin.PasswordStrength'
	],

	initComponent: function(){
		var me = this;

		// *************************************************************************************
		// My Account Data Store
		// *************************************************************************************
		me.store = Ext.create('App.store.miscellaneous.Users');

		// *************************************************************************************
		// User Settings Form
		// Add or Edit purpose
		// *************************************************************************************
		me.myAccountForm = Ext.create('App.ux.form.Panel', {
			cls: 'form-white-bg',
			frame: true,
			hideLabels: true,
			defaults: {
				labelWidth: 89,
				layout: {
					type: 'hbox',
					defaultMargins: {
						top: 0,
						right: 5,
						bottom: 0,
						left: 0
					}
				}
			},
			items: [
				{
					xtype: 'textfield',
					hidden: true,
					name: 'id'
				},
				{
					xtype: 'fieldset',
					title: _('personal_info'),
					defaultType: 'textfield',
					layout: 'anchor',
					defaults: {
						labelWidth: 89,
						anchor: '100%',
						layout: {
							type: 'hbox',
							defaultMargins: {
								top: 0,
								right: 5,
								bottom: 0,
								left: 0
							}
						}
					},
					items: [
						{
							xtype: 'fieldcontainer',
							defaults: {
								hideLabel: true
							},
							msgTarget: 'under',
							items: [
								{
									width: 110,
									xtype: 'displayfield',
									value: 'First, Middle, Last: '
								},
								{
									width: 55,
									xtype: 'mitos.titlescombo',
									name: 'title'
								},
								{
									width: 105,
									xtype: 'textfield',
									name: 'fname'
								},
								{
									width: 100,
									xtype: 'textfield',
									name: 'mname'
								},
								{
									width: 175,
									xtype: 'textfield',
									name: 'lname'
								}
							]
						}
					]
				},
				{
					xtype: 'fieldset',
					title: _('login_info'),
					defaultType: 'textfield',
					layout: 'anchor',
					defaults: {
						labelWidth: 89,
						anchor: '100%',
						layout: {
							type: 'hbox',
							defaultMargins: {
								top: 0,
								right: 5,
								bottom: 0,
								left: 0
							}
						}
					},
					items: [
						{
							xtype: 'fieldcontainer',
							defaults: {
								hideLabel: true
							},
							msgTarget: 'under',
							items: [
								{
									width: 110,
									xtype: 'displayfield',
									value: 'Username: '
								},
								{
									width: 170,
									xtype: 'textfield',
									name: 'username'
								},
								{
									width: 100,
									xtype: 'displayfield',
									value: 'Password: '
								},
								{
									width: 175,
									xtype: 'textfield',
									name: 'password',
									inputType: 'password',
									disabled: true
								}
							]
						}
					]
				},
				{
					xtype: 'fieldset',
					title: _('other_info'),
					defaultType: 'textfield',
					layout: 'anchor',
					defaults: {
						labelWidth: 89,
						anchor: '100%',
						layout: {
							type: 'hbox',
							defaultMargins: {
								top: 0,
								right: 5,
								bottom: 0,
								left: 0
							}
						}
					},
					items: [
						{
							xtype: 'fieldcontainer',
							defaults: {
								hideLabel: true
							},
							msgTarget: 'under',
							items: [
								{
									width: 110,
									xtype: 'displayfield',
									value: 'Default Facility: '
								},
								{
									xtype:'mitos.facilitiescombo',
									width: 170,
									name:'facility_id'
								},
								{
									width: 100,
									xtype: 'displayfield',
									value: 'Taxonomy: '
								},
								{
									width: 175,
									xtype: 'textfield',
									name: 'taxonomy'
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							defaults: {
								hideLabel: true
							},
							items: [
								{
									width: 110,
									xtype: 'displayfield',
									value: 'Federal Tax ID: '
								},
								{
									width: 170,
									xtype: 'textfield',
									name: 'fedtaxid'
								},
								{
									width: 100,
									xtype: 'displayfield',
									value: 'Fed Drug ID: '
								},
								{
									width: 175,
									xtype: 'textfield',
									name: 'feddrugid'
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							defaults: {
								hideLabel: true
							},
							items: [
								{
									width: 110,
									xtype: 'displayfield',
									value: 'User PIN#: '
								},
								{
									width: 170,
									xtype: 'textfield',
									name: 'pin'
								},
								{
									width: 100,
									xtype: 'displayfield',
									value: 'NPI: '
								},
								{
									width: 175,
									xtype: 'textfield',
									name: 'npi'
								}
							]
						},
						{
							xtype: 'fieldcontainer',
							defaults: {
								hideLabel: true
							},
							items: [
								{
									width: 110,
									xtype: 'displayfield',
									value: 'Job Description: '
								},
								{
									width: 455,
									xtype: 'textfield',
									name: 'specialty'
								}
							]
						}
					]
				}
			],
			tbar:[
				{
					text: _('change_password'),
					iconCls: 'save',
					scope: me,
					handler: me.onPasswordChange
				}
			],
			buttons: [
				{
					text: _('save'),
					iconCls: 'save',
					scope: me,
					handler: me.onSaveClick
				}
			]
		});

		me.win = Ext.create('App.ux.window.Window', {
			width: 420,
			title: _('change_you_password'),
			items: [
				{
					xtype: 'form',
					bodyPadding: 15,
					defaultType: 'textfield',
					defaults: {
						labelWidth: 130,
						width: 380,
						inputType: 'password'
					},
					items: [
						{
							name: 'id',
							hidden: true
						},
						{
							fieldLabel: _('old_password'),
							name: 'oPassword',
							allowBlank: false
						},
						{
							fieldLabel: _('new_password'),
							name: 'nPassword',
							allowBlank: false,
							id: 'myAccountPage_nPassword',
							vtype      : 'strength',
							strength   : 24,
							plugins    : {
								ptype : 'passwordstrength'
							}
						},
						{
							fieldLabel: _('re_type_password'),
							name: 'vPassword',
							allowBlank: false,
							vtype: 'password',
							initialPassField: 'myAccountPage_nPassword',
							validateOnChange: true
						}
					]
				}
			],
			buttons: [
				{
					text: _('save'),
					scope: me,
					handler: me.onPasswordSave
				},
				{
					text: _('cancel'),
					scope: me,
					handler: me.onCancel
				}
			],
			listeners: {
				scope: me,
				close: me.onClose
			}

		});
		me.pageBody = [me.myAccountForm];

		me.callParent(arguments);
	},

	onPasswordSave: function(btn){
		var me = this,
			form = me.win.down('form').getForm(),
			values = form.getValues(),
			id = me.myAccountForm.getForm().getRecord().data.id,
			params;

		if(values.nPassword != values.vPassword){
			app.msg(_('oops'), _('password_does_not_match'), true);
			return;
		}

		if(form.isValid()){
			params = {
				id:id,
				old_password:values.oPassword,
				new_password:values.nPassword
			};

			User.updatePassword(params, function(provider, response){

				if(response.result.success){
					app.msg(_('sweet'), _('record_updated'));
					me.win.close();
				}else{
					app.msg(_('oops'), _(response.result.message), true);
				}
			});

		}


	},

	onPasswordChange: function(){
		this.win.show();
	},

	onCancel: function(){
		this.win.close();
	},

	onClose: function(){
		this.win.down('form').getForm().reset();
	},

	onSaveClick:function(btn){
		var me = this,
			form = me.myAccountForm.getForm(),
			record = form.getRecord(),
			values = form.getValues();

		record.set(values);
		record.save({
			callback:function(){
				app.msg(_('sweet'), _('record_update'))
			}
		});
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		var me = this,
			form = me.myAccountForm.getForm();

		this.store.load({
			callback: function(record){
				form.loadRecord(record[0]);
			}
		});

		callback(true);

	}
}); 
