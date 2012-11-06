/*
 GaiaEHR (Electronic Health Records)
 Billing.js
 Billing Model
 Copyright (C) 2012 Ernesto J. Rodriguez

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
Ext.define( 'App.model.fees.Billing',
{
	extend : 'Ext.data.Model',
	fields : [
	{
		name : 'eid',
		type : 'int '
	},
	{
		name : 'pid',
		type : 'int'
	},
	{
		name : 'patientName',
		type : 'string'
	},
	{
		name : 'primaryProvider',
		type : 'string'
	},
	{
		name : 'encounterProvider',
		type : 'string'
	},
	{
		name : 'supervisorProvider',
		type : 'string'
	},
	{
		name : 'facility',
		type : 'string'
	},
	{
		name : 'billing_facility',
		type : 'string'
	},
	{
		name : 'service_date',
		type : 'string'
	},
	{
		name : 'close_date',
		type : 'string'
	},
	{
		name : 'billing_stage',
		type : 'int'
	},
	{
		name : 'icdxCodes',
		type : 'auto'
	}],
	proxy :
	{
		type : 'direct',
		api :
		{
			read : Fees.getFilterEncountersBillingData
		},
		reader :
		{
			root : 'encounters',
			totalProperty : 'totals'
		}
	}

} );
