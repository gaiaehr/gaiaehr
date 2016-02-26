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

Ext.define('App.view.patient.windows.FamilyHistory', {
	extend: 'Ext.window.Window',
	xtype: 'familyhistorywindow',
	requires: [
		'App.ux.form.fields.CheckBoxWithFamilyRelation'
	],
	title: _('family_history'),
	width: 600,
	height: 400,
	layout: 'fit',
	closeAction: 'hide',
	modal: true,
	bodyStyle: 'background-color:white',
	items: [
		{
			xtype: 'form',
			bodyPadding: 10,
			autoScroll: true,
			itemId: 'FamilyHistoryForm'
		}
	],
	buttons: [
		{
			text: _('cancel'),
			iconCls: 'icoCancel',
			itemId: 'FamilyHistoryWindowCancelBtn'
		},
		{
			text: _('save'),
			iconCls: 'icoAdd',
			itemId: 'FamilyHistoryWindowSaveBtn'
		}
	],

	initComponent: function(){
		var me = this;
		me.callParent();
		me.getFormItems(me.down('form'), 12);
	}
});