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

Ext.define('App.model.patient.CarePlanGoal', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_care_plan_goals',
		comment: 'Patient Care Plan Goals'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'eid',
			type: 'int',
			index: true
		},
		{
			name: 'uid',
			type: 'int'
		},
		{
			name: 'goal',
			type: 'string',
			len: 300
		},
		{
			name: 'goal_code',
			type: 'string',
			len: 20
		},
		{
			name: 'goal_code_type',
			type: 'string',
			len: 15
		},
		{
			name: 'instructions',
			type: 'string',
			len: 500
		},
		{
			name: 'plan_date',
			type: 'date',
			dateFormat: 'Y-m-d',
			dataType: 'date'
		},
		{
			name: 'created_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'CarePlanGoals.getPatientCarePlanGoals',
			create: 'CarePlanGoals.addPatientCarePlanGoal',
			update: 'CarePlanGoals.updatePatientCarePlanGoal',
			destroy: 'CarePlanGoals.destroyPatientCarePlanGoal'
		}
	}
});

