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

Ext.define('App.controller.miscellaneous.Amendments', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'AmendmentsPanel',
			selector: '#AmendmentsPanel'
		},
		{
			ref: 'AmendmentsGrid',
			selector: '#AmendmentsGrid'
		},
		{
			ref: 'AmendmentDetailsWindow',
			selector: '#AmendmentDetailsWindow'
		},
		{
			ref: 'AmendmentDetailsForm',
			selector: '#AmendmentDetailsForm'
		},
		{
			ref: 'AmendmentDetailsDataGrid',
			selector: '#AmendmentDetailsDataGrid'
		},
		{
			ref: 'AmendmentDetailsResponseMessageField',
			selector: '#AmendmentDetailsResponseMessageField'
		},
		{
			ref: 'AmendmentDetailsApproveBtn',
			selector: '#AmendmentDetailsApproveBtn'
		},
		{
			ref: 'AmendmentDetailsDenyBtn',
			selector: '#AmendmentDetailsDenyBtn'
		},
		{
			ref: 'AmendmentDetailsResponseText',
			selector: '#AmendmentDetailsResponseText'
		},
		{
			ref: 'AmendmentDetailsUserLiveSearch',
			selector: '#AmendmentDetailsUserLiveSearch'
		},
		{
			ref: 'AmendmentDetailsAssignBtn',
			selector: '#AmendmentDetailsAssignBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'#AmendmentsPanel' :{
				activate: me.onAmendmentsPanelActivate
			},
			'#AmendmentsGrid' :{
				itemdblclick: me.onAmendmentsPanelItemDblClick
			},
			'#AmendmentDetailsDenyBtn' :{
				click: me.onAmendmentDetailsDenyBtnClick
			},
			'#AmendmentDetailsApproveBtn' :{
				click: me.onAmendmentDetailsApproveBtnClick
			},
			'#AmendmentDetailsAssignBtn' :{
				click: me.onAmendmentDetailsAssignBtnClick
			}
		});

		if(a('amendments_access')){

			me.cronSkip = 2;
			var cron = App.app.getController('Cron'),
				count = 2;
			cron.checkForUnreadAmendments = function(){
				count++;
				if(count && (count % me.cronSkip !== 0)) return;
				Amendments.getUnViewedAmendments(a('amendments_view_unassigned'), function(response){
					if(response.total > 0){
						var messages = [];
						for(var i=0; i < response.data.length; i++){
							Ext.Array.push(messages, response.data[i].id);
						}
						app.notification.add(
							'new_amendment_notification',
							_('pending_amendment') + ' (' + response.total + ')',
							messages,
							'miscellaneous.Amendments',
							'onNewAmendmentClick'
						);
					}else{
						app.notification.remove('new_amendment_notification');
					}
				});
			};
			cron.addCronFn('me.checkForUnreadAmendments()');
		}
	},

	onAmendmentsPanelActivate: function(){
		var store = this.getAmendmentsGrid().getStore(),
			filters = [{
				property: 'assigned_to_uid',
				value: app.user.id
			}];

		store.clearFilter(true);

		if(a('amendments_view_unassigned')){
			Ext.Array.push(filters, {
				property: 'assigned_to_uid',
				value: '0'
			});
		}

		store.filter(filters);
	},

	updateIsViewed: function(record){
		if(record.data.is_viewed === false){
			Amendments.updateAmendment({
				id: record.data.id,
				is_viewed: true
			});
		}
	},

	updateIsRead: function(record){
		if(record.data.is_read === false){
			Amendments.updateAmendment({
				id: record.data.id,
				is_read: true
			});
		}
	},

	onNewAmendmentClick: function(){
		this.getController('Navigation').goTo('App.view.miscellaneous.Amendments');
	},

	onAmendmentsPanelItemDblClick: function(grid, record){

		if(!this.getAmendmentDetailsWindow()){
			Ext.create('App.view.miscellaneous.AmendmentDetails');
		}
		this.getAmendmentDetailsWindow().show();

		var me = this,
			form = me.getAmendmentDetailsForm().getForm(),
			dataGrid = me.getAmendmentDetailsDataGrid(),
			dataStore = dataGrid.getStore(),
			data = [];

		Ext.Object.each(record.data.amendment_data, function(key, value){
			for(var i = 0; i < value.length; i++){
				value[i].data_key = key;
				value[i].approved = true;
				Ext.Array.push(data, value[i]);
			}
		});

		dataStore.removeAll();

		if(data.length > 0){
			dataGrid.show();
			dataStore.loadData(data);
		}else{
			dataGrid.hide();

		}

		me.getAmendmentDetailsUserLiveSearch().reset();
		form.reset(true);
		form.loadRecord(record);


		if(record.data.amendment_status == 'W'){

			me.getAmendmentDetailsUserLiveSearch().setVisible(a('amendments_assign'));
			me.getAmendmentDetailsAssignBtn().setVisible(a('amendments_assign'));

			me.getAmendmentDetailsApproveBtn().setDisabled(true);
			me.getAmendmentDetailsDenyBtn().setDisabled(!a('amendments_response'));
			me.getAmendmentDetailsResponseText().hide();
			me.getAmendmentDetailsResponseText().setText('');

			app.setPatient(record.data.pid, null, function(patient){
				if(patient.pid === null){
					app.msg(_('oops'), _('patient_not_found'), true);
					me.getAmendmentDetailsApproveBtn().setDisabled(!a('amendments_response'));
					return;
				}
				me.getAmendmentDetailsApproveBtn().setDisabled(false);
				app.openPatientSummary();
			});

		}else{

			me.getAmendmentDetailsUserLiveSearch().setVisible(false);
			me.getAmendmentDetailsAssignBtn().setVisible(false);

			me.getAmendmentDetailsApproveBtn().setDisabled(true);
			me.getAmendmentDetailsDenyBtn().setDisabled(true);
			me.getAmendmentDetailsResponseText().show();

			var msg = '';
			if(record.data.amendment_status == 'A'){
				msg += _('approved') + ' - ' + Ext.Date.format(record.data.response_date, g('date_time_display_format'));
			}else if(record.data.amendment_status == 'D'){
				msg += _('denied') + ' - ' + Ext.Date.format(record.data.response_date, g('date_time_display_format'));
			}else if(record.data.amendment_status == 'C'){
				msg += _('canceled') + ' - ' + Ext.Date.format(record.data.cancel_date, g('date_time_display_format'));
			}

			me.getAmendmentDetailsResponseText().setText(msg);

		}
	},

	onAmendmentDetailsDenyBtnClick: function(){
		var form = this.getAmendmentDetailsForm().getForm(),
			record = form.getRecord(),
			messageField = this.getAmendmentDetailsResponseMessageField(),
			dataStore = this.getAmendmentDetailsDataGrid().getStore(),
			dataRecords = dataStore.data.items,
			amendment_data = {};

		messageField.allowBlank = false;

		if(!messageField.isValid()) return;

		if(dataRecords.length > 0){
			for(var i = 0; i < dataRecords.length; i++){
				dataRecords[i].set({approved: false});
				var key = dataRecords[i].data.data_key;

				if(!amendment_data[key]) amendment_data[key] = [];
				delete dataRecords[i].data.data_key;

				Ext.Array.push(amendment_data[key], dataRecords[i].data);
			}
		}

		record.set({
			amendment_data: amendment_data,
			is_synced: false,
			response_uid: app.user.id,
			response_date: new Date(),
			update_uid: app.user.id,
			update_date: new Date(),
			amendment_status: 'D',
			response_message: this.getAmendmentDetailsResponseMessageField().getValue()
		});

		record.save({
			callback: function(){
				app.msg(_('sweet'), _('record_saved'));
			}
		});

		this.getAmendmentDetailsWindow().close();
	},

	onAmendmentDetailsApproveBtnClick: function(){
		var me = this,
			form = me.getAmendmentDetailsForm().getForm(),
			record = form.getRecord(),
			messageField = me.getAmendmentDetailsResponseMessageField(),
			dataStore = me.getAmendmentDetailsDataGrid().getStore(),
			dataRecords = dataStore.data.items,
			amendment_data = {};

		messageField.allowBlank = true;


		Ext.Msg.show({
			title: _('wait'),
			msg: _('amendment_approval_confirmation'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){

					if(dataRecords.length > 0){
						for(var i = 0; i < dataRecords.length; i++){
							var key = dataRecords[i].data.data_key;
							if(!amendment_data[key]) amendment_data[key] = [];
							delete dataRecords[i].data.data_key;
							Ext.Array.push(amendment_data[key], dataRecords[i].data);
						}
					}

					record.set({
						amendment_data: amendment_data,
						is_synced: false,
						response_uid: app.user.id,
						response_date: new Date(),
						update_uid: app.user.id,
						update_date: new Date(),
						amendment_status: 'A',
						response_message: me.getAmendmentDetailsResponseMessageField().getValue()
					});

					me.doUpdatePatientData(amendment_data, record.data.pid, record.data.eid);

					record.save({
						callback: function(){
							app.msg(_('sweet'), _('record_saved'));

							app.setPatient(record.data.pid, null, function(){
								app.openPatientSummary();
							});

						}
					});

					me.getAmendmentDetailsWindow().close();
				}
			}
		});
	},

	onAmendmentDetailsAssignBtnClick: function(btn){
		var me = this,
			record = me.getAmendmentDetailsForm().getForm().getRecord(),
			searchField = me.getAmendmentDetailsUserLiveSearch(),
			assigned_user = searchField.getValue();

		if(!searchField.isValid()) return;


		me.getAmendmentDetailsWindow().mask(_('saving'));

		record.set({
			assigned_date: new Date(),
			assigned_to_uid: assigned_user
		});

		record.save({
			success: function(){
				app.msg(_('sweet'), _('amendment_assigned'));
				me.getAmendmentDetailsWindow().unmask();
				me.getAmendmentDetailsWindow().close();
			},
			failure: function(){
				app.msg(_('opps'), _('record_error'), true);
				me.getAmendmentDetailsWindow().unmask();
			}
		});

	},


	doUpdatePatientData: function(data, pid, eid){

		if(data.demographics){

			var panel = app.getActivePanel();
			if(panel.itemId == 'PatientSummaryPanel'){
				var values = {};
				for(var i = 0; i < data.demographics.length; i++){
					values[data.demographics[i].field_name] = data.demographics[i].new_value;
				}
				app.patient.record.set(values);
				app.patient.record.save();
			}
		}
	}

});