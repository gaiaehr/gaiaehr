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

Ext.define('App.view.patient.encounter.CarePlanGoals', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.store.patient.CarePlanGoals'
	],
	xtype: 'careplangoalsgrid',
	store: Ext.create('App.store.patient.CarePlanGoals'),

	frame: true,
	columns: [
		{
			text: _('goal'),
			dataIndex: 'goal',
			width: 200
		},
		{
			text: _('instructions'),
			dataIndex: 'instructions',
			flex: 1
		}
	],
	tbar: [
		_('care_plan_goals'),
		'->',
		{
			text: _('new_goal'),
			iconCls: 'icoAdd',
			itemId: 'NewCarePlanGoalBtn'
		}
	]
});