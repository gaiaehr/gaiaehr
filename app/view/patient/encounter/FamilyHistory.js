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

Ext.define('App.view.patient.encounter.FamilyHistory', {
	extend: 'Ext.form.Panel',
	xtype: 'familyhistorypanel',
	requires: [
		'App.ux.form.fields.CheckBoxWithFamilyRelation'
	],
	itemId: 'familyHistoryPanel',
	title: i18n('family_history'),
	autoScroll: true,
	frame: true,
	bodyPadding: 10,
	bodyStyle: 'background-color:white',

	plugins: {
		ptype: 'advanceform',
		autoSync: g('autosave'),
		syncAcl: acl['edit_family_history']
	},

	buttons: [
		{
			text: i18n('save'),
			iconCls: 'save',
			action: 'encounterRecordAdd',
			itemId: 'familyHistorySaveBtn'
		}
	],

	initComponent: function(){
		var me = this;
		me.callParent();
		me.getFormItems(me, 12);
	}
});