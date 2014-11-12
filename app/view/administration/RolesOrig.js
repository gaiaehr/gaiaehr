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

Ext.define('App.view.administration.Roles', {
	extend: 'App.ux.RenderPanel',
	pageTitle: _('roles_and_permissions'),

	initComponent: function(){
		var me = this;
		//******************************************************************************
		// Roles Store
		//******************************************************************************

		me.grid = Ext.create('Ext.grid.Panel', {
			bodyStyle: 'background-color:white',
			store: Ext.create('App.store.administration.RolePerms'),
			columns: [
				{
					text: _('permission'),
					dataIndex: 'perm_name',
					locked: true,
					width: 300
				},
				{
					text: _('front_office'),
					dataIndex: 'role-front_office',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					text: _('auditor'),
					dataIndex: 'role-auditor',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					text: _('clinician'),
					dataIndex: 'role-clinician',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					text: _('physician'),
					dataIndex: 'role-physician',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					text: _('emergency_access'),
					dataIndex: 'role-emergencyaccess',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					text: _('referrer'),
					dataIndex: 'role-referrer',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					text: _('administrator'),
					dataIndex: 'role-administrator',
					editor: { xtype: 'checkbox' },
					renderer: function(v){
						return app.boolRenderer(v);
					},
					cls: 'headerAlignCenter',
					width: 120
				},
				{
					flex: 1
				}
			],
			features: [
				{
					ftype: 'grouping',
					groupHeaderTpl: _('category') + ': {name}'
				}
			],
			plugins: [
				me.editingPlugin = Ext.create('Ext.grid.plugin.RowEditing', {
					clicksToEdit: 1,
					listeners: {
						beforeedit: function(context, e){
							return e.field != 'perm_name';
						}
					}
				})
			],
			buttons: [
				{
					text: _('cancel'),
					//                    iconCls: 'cancel',
					margin: '0 20 0 0',
					scope: me,
					handler: me.onRolesCancel
				},
				{
					text: _('save'),
					iconCls: 'save',
					margin: '0 20 0 0',
					scope: me,
					handler: me.onRolesSave
				}
			]
		});

		me.pageBody = [me.grid];
		me.pageBbuttons = [
			{
				text: _('cancel'),
				iconCls: 'cancel',
				margin: '0 20 0 0',
				scope: me,
				handler: me.onRolesCancel
			},
			{
				text: _('save'),
				iconCls: 'save',
				margin: '0 20 0 0',
				scope: me,
				handler: me.onRolesSave
			}
		];

		me.callParent(arguments);
	},

	onRolesCancel: function(){
		var me = this, form = me.form.getForm(), values = form.getValues(), record = form.getRecord(), changedValues;
		if(record.set(values) !== null){
			me.form.el.mask(_('saving_roles') + '...');
			changedValues = record.getChanges();
			Roles.saveRolesData(changedValues, function(provider, response){
				if(response.result){
					me.form.el.unmask();
					me.msg('Sweet!', _('roles_updated'));
					record.commit();
				}
			});
		}
	},

	onRolesSave: function(){
		var me = this, form = me.form.getForm(), values = form.getValues(), record = form.getRecord(), changedValues;
		if(record.set(values) !== null){
			me.form.el.mask(_('saving_roles') + '...');
			changedValues = record.getChanges();
			Roles.saveRolesData(changedValues, function(provider, response){
				if(response.result){
					me.form.el.unmask();
					me.msg('Sweet!', _('roles_updated'));
					record.commit();
				}
			});
		}
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		var me = this;
		//        form.el.mask(_('loading') + '...');

		me.grid.store.load();

		callback(true);
	}
});
