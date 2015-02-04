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

Ext.define('App.view.patient.ItemsToReview', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.itemstoreview',
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	frame: true,
	bodyPadding: 5,
	bodyBorder: true,
	bodyStyle: 'background-color:white',
	showRating: true,
	autoScroll: true,
	itemId: 'ItemsToReviewPanel',
	items: [
		{
			xtype: 'container',
			layout: {
				type: 'hbox',
				align: 'stretch'
			},
			defaults: {
				xtype: 'grid',
				margin: '0 0 5 0'
			},
			items: [
				{
					title: _('immunizations'),
					frame: true,
					height: 180,
					flex: 1,
					store: Ext.create('App.store.patient.PatientImmunization'),
					itemId: 'ItemsToReviewImmuGrid',
					margin: '0 5 5 0',
					columns: [
						{
							header: _('immunization'),
							width: 250,
							dataIndex: 'vaccine_name'
						},
						{
							header: _('date'),
							width: 90,
							xtype: 'datecolumn',
							format: 'Y-m-d',
							dataIndex: 'administered_date'
						},
						{
							header: _('notes'),
							flex: 1,
							dataIndex: 'note'
						}
					]
				},
				{
					title: _('allergies'),
					frame: true,
					height: 180,
					flex: 1,
					store: Ext.create('App.store.patient.Allergies'),
					itemId: 'ItemsToReviewAllergiesGrid',
					columns: [
						{
							header: _('type'),
							width: 100,
							dataIndex: 'allergy_type'
						},
						{
							header: _('name'),
							width: 100,
							dataIndex: 'allergy'
						},
						{
							header: _('severity'),
							flex: 1,
							dataIndex: 'severity'
						}
					]
				}
			]
		},
		{
			xtype: 'container',
			layout: {
				type: 'hbox',
				align: 'stretch'
			},
			defaults: {
				xtype: 'grid',
				margin: '0 0 5 0'
			},
			items: [
				{
					title: _('active_problems'),
					frame: true,
					height: 180,
					flex: 1,
					margin: '0 5 5 0',
					store: Ext.create('App.store.patient.PatientActiveProblems'),
					itemId: 'ItemsToReviewActiveProblemsGrid',
					columns: [
						{
							header: _('problem'),
							width: 250,
							dataIndex: 'code_text'
						},
						{
							xtype: 'datecolumn',
							header: _('begin_date'),
							width: 90,
							format: 'Y-m-d',
							dataIndex: 'begin_date'
						},
						{
							xtype: 'datecolumn',
							header: _('end_date'),
							flex: 1,
							format: 'Y-m-d',
							dataIndex: 'end_date'
						}
					]
				},
				{
					title: _('medications'),
					frame: true,
					height: 180,
					flex: 1,
					store: Ext.create('App.store.patient.Medications'),
					itemId: 'ItemsToReviewMedicationsGrid',
					columns: [
						{
							header: _('medication'),
							width: 250,
							dataIndex: 'STR'
						},
						{
							xtype: 'datecolumn',
							header: _('begin_date'),
							width: 90,
							format: 'Y-m-d',
							dataIndex: 'begin_date'
						},
						{
							xtype: 'datecolumn',
							header: _('end_date'),
							flex: 1,
							format: 'Y-m-d',
							dataIndex: 'end_date'
						}
					]
				}
			]
		},
		{
			xtype: 'fieldset',
			title: _('social_history'),
			items: [
				{
					fieldLabel: _('smoking_status'),
					xtype: 'mitos.smokingstatuscombo',
					itemId: 'reviewsmokingstatuscombo',
					allowBlank: false,
					labelWidth: 100,
					width: 325
				}
			]
		}
	],
	buttons: [
		{
			text: _('review_all'),
			name: 'review',
			itemId: 'encounterRecordAdd'
		}
	]
});