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

Ext.define('App.model.patient.Insurance', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_insurances',
		comment: 'Patient Insurances'
	},
	fields: [
		{
			name: 'id',
			type: 'int',
			comment: 'Insurance ID'
		},
		{
			name: 'pid',
			type: 'int',
			comment: 'Patient ID'
		},
		{
			name: 'createUid',
			type: 'int',
			comment: 'create user ID'
		},
		{
			name: 'writeUid',
			type: 'int',
			comment: 'update user ID'
		},
		{
			name: 'createDate',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'create date'
		},
		{
			name: 'updateDate',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'last update date'
		},
		{
			name: 'provider',
			type: 'string',
			comment: 'provider',
			len: 80
		},
		{
			name: 'planName',
			type: 'string',
			comment: 'plan name',
			len: 40
		},
		{
			name: 'effectiveDate',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			comment: 'affective date'
		},
		{
			name: 'policyNumber',
			type: 'string',
			comment: 'policy number',
			len: 40
		},
		{
			name: 'groupNumber',
			type: 'string',
			comment: 'group number',
			len: 40
		},
		{
			name: 'subscriberTitle',
			type: 'string',
			comment: 'subscriber title',
			len: 10
		},
		{
			name: 'subscriberGivenName',
			type: 'string',
			comment: 'subscriber first name',
			len: 80
		},
		{
			name: 'subscriberMiddleName',
			type: 'string',
			comment: 'subscriber middle name',
			len: 80
		},
		{
			name: 'subscriberSurname',
			type: 'string',
			comment: 'subscriber last name',
			len: 80
		},
		{
			name: 'subscriberRelationship',
			type: 'string',
			comment: 'subscriber relationship',
			len: 40
		},
		{
			name: 'subscriberStreet',
			type: 'string',
			comment: 'subscriber address',
			len: 80
		},
		{
			name: 'subscriberCity',
			type: 'string',
			comment: 'subscriber city',
			len: 80

		},
		{
			name: 'subscriberState',
			type: 'string',
			comment: 'subscriber state',
			len: 80
		},
		{
			name: 'subscriberCountry',
			type: 'string',
			comment: 'subscriber country',
			len: 80
		},
		{
			name: 'subscriberPostalCode',
			type: 'string',
			comment: 'subscriber postal code',
			len: 20
		},
		{
			name: 'subscriberPhone',
			type: 'string',
			comment: 'subscriber phone',
			len: 20
		},
		{
			name: 'subscriberEmployer',
			type: 'string',
			comment: 'subscriber employer',
			len: 80
		},
		{
			name: 'subscriberEmployerStreet',
			type: 'string',
			comment: 'subscriber employer address',
			len: 80
		},
		{
			name: 'subscriberEmployerCity',
			type: 'string',
			comment: 'subscriber employer city',
			len: 80
		},
		{
			name: 'subscriberEmployerState',
			type: 'string',
			comment: 'subscriber employer state',
			len: 80
		},
		{
			name: 'subscriberEmployerCountry',
			type: 'string',
			comment: 'subscriber employer country',
			len: 80
		},
		{
			name: 'subscriberEmployerPostalCode',
			type: 'string',
			comment: 'subscriber employer postal code',
			len: 20
		},
		{
			name: 'subscriberDob',
			type: 'date',
			comment: 'subscriber date of birth'
		},
		{
			name: 'subscriberSS',
			type: 'string',
			comment: 'subscriber social security',
			len: 80
		},
		{
			name: 'copay',
			type: 'string',
			comment: 'default copay',
			len: 10
		},
		{
			name: 'type',
			type: 'string',
			comment: 'main or supplemental',
			len: 40
		},
		{
			name: 'image',
			type: 'string',
			dataType: 'mediumtext',
			comment: 'insurance image base64 string'
		},
		{
			name: 'active',
			type: 'bool',
			comment: '0=inactive, 1=active',
			defaultValue: 0
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: Patient.getInsurances,
			create: Patient.saveInsurance,
			update: Patient.saveInsurance
		}
	},
	associations: [
		{
			type: 'belongsTo',
			model: 'App.model.patient.Patient',
			associationKey: 'pid',
			foreignKey: 'pid'
		}
	]
});