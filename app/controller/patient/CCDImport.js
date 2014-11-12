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

Ext.define('App.controller.patient.CCDImport', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'CcdImportWindow',
			selector: 'ccdimportwindow'
		},
		{
			ref: 'CcdImportPatientForm',
			selector: '#CcdImportPatientForm'
		},
		{
			ref: 'CcdImportEncounterForm',
			selector: '#CcdImportEncounterForm'
		},
		{
			ref: 'CcdImportEncounterAssessmentContainer',
			selector: '#CcdImportEncounterAssessmentContainer'
		},

		// grids...
		{
			ref: 'CcdImportMedicationsGrid',
			selector: '#CcdImportMedicationsGrid'
		},
		{
			ref: 'CcdImportAllergiesGrid',
			selector: '#CcdImportAllergiesGrid'
		},
		{
			ref: 'CcdImportProceduresGrid',
			selector: '#CcdImportProceduresGrid'
		},
		{
			ref: 'CcdImportActiveProblemsGrid',
			selector: '#CcdImportActiveProblemsGrid'
		},
		{
			ref: 'CcdImportOrderResultsGrid',
			selector: '#CcdImportOrderResultsGrid'
		},
		{
			ref: 'CcdImportEncountersGrid',
			selector: '#CcdImportEncountersGrid'
		},

		// buttons...
		{
			ref: 'CcdImportWindowImportBtn',
			selector: '#CcdImportWindowImportBtn'
		},
		{
			ref: 'CcdImportWindowCloseBtn',
			selector: '#CcdImportWindowCloseBtn'
		},
		{
			ref: 'CcdImportWindowPatientSearchField',
			selector: '#CcdImportWindowPatientSearchField'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'ccdimportwindow': {
				show: me.onCcdImportWindowShow
			},
			'#CcdImportWindowImportBtn': {
				click: me.onCcdImportWindowImportBtnClick
			},
			'#CcdImportWindowCloseBtn': {
				click: me.onCcdImportWindowCloseBtnClick
			},
			'#CcdImportWindowPatientSearchField': {
				select: me.onCcdImportWindowPatientSearchFieldSelect
			},
			'#CcdImportWindowSelectAllField': {
				change: me.onCcdImportWindowSelectAllFieldChange
			},
			'#CcdImportWindowViewRawCcdBtn': {
				click: me.onCcdImportWindowViewRawCcdBtnClick
			},
			'#PossiblePatientDuplicatesWindow > grid': {
				itemdblclick: me.onPossiblePatientDuplicatesGridItemDblClick
			},
			'#PossiblePatientDuplicatesContinueBtn': {
				click: me.onPossiblePatientDuplicatesContinueBtnClick
			}
		});

	},

	onCcdImportWindowShow: function(win){
		this.doLoadCcdData(win.ccdData);
	},

	doLoadCcdData: function(data){
		var me = this;

		var pForm = me.getCcdImportPatientForm().getForm(),
			ePanel = me.getCcdImportEncounterForm(),
			eForm = ePanel.getForm(),
			patient = Ext.create('App.model.patient.Patient', data.patient);


		if(data.encounter && !Ext.Object.isEmpty(data.encounter)){
			ePanel.show();
			var encounter = Ext.create('App.model.patient.Patient', data.encounter),
				assessmentContainer = me.getCcdImportEncounterAssessmentContainer();

			eForm.loadRecord(encounter);
			for(var i = 0; i < data.encounter.assessments.length; i++){
				assessmentContainer.add({
					anchor: '100%',
					boxLabel: data.encounter.assessments[i].text,
					assessmentData: data.encounter.assessments[i],
					boxLabelCls: 'CheckBoxWrapHammerFix'
				});
			}
		}else{
			ePanel.hide();
		}

		pForm.loadRecord(patient);

		// list 59 ethnicity
		// list 14 race
		if(data.patient.race && data.patient.race != ''){
			CombosData.getDisplayValueByListIdAndOptionValue(14, data.patient.race, function(response){
				pForm.findField('race_text').setValue(response);
			});
		}
		if(data.patient.ethnicity && data.patient.ethnicity != ''){
			CombosData.getDisplayValueByListIdAndOptionCode(59, data.patient.ethnicity, function(response){
				pForm.findField('ethnicity_text').setValue(response);
			});
		}

		if(data){
			if(data.allergies && data.allergies.length > 0){
				me.reconfigureGrid('getCcdImportAllergiesGrid', data.allergies);
			}
			if(data.medications && data.medications.length > 0){
				me.reconfigureGrid('getCcdImportMedicationsGrid', data.medications);
			}
			if(data.problems && data.problems.length > 0){
				me.reconfigureGrid('getCcdImportActiveProblemsGrid', data.problems);
			}
			if(data.procedures && data.procedures.length > 0){
				me.reconfigureGrid('getCcdImportProceduresGrid', data.procedures);
			}
			if(data.results && data.results.length > 0){
				me.reconfigureGrid('getCcdImportOrderResultsGrid', data.results);
			}
			if(data.encounters && data.encounters.length > 0){
				me.reconfigureGrid('getCcdImportEncountersGrid', data.encounters);
			}
		}
	},

	reconfigureGrid: function(getter, data){
		var me = this,
			grid = me[getter]();
		grid.getStore().loadRawData(data);
	},

	onCcdImportWindowPatientSearchFieldSelect: function(cmb, records){
		var me = this,
			patient = me.getCcdImportPatientForm().getForm().getRecord();

		if(patient.data.sex != records[0].data.sex){
			app.msg(_('warning'), _('records_sex_are_not_equal'), true);
		}

		if(patient.data.DOB.getFullYear() != records[0].data.DOB.getFullYear() &&
			patient.data.DOB.getMonth() != records[0].data.DOB.getMonth() &&
			patient.data.DOB.getDate() != records[0].data.DOB.getDate()){
			app.msg(_('warning'), _('records_date_of_birth_are_not_equal'), true);
		}

	},

	// TODO
	doResultShowObservations: function(row, store){

		say('doResultShowObservations');
		say(row);
		say(store);
		say(row.getXY());

		var win = this.getResultObservationGrid();
		win.down('grid').reconfigure(store);
		win.show(row);
	},

	getResultObservationGrid: function(){
		return Ext.widget('window',{
			title: _('observations'),
			modal: true,
			items:[
				{
					xtype: 'grid',
					width: 900,
					margin: 5,
					frame: true,
					columns:[
						{
							text: _('name'),
							menuDisabled: true,
							dataIndex: 'code_text',
							width: 200
						},
						{
							text: _('value'),
							menuDisabled: true,
							dataIndex: 'value',
							width: 150,
							renderer: function(v, meta, record){
								var red = ['LL', 'HH', '>', '<', 'AA', 'VS'],
									orange = ['L', 'H', 'A', 'W', 'MS'],
									blue = ['B', 'S', 'U', 'D', 'R', 'I'],
									green = ['N'];

								if(Ext.Array.contains(green, record.data.abnormal_flag)){
									return '<span style="color:green;">' + v + '</span>';
								}else if(Ext.Array.contains(blue, record.data.abnormal_flag)){
									return '<span style="color:blue;">' + v + '</span>';
								}else if(Ext.Array.contains(orange, record.data.abnormal_flag)){
									return '<span style="color:orange;">' + v + '</span>';
								}else if(Ext.Array.contains(red, record.data.abnormal_flag)){
									return '<span style="color:red;">' + v + '</span>';
								}else{
									return v;
								}
							}
						},
						{
							text: _('units'),
							menuDisabled: true,
							dataIndex: 'units',
							width: 75
						},
						{
							text: _('abnormal'),
							menuDisabled: true,
							dataIndex: 'abnormal_flag',
							width: 75,
							renderer: function(v, attr){
								var red = ['LL', 'HH', '>', '<', 'AA', 'VS'],
									orange = ['L', 'H', 'A', 'W', 'MS'],
									blue = ['B', 'S', 'U', 'D', 'R', 'I'],
									green = ['N'];

								if(Ext.Array.contains(green, v)){
									return '<span style="color:green;">' + v + '</span>';
								}else if(Ext.Array.contains(blue, v)){
									return '<span style="color:blue;">' + v + '</span>';
								}else if(Ext.Array.contains(orange, v)){
									return '<span style="color:orange;">' + v + '</span>';
								}else if(Ext.Array.contains(red, v)){
									return '<span style="color:red;">' + v + '</span>';
								}else{
									return v;
								}
							}
						},
						{
							text: _('range'),
							menuDisabled: true,
							dataIndex: 'reference_rage',
							width: 150
						},
						{
							text: _('notes'),
							menuDisabled: true,
							dataIndex: 'notes',
							width: 300
						},
						{
							text: _('status'),
							menuDisabled: true,
							dataIndex: 'observation_result_status',
							width: 60
						}
					]
				}
			]
		})
	},

	onCcdImportWindowCloseBtnClick: function(){
		this.getCcdImportWindow().close();
	},

	verifyPatientImport:function(){
		var me = this,
			pid = me.getCcdImportWindowPatientSearchField().getValue();

		Ext.Msg.show({
			title: _('wait'),
			msg: pid ? _('patient_merge_verification') : _('patient_import_verification'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					if(pid){
						me.doPatientSectionsImport(pid);
					}else{
						me.doPatientImport();
					}
				}
			}
		});
	},

	doPatientImport: function(){
		var me = this,
			patient = me.getCcdImportPatientForm().getForm().getRecord();

		patient.set({
			create_uid: app.user.id,
			create_date: new Date()
		});

		patient.save({
			callback:function(record, operation, success){
				if(success){
					me.doPatientSectionsImport(record.data.pid);
				}else{
					app.msg(_('oops'), _('record_error'), true);
				}
			}
		});
	},

	doPatientSectionsImport: function(pid){
		var me = this,
			date = new Date(),
			encounter = me.getCcdImportEncounterForm().getForm().getRecord(),

			// mandatory...
			allergies = me.getCcdImportAllergiesGrid().getSelectionModel().getSelection(),
			medications = me.getCcdImportMedicationsGrid().getSelectionModel().getSelection(),
			problems = me.getCcdImportActiveProblemsGrid().getSelectionModel().getSelection(),

			//optional...
			procedures = me.getCcdImportProceduresGrid().getSelectionModel().getSelection(),
			results = me.getCcdImportOrderResultsGrid().getSelectionModel().getSelection(),
			encounters = me.getCcdImportEncountersGrid().getSelectionModel().getSelection(),
			i,
			len;

		// allergies
		len = allergies.length;
		for(i = 0; i < len; i++){
			allergies[i].set({
				pid: pid,
				created_uid: app.patient.id,
				create_date: date
			});
			allergies[i].save();
		}

		// medications
		len = medications.length;
		for(i = 0; i < len; i++){
			medications[i].set({
				pid: pid,
				created_uid: app.patient.id,
				create_date: date
			});
			medications[i].save();
		}

		// problems
		len = problems.length;
		for(i = 0; i < len; i++){
			problems[i].set({
				pid: pid,
				created_uid: app.patient.id,
				create_date: date
			});

			problems[i].save({
				callback: function(){
					me.getCcdImportWindow().close();
					app.setPatient(pid, null, function(){
						app.openPatientSummary();
					});
					app.msg(_('sweet'), _('patient_data_imported'));
				}
			});
		}
	},

	onPossiblePatientDuplicatesGridItemDblClick:function(grid, record){
		var win = grid.up('window');
		if(win.action != 'ccdImportDuplicateAction') return;

		var me = this,
			cmb = me.getCcdImportWindowPatientSearchField(),
			store = cmb.getStore(),
			records;

		store.removeAll();
		records = store.add({
			pid: record.data.pid,
			fname: record.data.fname,
			mname: record.data.mname,
			lname: record.data.lname
		});

		cmb.select(records[0]);
		win.close();
		me.verifyPatientImport();
	},

	onCcdImportWindowImportBtnClick: function(){
		var me = this,
			patientCtrl = App.app.getController('patient.Patient'),
			data = me.getCcdImportPatientForm().getRecord().data,
			pid = me.getCcdImportWindowPatientSearchField().getValue(),
			params = {
				fname: data.fname,
				lname: data.lname,
				sex: data.sex,
				DOB: data.DOB
			};

		if(pid){
			me.verifyPatientImport();
		}else{
			patientCtrl.lookForPossibleDuplicates(params, 'ccdImportDuplicateAction', function(records){
				if(records == 0){
					me.verifyPatientImport();
				}
			});
		}
	},

	onPossiblePatientDuplicatesContinueBtnClick:function(btn){
		if(btn.up('window').action != 'ccdImportDuplicateAction') return;
		this.verifyPatientImport();
	},

	onCcdImportWindowSelectAllFieldChange: function(field, selected){
		var me = this,
			grids = me.getCcdImportWindow().query('grid');

		for(var i = 0; i < grids.length; i++){
			var sm = grids[i].getSelectionModel();
			if(selected){
				sm.selectAll();
			}else{
				sm.deselectAll();
			}
		}

		if(me.getCcdImportEncounterAssessmentContainer()){
			var checkboxes = me.getCcdImportEncounterAssessmentContainer().query('checkbox');

			for(var j = 0; j < checkboxes.length; j++){
				checkboxes[j].setValue(selected);
			}
		}
	},

	onCcdImportWindowViewRawCcdBtnClick: function(){
		var me = this,
			record = Ext.create('App.model.patient.PatientDocumentsTemp', {
				create_date: new Date(),
				document_name: 'temp_ccd.xml',
				document: me.getCcdImportWindow().ccd
			});

		record.save({
			callback: function(record){
				app.onDocumentView(record.data.id, 'ccd');
				say(record.data.id);

			}
		});
	}
});