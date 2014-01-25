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

Ext.define('App.model.patient.PatientsReferral', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_referrals',
		comment: 'Patients Referrals'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'eid',
			type: 'int',
			index: true,
			comment: 'encounter id'
		},
		{
			name: 'pid',
			type: 'int',
			index: true,
			comment: 'patient ID'
		},
		{
			name: 'uid',
			type: 'int',
			comment: 'user ID who created the referral'
		},
		{
			name: 'referral_date',
			type: 'date',
			dateFormat:'Y-m-d H:i:s'
		},
		{
			name: 'referral_from',
			type: 'int',
			comment: 'referral from doctors id'
		},
		{
			name: 'referral_to',
			type: 'string',
			len: 200,
			comment: 'referral to doctors name'
		},
		{
			name: 'reason',
			type: 'string',
			dataType:'text'
		},
		{
			name: 'diagnosis_codes',
			type: 'string'
		},
		{
			name: 'requested_services',
			type: 'string'
		},
		{
			name: 'include_vitals',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: Referrals.getPatientReferrals,
			create: Referrals.addPatientReferral,
			update: Referrals.updatePatientReferral,
			destroy: Referrals.deletePatientReferral
		},
		remoteGroup: false
	}
});