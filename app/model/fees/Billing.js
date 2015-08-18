/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.fees.Billing',
	{
		extend: 'Ext.data.Model',
		table: {
			name: 'billing',
			comment: 'Billing'
		},
		fields: [
			{
				name: 'eid',
				type: 'int '
			},
			{
				name: 'pid',
				type: 'int'
			},
			{
				name: 'patientName',
				type: 'string'
			},
			{
				name: 'primaryProvider',
				type: 'string'
			},
			{
				name: 'encounterProvider',
				type: 'string'
			},
			{
				name: 'supervisorProvider',
				type: 'string'
			},
			{
				name: 'facility',
				type: 'string'
			},
			{
				name: 'billing_facility',
				type: 'string'
			},
			{
				name: 'service_date',
				type: 'date'
			},
			{
				name: 'close_date',
				type: 'date'
			},
			{
				name: 'billing_stage',
				type: 'int'
			},
			{
				name: 'dxCodes',
				type: 'auto'
			}
		],
		proxy: {
			type: 'direct',
			api: {
				read: 'Fees.getFilterEncountersBillingData'
			},
			reader: {
				root: 'encounters',
				totalProperty: 'totals'
			}
		}

	});
