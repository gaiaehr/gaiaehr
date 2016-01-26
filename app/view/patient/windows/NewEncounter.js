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

Ext.define('App.view.patient.windows.NewEncounter', {
	extend: 'Ext.window.Window',
	itemId: 'EncounterDetailWindow',
	title: _('encounter'),
	closeAction: 'hide',
	closable: false,
	modal: true,
	width: 660,

	initComponent: function(){
		var me = this;

		me.store = Ext.create('App.store.patient.Encounters');

		Ext.apply(me, {
			items: [
				me.encForm = Ext.create('Ext.form.Panel', {
					itemId: 'EncounterDetailForm',
					border: false,
					bodyPadding: '10 10 0 10'
				})
			],
			buttons: [
				{
					text: _('save'),
					action: 'encounter',
					scope: me,
					handler: me.onFormSave
				},
				{
					text: _('cancel'),
					scope: me,
					handler: me.cancelNewEnc
				}
			],
			listeners: {
				show: me.checkValidation,
				hide: me.resetRecord
			}
		}, me);

		me.getFormItems(this.encForm, 5);

		me.callParent(arguments);
	},

	checkValidation: function()
    {
        var me = this,
            form = me.down('form').getForm(),
            record = form.getRecord();
        
		if(app.patient.pid)
        {
			if(!record && a('add_encounters')){

				me.loadRecord(
					Ext.create('App.model.patient.Encounter', {
						pid: app.patient.pid,
						service_date: new Date(),
						priority: 'Minimal',
						open_uid: app.user.id,
						facility: app.user.facility,
						billing_facility: app.user.facility,
						brief_description: g('default_chief_complaint')
					})
				);

				Encounter.checkOpenEncountersByPid(app.patient.pid, function(provider, response){
					if(response.result.encounter){
						Ext.Msg.show({
							title: 'Oops! ' + _('open_encounters_found') + '...',
							msg: _('do_you_want_to') + ' <strong>' + _('continue_creating_the_new_encounters') + '</strong><br>"' + _('click_no_to_review_encounter_history') + '"',
							buttons: Ext.Msg.YESNO,
							icon: Ext.Msg.QUESTION,
							fn: function(btn){
								if(btn != 'yes'){
									me.hide();
									form.reset();
								}
							}
						});
					}
				});
			} else if(record && a('edit_encounters')){

				// placeholder

			} else{
				app.accessDenied();
			}
		}else{
			app.currPatientError();
		}
	},

	onFormSave: function(btn){
		var me = this,
			form = me.encForm.getForm(),
			values = form.getValues(),
			record = form.getRecord(),
			isNew = record.data.eid === 0;
		if(form.isValid()){
			if((isNew && a('add_encounters') || (!isNew && a('edit_encounters')))){
				record.set(values);

				record.save({
					callback: function(record){
						if(isNew){
							var data = record.data;
							app.patientButtonRemoveCls();
							app.patientBtn.addCls(data.priority);
							app.openEncounter(data.eid);
						}
						me.close();
					}
				});
			}else{
				btn.up('window').close();
				app.accessDenied();
			}
		}
	},

	loadRecord: function(record){
		this.encForm.getForm().loadRecord(record);
	},

	resetRecord: function(){
		this.down('form').getForm().reset(true);
		delete this.down('form').getForm()._record;
	},

	cancelNewEnc: function(){
		this.close();
	}

});
