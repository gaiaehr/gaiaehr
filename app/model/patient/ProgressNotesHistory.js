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

Ext.define('App.model.patient.ProgressNotesHistory', {
	extend: 'Ext.data.Model',
	fields: [
		{
			name: 'service_date',
			type: 'date'
		},
		{
			name: 'brief_description',
			type: 'string'
		},
		{
			name: 'subjective',
			type: 'string'
		},
		{
			name: 'objective',
			type: 'string'
		},
		{
			name: 'assessment',
			type: 'string'
		},
		{
			name: 'plan',
			type: 'string'
		},
		{
			name: 'progress',
			type: 'string',
			convert: function(v, record){
				var str = '';
				str += '<b>' + _('service_date') + ':</b> ' + Ext.Date.format(record.data.service_date, g('date_time_display_format')) + '<br>';
				str += '<b>' + _('chief_complaint') + ':</b> ' + Ext.String.htmlDecode(record.data.brief_description) + '<br>';
				str += '<b>' + _('subjective') + ':</b> ' + Ext.String.htmlDecode(record.data.subjective) + '<br>';
				str += '<b>' + _('objective') + ':</b> ' + Ext.String.htmlDecode(record.data.objective) + '<br>';
				str += '<b>' + _('assessment') + ':</b> ' + Ext.String.htmlDecode(record.data.assessment) + '<br>';
				str += '<b>' + _('plan') + ':</b> ' + Ext.String.htmlDecode(record.data.plan) + '<br>';
				return str;
			}
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Encounter.getSoapHistory'
		}
	}
});