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

Ext.define('App.view.patient.Vitals', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.vitalspanel',
	title: i18n('vitals'),
	layout:'border',
	bodyPadding:5,
	items:[
		{
			xtype:'container',
			height: 100,
			region: 'north',
			layout:{
				type:'hbox',
				align:'stretch'
			},
			defaults:{
				xtype:'container',
				cls: 'latest-vitals-items',
				margin: '5 10',
				width: 130
			},
			items:[
				{
					html:'<p class="title">BP</p><p class="value">180/90<img src="resources/images/icons/arrow_down.png" class="trend"></p><p class="extra">systolic/diastolic</p>'
				},
				{
					html:'<p class="title">Temp.</p><p class="value">78&deg;c<img src="resources/images/icons/arrow_down.png" class="trend"></p><p class="extra">Oral</p>'
				},
				{
					html:'<p class="title">Weight</p><p class="value">180 lbs<img src="resources/images/icons/arrow_down.png" class="trend"></p>'
				},
				{
					html:'<p class="title">Height</p><p class="value">66 in<img src="resources/images/icons/arrow_down.png" class="trend"></p>'
				},
				{
					html:'<p class="title">BMI</p><p class="value">21.1<img src="resources/images/icons/arrow_down.png" class="trend"></p><p class="extra">overweight</p>'
				}
			]
		},
		{
			xtype:'grid',
			region: 'center',
			flex: 1,
			columnsLines: true,
			columns:[
				{
					text: i18n('date'),
					dataIndex: 'date'
				},
				{
					text: i18n('weight_lbs'),
					dataIndex: 'date'
				},
				{
					text: i18n('weight_kg'),
					dataIndex: 'weight_kg',
					hidden: true
				},
				{
					text: i18n('height_in'),
					dataIndex: 'height_in'
				},
				{
					text: i18n('height_cm'),
					dataIndex: 'height_cm',
					hidden: true
				},
				{
					text: i18n('bp_systolic'),
					dataIndex: 'bp_systolic'
				},
				{
					text: i18n('bp_diastolic'),
					dataIndex: 'bp_diastolic'
				},
				{
					text: i18n('temp_f'),
					dataIndex: 'temp_f',
					width:70
				},
				{
					text: i18n('temp_c'),
					dataIndex: 'temp_c',
					width:70,
					hidden: true
				},
				{
					text: i18n('temp_location'),
					dataIndex: 'temp_location'
				},
				{
					text: i18n('pulse'),
					dataIndex: 'pulse',
					width:60
				},
				{
					text: i18n('respiration'),
					dataIndex: 'respiration'
				},
//				{
//					text: i18n('oxygen_saturation'),
//					dataIndex: 'oxygen_saturation'
//				},
//				{
//					text: i18n('head_circumference_in'),
//					dataIndex: 'head_circumference_in',
//					width: 150
//				},
//				{
//					text: i18n('head_circumference_cm'),
//					dataIndex: 'head_circumference_cm',
//					width: 150,
//					hidden: true
//				},
//				{
//					text: i18n('waist_circumference_in'),
//					dataIndex: 'waist_circumference_in',
//					width: 150
//				},
//				{
//					text: i18n('waist_circumference_cm'),
//					dataIndex: 'waist_circumference_cm',
//					width: 150,
//					hidden: true
//				},
				{
					text: i18n('bmi'),
					dataIndex: 'bmi'
				},
//				{
//					text: i18n('bmi_status'),
//					dataIndex: 'bmi_status'
//				}
				{
					text: i18n('other_notes'),
					dataIndex: 'other_notes',
					flex: 1
				}
			]
		}
	]
});
