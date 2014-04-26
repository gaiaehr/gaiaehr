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
	eid: null,
	autoScroll: true,
	initComponent: function(){
		var me = this;

		me.patientImmuListStore = Ext.create('App.store.patient.PatientImmunization');
		me.patientAllergiesListStore = Ext.create('App.store.patient.Allergies');
		me.patientActiveProblemsStore = Ext.create('App.store.patient.PatientActiveProblems');
		me.patientMedicationsStore = Ext.create('App.store.patient.Medications');

		me.items = [
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
						title: i18n('immunizations'),
						frame: true,
						height: 180,
						flex:1,
						store: me.patientImmuListStore,
						margin: '0 5 5 0',
						columns: [
							{
								header: i18n('immunization'),
								width: 250,
								dataIndex: 'vaccine_name'
							},
							{
								header: i18n('date'),
								width: 90,
								xtype: 'datecolumn',
								format: 'Y-m-d',
								dataIndex: 'administered_date'
							},
							{
								header: i18n('notes'),
								flex: 1,
								dataIndex: 'note'
							}
						]
					},
					{
						title: i18n('allergies'),
						frame: true,
						height: 180,
						flex:1,
						store: me.patientAllergiesListStore,
						columns: [
							{
								header: i18n('type'),
								width: 100,
								dataIndex: 'allergy_type'
							},
							{
								header: i18n('name'),
								width: 100,
								dataIndex: 'allergy'
							},
							{
								header: i18n('severity'),
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
						title: i18n('active_problems'),
						frame: true,
						height: 180,
						flex:1,
						margin: '0 5 5 0',
						store: me.patientActiveProblemsStore,
						columns: [
							{
								header: i18n('problem'),
								width: 250,
								dataIndex: 'code'
							},
							{
								xtype: 'datecolumn',
								header: i18n('begin_date'),
								width: 90,
								format: 'Y-m-d',
								dataIndex: 'begin_date'
							},
							{
								xtype: 'datecolumn',
								header: i18n('end_date'),
								flex: 1,
								format: 'Y-m-d',
								dataIndex: 'end_date'
							}
						]
					},
					{
						title: i18n('medications'),
						frame: true,
						height: 180,
						flex:1,
						store: me.patientMedicationsStore,
						columns: [
							{
								header: i18n('medication'),
								width: 250,
								dataIndex: 'STR'
							},
							{
								xtype: 'datecolumn',
								header: i18n('begin_date'),
								width: 90,
								format: 'Y-m-d',
								dataIndex: 'begin_date'
							},
							{
								xtype: 'datecolumn',
								header: i18n('end_date'),
								flex: 1,
								format: 'Y-m-d',
								dataIndex: 'end_date'
							}
						]
					}
				]
			},
			{
				xtype: 'form',
				border: false,
				items: [
					{
						xtype: 'fieldset',
						title: i18n('live_styles'),
						items: [
							{
								xtype: 'fieldcontainer',
								layout: 'hbox',
								items: [
									{
										fieldLabel: i18n('smoking_status'),
										xtype: 'mitos.smokingstatuscombo',
										labelWidth: 100,
										width: 325,
										name: 'review_smoke'
									},
									{
										xtype: 'mitos.smokingstatuscombo',
										fieldLabel: i18n('smoke_history'),
										name: 'last_history_smoke',
										labelWidth: 85,
										margin: '0 5',
										width: 325,
										submitValue: false
									},
									{
										xtype: 'displayfield',
										labelWidth: 40,
										fieldLabel: i18n('date'),
										name: 'last_history_smoke_date',
										submitValue: false
									}
								]
							},
							{
								fieldLabel: i18n('alcohol'),
								xtype: 'mitos.yesnocombo',
								labelWidth: 100,
								width: 325,
								name: 'review_alcohol'
							},
							{
								fieldLabel: i18n('pregnant'),
								xtype: 'mitos.yesnonacombo',
								labelWidth: 100,
								width: 325,
								name: 'review_pregnant'
							}
						]
					}

				]
			}
		];

		me.buttons = [
			{
				text: i18n('review_all'),
				name: 'review',
				itemId: 'encounterRecordAdd',
				scope: me,
				handler: me.onReviewAll
			}
		];

		me.listeners = {
			show: me.storesLoad
		};

		me.callParent(arguments);
	},
	storesLoad: function(){
		var me = this,
			form = me.down('form').getForm(),
			params = {
				// old way
				params: {
					pid: app.patient.pid
				},
				// new way
				filters: [
					{
						property: 'pid',
						value: app.patient.pid
					}
				]
			};

		me.patientImmuListStore.load(params);
		me.patientAllergiesListStore.load(params);
		me.patientActiveProblemsStore.load(params);
		me.patientMedicationsStore.load(params);

		Medical.getEncounterReviewByEid(app.patient.eid, function(provider, response){
			if(response.result.last_history_smoke_date == '') response.result.last_history_smoke_date = i18n('n/a');
			form.setValues(response.result);
			form.findField('last_history_smoke').setReadOnly(true);
		});
	},
	onReviewAll: function(){
		var me = this,
			panel = me.down('form'),
			form = panel.getForm(),
			values = form.getFieldValues();

		values.eid = app.patient.eid;
		if(form.isValid()){
			Medical.reviewAllMedicalWindowEncounter(values, function(provider, response){
				if(response.result.success){
					app.msg('Sweet!', i18n('items_to_review_save_and_review'));
				}else{
					app.msg('Oops!', i18n('items_to_review_entry_error'))
				}
			});
		}
	}
});