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

Ext.define('App.view.patient.Referrals', {
	extend: 'Ext.grid.Panel',
	xtype: 'patientreferralspanel',
	action: 'referralsGrid',
	store: Ext.create('App.store.patient.PatientsReferrals', {
			remoteFilter: true
	}),
	columns: [
		{
			text: i18n('date'),
			dataIndex: 'description',
			menuDisabled: true,
			resizable: false
		},
		{
			text: i18n('to'),
			dataIndex: 'status',
			menuDisabled: true,
			resizable: false,
			width: 200
		},
		{
			text: i18n('notes'),
			dataIndex: 'notes',
			menuDisabled: true,
			resizable: false,
			width: 200
		}
	],
	tbar: [
		'->',
		{
			text: i18n('add_referral'),
			iconCls: 'icoAdd',
			action:'addReferralBtn'
		}
	]
});