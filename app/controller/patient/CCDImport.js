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
        'App.view.patient.windows.PossibleDuplicates'
	],
	refs: [
		{
			ref: 'CcdImportWindow',
			selector: 'ccdimportwindow'
		},
		{
			ref: 'CcdImportPreviewWindow',
			selector: 'ccdimportpreviewwindow'
		},

		// import patient...
		{
			ref: 'CcdImportPatientForm',
			selector: '#CcdImportPatientForm'
		},
		{
			ref: 'CcdImportActiveProblemsGrid',
			selector: '#CcdImportActiveProblemsGrid'
		},
		{
			ref: 'CcdImportMedicationsGrid',
			selector: '#CcdImportMedicationsGrid'
		},
		{
			ref: 'CcdImportAllergiesGrid',
			selector: '#CcdImportAllergiesGrid'
		},


		// marge patient...
		{
			ref: 'CcdPatientPatientForm',
			selector: '#CcdPatientPatientForm'
		},
		{
			ref: 'CcdPatientActiveProblemsGrid',
			selector: '#CcdPatientActiveProblemsGrid'
		},
		{
			ref: 'CcdPatientMedicationsGrid',
			selector: '#CcdPatientMedicationsGrid'
		},
		{
			ref: 'CcdPatientAllergiesGrid',
			selector: '#CcdPatientAllergiesGrid'
		},


		// preview patient...
		{
			ref: 'CcdImportPreviewPatientForm',
			selector: '#CcdImportPreviewPatientForm'
		},
		{
			ref: 'CcdImportPreviewActiveProblemsGrid',
			selector: '#CcdImportPreviewActiveProblemsGrid'
		},
		{
			ref: 'CcdImportPreviewMedicationsGrid',
			selector: '#CcdImportPreviewMedicationsGrid'
		},
		{
			ref: 'CcdImportPreviewAllergiesGrid',
			selector: '#CcdImportPreviewAllergiesGrid'
		},


		// buttons...
		{
			ref: 'CcdImportWindowPreviewBtn',
			selector: '#CcdImportWindowPreviewBtn'
		},
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
			'#CcdImportPreviewWindowImportBtn': {
				click: me.onCcdImportPreviewWindowImportBtnClick
			},
			'#CcdImportWindowPreviewBtn': {
				click: me.onCcdImportWindowPreviewBtnClick
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
			},
			'#CcdImportPreviewWindowCancelBtn': {
				click: me.onCcdImportPreviewWindowCancelBtnClick
			}
		});
	},

	CcdImport: function(ccdData){
		if(!this.getCcdImportWindow()){
			Ext.create('App.view.patient.windows.CCDImport');
		}
		this.getCcdImportWindow().ccdData = ccdData;
		this.getCcdImportWindow().show();
	},

	onCcdImportWindowShow: function(win){
		this.doLoadCcdData(win.ccdData);
	},

    /*
    Event when the CDA Import and Viewer shows up.
    Also will check for duplicates in the database and if a posible duplicate is found
    show the posible duplicate window
     */
	doLoadCcdData: function(data){
		var me = this,
			ccdPatientForm = me.getCcdImportPatientForm().getForm(),
			patient = Ext.create('App.model.patient.Patient', data.patient),
            phone;
        ccdPatientForm.loadRecord(patient);

        App.app.getController('patient.Patient').lookForPossibleDuplicates(
            {
                fname: patient.data.fname,
                lname: patient.data.lname,
                sex: patient.data.sex,
                DOB: patient.data.DOB
            },
            'ccdImportDuplicateAction',
            function(patient) {
            }
        );

		// list 59 ethnicity
		// list 14 race
        // phone from Patient Contacts
		if(data.patient.race && data.patient.race !== ''){
			CombosData.getDisplayValueByListIdAndOptionValue(14, data.patient.race, function(response){
                ccdPatientForm.findField('race_text').setValue(response);
			});
		}

		if(data.patient.ethnicity && data.patient.ethnicity !== ''){
			CombosData.getDisplayValueByListIdAndOptionCode(59, data.patient.ethnicity, function(response){
                ccdPatientForm.findField('ethnicity_text').setValue(response);
			});
		}

        if(data.patient.pid && data.patient.pid !== '') {
            PatientContacts.getSelfContact(data.patient.pid, function (response) {
                phone = response.data.phone_use_code + '-' + response.data.phone_area_code + '-' + response.data.phone_local_number
                ccdPatientForm.findField('phones').setValue(phone);
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
		}
	},

    /*
     Event fired when in the duplicate window data grid double click on an item
     this method will copy the patient information selected from the data grid, into
     the system information panel.
     */
    onPossiblePatientDuplicatesGridItemDblClick:function(grid, record){
        var me = this,
            cmb = me.getCcdImportWindowPatientSearchField(),
            systemPatientForm = me.getCcdPatientPatientForm().getForm(),
            store = cmb.getStore(),
            win = grid.up('window');

        if(win.action != 'ccdImportDuplicateAction') return;

        store.removeAll();
        me.doLoadMergePatientData(record.data.pid);
        cmb.select(record);
        win.close();
        me.promptVerifyPatientImport();
    },

	reconfigureGrid: function(getter, data){
		var me = this,
			grid = me[getter]();
		grid.getStore().loadRawData(data);
	},

	onCcdImportWindowPatientSearchFieldSelect: function(cmb, records){
		var me = this,
			importPatient = me.getCcdImportPatientForm().getForm().getRecord();

		if(importPatient.data.sex != records[0].data.sex){
			app.msg(_('warning'), _('records_sex_are_not_equal'), true);
		}

		if(importPatient.data.DOB.getFullYear() != records[0].data.DOB.getFullYear() &&
			importPatient.data.DOB.getMonth() != records[0].data.DOB.getMonth() &&
			importPatient.data.DOB.getDate() != records[0].data.DOB.getDate()){
			app.msg(_('warning'), _('records_date_of_birth_are_not_equal'), true);
		}

		me.doLoadMergePatientData(records[0].data.pid);

	},

	doLoadMergePatientData: function(pid){
		var me = this,
			pForm = me.getCcdPatientPatientForm().getForm(),
            phone;

		App.model.patient.Patient.load(pid, {
			success: function(patient) {

				pForm.loadRecord(patient);
				if(patient.data.race && patient.data.race !== ''){
					CombosData.getDisplayValueByListIdAndOptionValue(14, patient.data.race, function(response){
						pForm.findField('race_text').setValue(response);
					});
				}

				if(patient.data.ethnicity && patient.data.ethnicity !== ''){
					CombosData.getDisplayValueByListIdAndOptionValue(59, patient.data.ethnicity, function(response){
						pForm.findField('ethnicity_text').setValue(response);
					});
				}

                if(patient.data.pid && patient.data.pid !== '') {
                    PatientContacts.getSelfContact(patient.data.pid, function (response) {
                        say(response);
                        phone = response.data.phone_use_code + '-' + response.data.phone_area_code + '-' + response.data.phone_local_number
                        pForm.findField('phones').setValue(phone);
                    });
                }

				me.getCcdPatientMedicationsGrid().reconfigure(patient.medications());
				patient.medications().load({
					params: { reconciled: true }
				});

				me.getCcdPatientAllergiesGrid().reconfigure(patient.allergies());
				patient.allergies().load();

				me.getCcdPatientActiveProblemsGrid().reconfigure(patient.activeproblems());
				patient.activeproblems().load();
			}
		});
	},

	onCcdImportWindowCloseBtnClick: function(){
		this.getCcdImportWindow().close();
	},

	onCcdImportWindowPreviewBtnClick: function(){
		var me = this,

			reconcile = true,

			pForm,
			importPatient = me.getCcdImportPatientForm().getForm().getRecord(),
			importActiveProblems = me.getCcdImportActiveProblemsGrid().getSelectionModel().getSelection(),
			importMedications = me.getCcdImportMedicationsGrid().getSelectionModel().getSelection(),
			importAllergies = me.getCcdImportAllergiesGrid().getSelectionModel().getSelection(),

			mergePatient = me.getCcdPatientPatientForm().getForm().getRecord(),
			mergeActiveProblems = me.getCcdPatientActiveProblemsGrid().getStore().data.items,
			mergeMedications = me.getCcdPatientMedicationsGrid().getStore().data.items,
			mergeAllergies = me.getCcdPatientAllergiesGrid().getStore().data.items,

			isMerge = mergePatient !== undefined,

			i, store, records,

            phone;

		// check is merge and nothing is selected
		if(
			isMerge &&
			importActiveProblems.length === 0 &&
			importMedications.length === 0 &&
			importAllergies.length === 0
		){
			app.msg(_('oops'), _('nothing_to_merge'), true);
			return;
		}

		if(!me.getCcdImportPreviewWindow()){
			Ext.create('App.view.patient.windows.CCDImportPreview');
		}
		me.getCcdImportPreviewWindow().show();

		pForm = me.getCcdImportPreviewPatientForm().getForm();

		if(isMerge){
			me.getCcdImportPreviewPatientForm().getForm().loadRecord(mergePatient);

			if(mergePatient.data.race && mergePatient.data.race !== ''){
				CombosData.getDisplayValueByListIdAndOptionValue(14, mergePatient.data.race, function(response){
					pForm.findField('race_text').setValue(response);
				});
			}

			if(mergePatient.data.ethnicity && mergePatient.data.ethnicity !== ''){
				CombosData.getDisplayValueByListIdAndOptionValue(59, mergePatient.data.ethnicity, function(response){
					pForm.findField('ethnicity_text').setValue(response);
				});
			}

            if(mergePatient.data.pid && mergePatient.data.pid !== '') {
                PatientContacts.getSelfContact(mergePatient.data.pid, function (response) {
                    phone = response.data.phone_use_code + '-' + response.data.phone_area_code + '-' + response.data.phone_local_number
                    pForm.findField('phones').setValue(phone);
                });
            }
		}else{
			me.getCcdImportPreviewPatientForm().getForm().loadRecord(importPatient);

			if(importPatient.data.race && importPatient.data.race !== ''){
				CombosData.getDisplayValueByListIdAndOptionValue(14, importPatient.data.race, function(response){
					pForm.findField('race_text').setValue(response);
				});
			}

			if(importPatient.data.ethnicity && importPatient.data.ethnicity !== ''){
				CombosData.getDisplayValueByListIdAndOptionCode(59, importPatient.data.ethnicity, function(response){
					pForm.findField('ethnicity_text').setValue(response);
				});
			}
            if(importPatient.data.pid && importPatient.data.pid !== '') {
                PatientContacts.getSelfContact(importPatient.data.pid, function (response) {
                    phone = response.data.phone_use_code + '-' + response.data.phone_area_code + '-' + response.data.phone_local_number
                    pForm.findField('phones').setValue(phone);
                });
            }
		}

		if(reconcile){
			// reconcile active problems
			records = Ext.clone(mergeActiveProblems);
			store = me.getCcdPatientActiveProblemsGrid().getStore();
			for(i=0; i < importActiveProblems.length; i++){
				if(store.find('code' , importActiveProblems[i].data.code) !== -1) continue;
				Ext.Array.insert(records, 0, [importActiveProblems[i]]);
			}
			me.getCcdImportPreviewActiveProblemsGrid().getStore().loadRecords(records);

			// reconcile medications
			records = Ext.clone(mergeMedications);
			store = me.getCcdPatientMedicationsGrid().getStore();
			for(i=0; i < importMedications.length; i++){
				if(store.find('RXCUI' , importMedications[i].data.RXCUI) !== -1) continue;
				Ext.Array.insert(records, 0, [importMedications[i]]);
			}
			me.getCcdImportPreviewMedicationsGrid().getStore().loadRecords(records);

			// reconcile allergies
			records = Ext.clone(mergeAllergies);
			store = me.getCcdPatientAllergiesGrid().getStore();
			for(i=0; i < importAllergies.length; i++){
				if(store.find('allergy_code' , importAllergies[i].data.allergy_code) !== -1) continue;
				Ext.Array.insert(records, 0, [importAllergies[i]]);
			}
			me.getCcdImportPreviewAllergiesGrid().getStore().loadRecords(records);

		}else{
			me.getCcdImportPreviewActiveProblemsGrid().getStore().loadRecords(
				Ext.Array.merge(importActiveProblems, mergeActiveProblems)
			);
			me.getCcdImportPreviewMedicationsGrid().getStore().loadRecords(
				Ext.Array.merge(importMedications, mergeMedications)
			);
			me.getCcdImportPreviewAllergiesGrid().getStore().loadRecords(
				Ext.Array.merge(importAllergies, mergeAllergies)
			);
		}
	},

	onCcdImportPreviewWindowImportBtnClick: function(){
		var me = this,
			patient = me.getCcdImportPreviewPatientForm().getRecord();

		if(patient.data.pid){
			me.promptVerifyPatientImport(patient);
		}else{
			App.app.getController('patient.Patient').lookForPossibleDuplicates(
				{
					fname: patient.data.fname,
					lname: patient.data.lname,
					sex: patient.data.sex,
					DOB: patient.data.DOB
				},
				'ccdImportDuplicateAction',
				function(records){
					if(records.length === 0){
						me.promptVerifyPatientImport(patient);
					}
				}
			);
		}
	},

	promptVerifyPatientImport:function(patient){
		var me = this;

		Ext.Msg.show({
			title: _('wait'),
			msg: patient.data.pid ? _('patient_merge_verification') : _('patient_import_verification'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					if(patient.data.pid){
						me.doPatientSectionsImport(patient);
					}else{
						me.doPatientImport(patient);
					}
				}
			}
		});
	},

	doPatientImport: function(patient){
		var me = this;

		patient.set({
			create_uid: app.user.id,
			create_date: new Date()
		});

		patient.save({
			callback:function(record, operation, success){
				if(success){
					me.doPatientSectionsImport(record);
				}else{
					app.msg(_('oops'), _('record_error'), true);
				}
			}
		});
	},

	onCcdImportPreviewWindowCancelBtnClick: function(btn){
		btn.up('window').close();
	},

	doPatientSectionsImport: function(patient){
		var me = this,
			now = new Date(),
			pid = patient.data.pid,

		// Get all the stores of the dataGrids
			problems = me.getCcdImportPreviewActiveProblemsGrid().getStore().data.items,
			medications = me.getCcdImportPreviewMedicationsGrid().getStore().data.items,
			allergies = me.getCcdImportPreviewAllergiesGrid().getStore().data.items;

		// Allergies
		for(Index = 0; Index < allergies.length; Index++){

			if(allergies[Index].data.id && allergies[Index].data.id > 0)  continue;

			allergies[Index].set({
                pid: pid,
                created_uid: app.patient.id,
                create_date: now
            });
            allergies[Index].setDirty();
			allergies[Index].save();
		}

		// Medications
		for(Index = 0; Index < medications.length; Index++){

			if(medications[Index].data.id && medications[Index].data.id > 0)  continue;

			medications[Index].set({
				pid: pid,
				created_uid: app.patient.id,
				create_date: now
			});
            medications[Index].setDirty();
			medications[Index].save();
		}

		// Problems
		for(Index = 0; Index < problems.length; Index++){

			if(problems[Index].data.id && problems[Index].data.id > 0)  continue;

			problems[Index].set({
				pid: pid,
				created_uid: app.patient.id,
				create_date: now
			});
            problems[Index].setDirty();
			problems[Index].save({
				callback: function(){

					me.getCcdImportWindow().close();
					me.getCcdImportPreviewWindow().close();

					app.setPatient(pid, null, function(){
						app.openPatientSummary();
					});

					app.msg(_('sweet'), _('patient_data_imported'));
				}
			});
		}
	},

	onPossiblePatientDuplicatesContinueBtnClick:function(btn){
		if(btn.up('window').action != 'ccdImportDuplicateAction') return;
        if(this.getCcdImportPreviewPatientForm()){

        }
		this.promptVerifyPatientImport(this.getCcdImportPreviewPatientForm().getRecord());
	},

	onCcdImportWindowSelectAllFieldChange: function(field, selected){
		var me = this,
			grids = me.getCcdImportWindow().query('grid');

		for(var Index = 0; Index < grids.length; Index++){
			var sm = grids[Index].getSelectionModel();
			if(selected){
				sm.selectAll();
			}else{
				sm.deselectAll();
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