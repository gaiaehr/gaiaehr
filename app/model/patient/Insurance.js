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
			comment: 'provider'
		},
		{
			name: 'planName',
			type: 'string',
			comment: 'plan name'
		},
		{
			name: 'effectiveDate',
			type: 'string',
			comment: 'affective date'
		},
		{
			name: 'policyNumber',
			type: 'string',
			comment: 'policy number'
		},
		{
			name: 'groupNumber',
			type: 'string',
			comment: 'group number'
		},
		{
			name: 'subscriberTitle',
			type: 'string',
			comment: 'subscriber title'
		},
		{
			name: 'subscriberGivenName',
			type: 'string',
			comment: 'subscriber first name'
		},
		{
			name: 'subscriberMiddleName',
			type: 'string',
			comment: 'subscriber middle name'
		},
		{
			name: 'subscriberSurname',
			type: 'string',
			comment: 'subscriber last name'
		},
		{
			name: 'subscriberRelationship',
			type: 'string',
			comment: 'subscriber relationship'
		},
		{
			name: 'subscriberStreet',
			type: 'string',
			comment: 'subscriber address'
		},
		{
			name: 'subscriberCity',
			type: 'string',
			comment: 'subscriber city'

		},
		{
			name: 'subscriberState',
			type: 'string',
			comment: 'subscriber state'
		},
		{
			name: 'subscriberCountry',
			type: 'string',
			comment: 'subscriber country'
		},
		{
			name: 'subscriberPostalCode',
			type: 'string',
			comment: 'subscriber postal code'
		},
		{
			name: 'subscriberPhone',
			type: 'string',
			comment: 'subscriber phone'
		},
		{
			name: 'subscriberEmployer',
			type: 'string',
			comment: 'subscriber employer'
		},
		{
			name: 'subscriberEmployerStreet',
			type: 'string',
			comment: 'subscriber employer address'
		},
		{
			name: 'subscriberEmployerCity',
			type: 'string',
			comment: 'subscriber employer city'
		},
		{
			name: 'subscriberEmployerState',
			type: 'string',
			comment: 'subscriber employer state'
		},
		{
			name: 'subscriberEmployerCountry',
			type: 'string',
			comment: 'subscriber employer country'
		},
		{
			name: 'subscriberEmployerPostalCode',
			type: 'string',
			comment: 'subscriber employer postal code'
		},
		{
			name: 'subscriberDob',
			type: 'date',
			comment: 'subscriber date of birth'
		},
		{
			name: 'subscriberSS',
			type: 'string',
			comment: 'subscriber social security'
		},
		{
			name: 'copay',
			type: 'string',
			comment: 'default copay'
		},
		{
			name: 'type',
			type: 'string',
			comment: 'main supplemental'
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