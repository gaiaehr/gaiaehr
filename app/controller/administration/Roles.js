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

Ext.define('App.controller.administration.Roles', {
	extend: 'Ext.app.Controller',

	requires: [
		'App.model.administration.AclGroupPerm'
	],

	refs: [
		{
			ref: 'AdministrationRoleGroupCombo',
			selector: '#AdministrationRoleGroupCombo'
		},
		{
			ref: 'AdministrationRoleGrid',
			selector: '#AdministrationRoleGrid'
		}
	],

	init: function(){
		this.control({
			'#AdministrationRolePanel': {
				render: this.onAdministrationRolePanelRender
			},
			'#AdministrationRoleGrid': {
				beforeedit: this.beforeCellEdit
			},
			'#AdministrationRoleGroupCombo': {
				select: this.doAdministrationRoleGridReconfigure
			},
			'button[action=adminAclAddRole]': {
				click: this.doAddRole
			},
			'button[action=adminAclSave]': {
				click: this.doSaveAcl
			},
			'button[action=adminAclCancel]': {
				click: this.doCancelAcl
			}
		});
	},

	onAdministrationRolePanelRender: function(){
		var me = this,
			cmb = me.getAdministrationRoleGroupCombo(),
			store = cmb.getStore();

		store.load({
			callback: function(records){
				cmb.select(records[0]);
				me.doAdministrationRoleGridReconfigure();
			}
		});

	},

	doSaveAcl: function(){
		var me = this,
			store = this.getAdministrationRoleGrid().getStore();

		if(store.getUpdatedRecords().length > 0){
			me.getAdministrationRoleGrid().el.mask(_('saving'));
		}

		store.sync({
			callback: function(response){
				app.msg(_('sweet'), _('record_saved'));
				me.getAdministrationRoleGrid().el.unmask();
			}
		});
	},

	doCancelAcl: function(){
		this.getAdministrationRoleGrid().getStore().rejectChanges();
	},

	beforeCellEdit: function(editor, e){
		return e.field != 'title';
	},

	doAdministrationRoleGridReconfigure: function(){
		var me = this,
			cmb = me.getAdministrationRoleGroupCombo(),
			group_id = cmb.getValue(),
			grid = me.getAdministrationRoleGrid(),
			fields = [
				{
					name: 'id',
					type: 'int'
				},
				{
					name: 'title',
					type: 'string'
				},
				{
					name: 'group_id',
					type: 'int'
				},
				{
					name: 'category',
					type: 'string'
				}
			], columns, store, model;

		// add mask to view while we get the data and grid configurations
		grid.view.el.mask('Loading');
		// Ext.direct method to get grid configuration and data

		ACL.getGroupPerms({group_id: group_id}, function(response){
			// new columns
			columns = response.columns;

			// set model fields merging default fields and role fields
			fields = fields.concat(response.fields);
			me.getModel('administration.AclGroupPerm').setFields(fields);

			var store = Ext.create('Ext.data.Store', {
				model: 'App.model.administration.AclGroupPerm',
				groupField: 'category'
			});

			// add raw data to the store
			store.loadRawData(response.data);

			// add the checkbox editor and renderer to role fields
			for(var i = 0; i < columns.length; i++){
				columns[i].editor = {xtype: "checkbox"};
				columns[i].renderer = app.boolRenderer;
			}

			columns.push({
				flex: 1
			});

			// reconfigure grid
			grid.reconfigure(store, columns);
			// remove grid view mask
			grid.view.el.unmask();

		});
	},

	doAddRole: function(){
		var me = this,
			record = Ext.create('App.model.administration.AclRoles', {
				group_id: me.getAdministrationRoleGroupCombo().getValue()
			});

		me.getRoleWindow().show();
		me.roleWindow.down('form').getForm().loadRecord(record);
	},

	doSaveRole: function(){
		var me = this,
			panel = me.roleWindow.down('form'),
			form = panel.getForm(),
			record = form.getRecord(),
			values = form.getValues();

		if(form.isValid()){
			panel.el.mask(_('be_right_back'));
			record.set(values);
			record.save({
				callback: function(rec){
					me.doAdministrationRoleGridReconfigure();
					panel.el.unmask();
					me.roleWindow.close();
				}
			});
		}
	},

	doCancelRole: function(){
		this.roleWindow.close();
	},

	getRoleWindow: function(){
		var me = this;

		me.roleWindow = Ext.widget('window', {
			title: _('new_role'),
			items: [
				{
					xtype: 'form',
					border: false,
					bodyPadding: 10,
					items: [
						{
							xtype: 'textfield',
							fieldLabel: _('role_name'),
							name: 'role_name',
							allowBlank: false
						},
						{
							xtype: 'checkbox',
							fieldLabel: _('active'),
							name: 'active'
						}
					]
				}
			],
			buttons: [
				{
					text: _('cancel'),
					cls: 'cancelBtn',
					scope: me,
					handler: me.doCancelRole,
					action: 'adminAclRoleCancel'
				},
				{
					text: _('save'),
					cls: 'saveBtn',
					scope: me,
					handler: me.doSaveRole,
					action: 'adminAclRoleSave'
				}
			]
		});

		return me.roleWindow;
	}

});