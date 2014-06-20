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

Ext.define('App.model.administration.DecisionSupportRule', {
	extend: 'Ext.data.Model',
	requires: 'App.model.administration.DecisionSupportRuleConcept',
	table: {
		name: 'support_rules'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'category',
			type: 'string',
			len: 10,
			comment: 'C = Clinical A = Administrative'
		},
		{
			name: 'alert_type',
			type: 'string',
			len: 2,
			comment: 'A = Active P = Passive'
		},
		{
			name: 'description',
			type: 'string'
		},
		{
			name: 'service_type',
			type: 'string',
			len: 10,
			comment: 'PROC IMMU DX MEDI LAB RAD'
		},
		{
			name: 'service_text',
			type: 'string'
		},
		{
			name: 'service_code',
			type: 'string',
			len: 25
		},
		{
			name: 'service_code_type',
			type: 'string',
			len: 10
		},
		{
			name: 'age_start',
			type: 'int',
			defaultValue: '0'
		},
		{
			name: 'age_end',
			type: 'int',
			defaultValue: '0'
		},
		{
			name: 'sex',
			type: 'string',
			len: 5
		},
		{
			name: 'warning',
			type: 'string',
			len: 5,
			comment: 'examples 1W or 5M or 1Y'
		},
		{
			name: 'past_due',
			type: 'string',
			len: 5,
			comment: 'examples 1W or 5M or 1Y'
		},
		{
			name: 'reference',
			type: 'string'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'DecisionSupport.getDecisionSupportRules',
			create: 'DecisionSupport.addDecisionSupportRule',
			update: 'DecisionSupport.updateDecisionSupportRule',
			destroy: 'DecisionSupport.deleteDecisionSupportRule'
		},
		reader: {
			root: 'data'
		}
	},
	hasMany: [
		{
			model: 'App.model.administration.DecisionSupportRuleConcept',
			name: 'concepts',
			foreignKey: 'rule_id'
		}
	]
});