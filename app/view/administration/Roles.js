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
	requires: [
		'App.ux.combo.XCombo',
		'Ext.grid.plugin.CellEditing',
		'Ext.ux.DataTip'
	],
	itemId: 'AdministrationRolePanel',
	pageTitle: _('roles_and_permissions'),
	pageBody: [
		{
			xtype:'grid',
			bodyStyle: 'background-color:white',
			itemId: 'AdministrationRoleGrid',
			frame: true,
			columnLines: true,
			tbar: [
				{
					xtype: 'xcombo',
					emptyText: _('select'),
					labelWidth: 50,
					width: 250,
					valueField: 'id',
					displayField: 'title',
					queryMode: 'local',
					store: Ext.create('App.store.administration.AclGroups'),
					itemId: 'AdministrationRoleGroupCombo',
					windowConfig: {
						title: _('add_group')
					},
					formConfig: {
						border: false,
						bodyPadding: 10,
						items: [
							{
								xtype: 'textfield',
								fieldLabel: _('group_name'),
								name: 'title'
							},
							{
								xtype: 'checkbox',
								fieldLabel: _('active'),
								name: 'active'
							}
						]
					}
				},
				'-',
				'->',
				'-',
				{
					xtype: 'button',
					text: _('add_role'),
					iconCls: 'icoAdd',
					action: 'adminAclAddRole'
				},
				'-'
			],
			//    selModel: {
			//        selType: 'cellmodel'
			//    },
			features: [
				{
					ftype: 'grouping'
				}
			],
			plugins: [
				{
					ptype: 'cellediting',
					clicksToEdit: 1
				},
				{
					ptype: 'datatip',
					tpl: _('click_to_edit')
				}
			],
			columns: [
				{
					text: 'Permission',
					width: 250,
					locked: true
				}
			]
		}
	],
	pageButtons: [
		{
			text: _('cancel'),
			cls: 'cancelBtn',
			action: 'adminAclCancel'
		},
		'-',
		{
			text: _('save'),
			cls: 'saveBtn',
			action: 'adminAclSave'
		}
	]
});
