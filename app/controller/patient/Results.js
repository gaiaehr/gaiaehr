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

Ext.define('App.controller.patient.Results',
{
	extend: 'Ext.app.Controller',
	requires: [
		'App.view.administration.HL7MessageViewer'
	],
	refs: [
		{
			ref: 'resultsPanel',
			selector: 'patientresultspanel'
		},
		{
			ref: 'resultForm',
			selector: 'patientresultspanel #OrderResultForm'
		},
		{
			ref: 'observationsGrid',
			selector: 'patientresultspanel #observationsGrid'
		},
		{
			ref: 'ordersGrid',
			selector: 'patientresultspanel > grid[action=orders]'
		},
		{
			ref: 'uploadField',
			selector: 'filefield[action=orderresultuploadfield]'
		},
		{
			ref: 'messageField',
			selector: 'hl7messageviewer > textareafield[action=message]'
		},
		{
			ref: 'acknowledgeField',
			selector: 'hl7messageviewer > textareafield[action=acknowledge]'
		},
		{
			ref: 'OrderResultSignBtn',
			selector: '#OrderResultSignBtn'
		},
        {
            ref: 'DocumentTypeCard',
            selector: 'patientresultspanel > #documentTypeCard'
        },
        {
            ref: 'LaboratoryResultPanel',
            selector: '#laboratoryResultPanel'
        },
        {
            ref: 'LaboratoryResultForm',
            selector: '#laboratoryResultForm'
        },
        {
            ref: 'NewOrderResultBtn',
            selector: '#NewOrderResultBtn'
        }
	],

	init: function()
    {
		var me = this;
		me.control({
			'patientresultspanel': {
				activate: me.onResultPanelActive
			},
			'patientresultspanel > grid[action=orders]': {
				selectionchange: me.onOrderSelectionChange,
				edit: me.onOrderSelectionEdit
			},
			'filefield[action=orderresultuploadfield]': {
				change: me.onOrderDocumentChange
			},
			'button[action=orderResultResetBtn]': {
				click: me.onResetOrderResultClicked
			},
			'button[action=orderResultSaveBtn]': {
				click: me.onSaveOrderResultClicked
			},
			'button[action=orderDocumentViewBtn]': {
				click: me.onOrderDocumentViewBtnClicked
			},
			'#NewOrderResultBtn': {
				click: me.onNewOrderResultBtnClick
			},
			'#OrderResultSignBtn': {
				click: me.onOrderResultSignBtnClick
			},
            '#orderTypeCombo':{
                change: me.onOrderTypeSelect
            },
            '#resultRowEditor':{
                beforeedit: me.onOrderResultGridRowEdit
            }
		});
	},

	onOrderResultSignBtnClick: function()
    {
		var me = this,
            record;

		app.passwordVerificationWin(function(btn, password)
        {
			if(btn == 'ok')
            {
				User.verifyUserPass(password, function(success){
					if(success)
                    {
                        record = me.getLaboratoryResultForm().getRecord();
						record.set({signed_uid: app.user.id});
						record.save({
							success: function()
                            {
								app.msg(_('sweet'), _('result_signed'));
							},
							failure: function()
                            {
								app.msg(_('sweet'), _('record_error'), true);
							}
						});
					}
                    else
                    {
						me.onOrderResultSignBtnClick();
					}
				});
			}
		});
	},

	onOrderSelectionEdit: function(editor, e)
    {
		this.getOrderResult(e.record);
	},

    onNewOrderResultBtnClick: function(btn){
		var grid = btn.up('grid'),
			store = grid.getStore(),
			records,
            fields;
		grid.editingPlugin.cancelEdit();
		records = store.add({
			pid: app.patient.pid,
			uid: app.user.id,
            order_type: 'lab',
			status: 'Pending'
		});
		grid.getPlugin('resultRowEditor').startEdit(records[0], 0);

        // Focus the second column when editing.
        fields = grid.getPlugin('resultRowEditor').getEditor();
        fields.items.items[2].focus();
        fields.items.items[1].setValue('lab');

        // By Default when adding a new record, it will be a Laboratory
        grid.columns[3].setEditor({
            xtype: 'labslivetsearch',
            itemId: 'labOrderLiveSearch',
            allowBlank: false,
            flex: 1
        });
    },

    onOrderResultGridRowEdit: function(editor, context, eOpts)
    {
        //say(context);
    },

    onOrderTypeSelect: function(combo, newValue, oldValue, eOpts)
    {
        var grid = combo.up('grid');

        if(newValue === 'lab')
        {
            // Change the Card panel, to show the Laboratory results form
            this.getDocumentTypeCard().getLayout().setActiveItem('laboratoryResultPanel');
            // Change the field to look for laboratories
            grid.columns[3].setEditor({
                xtype: 'labslivetsearch',
                itemId: 'labOrderLiveSearch',
                allowBlank: false,
                flex: 1,
                value: ''
            });
            // Enabled the New Order Result Properties
            this.getNewOrderResultBtn().disable(false);
        }

        if(newValue === 'rad')
        {
            // Change the Card panel, to show the Radiology results form
            this.getDocumentTypeCard().getLayout().setActiveItem('radiologyResultPanel');
            // Change the field to look for radiologies
            grid.columns[3].setEditor({
                xtype: 'radslivetsearch',
                itemId: 'radsOrderLiveSearch',
                allowBlank: false,
                flex: 1,
                value: ''
            });
            // Enabled the New Order Result Properties
            this.getNewOrderResultBtn().disable(false);
        }
    },

	onResultPanelActive: function()
    {
		this.setResultPanel();
	},

	setResultPanel: function(){
		var me = this,
			ordersStore = me.getOrdersGrid().getStore();

		if(app.patient)
        {
			ordersStore.clearFilter(true);
			ordersStore.filter([
				{
					property: 'pid',
					value: app.patient.pid
				}
			]);
		}
        else
        {
			ordersStore.clearFilter(true);
			ordersStore.load();
		}
	},

	onOrderSelectionChange: function(model, records)
    {
        if(!this.getDocumentTypeCard().isVisible())
            this.getDocumentTypeCard().setVisible(true);

        if(records[0])
        {
            if (records[0].data.order_type === 'lab')
                this.getDocumentTypeCard().getLayout().setActiveItem('laboratoryResultPanel');

            if (records[0].data.order_type === 'rad')
                this.getDocumentTypeCard().getLayout().setActiveItem('radiologyResultPanel');

            if (records.length > 0)
            {
                this.getOrderResult(records[0]);
            }
            else
            {
                this.resetOrderResultForm();
            }
        }
	},

	getOrderResult: function(orderRecord)
    {
		var me = this,
			form = me.getLaboratoryResultForm(),
			resultsStore = orderRecord.results(),
			observationGrid = me.getObservationsGrid(),
			observationStore,
            newResult,
            i;

		observationGrid.editingPlugin.cancelEdit();
		resultsStore.load({
			callback: function(records){
				if(records.length > 0)
                {
					form.loadRecord(records[0]);
					me.getOrderResultSignBtn().setDisabled(records[0].data.signed_uid > 0);
					observationStore = records[0].observations();
					observationGrid.reconfigure(observationStore);
					observationStore.load();
				}
                else
                {
					newResult = resultsStore.add({
						pid: orderRecord.data.pid,
						code: orderRecord.data.code,
						code_text: orderRecord.data.description,
						code_type: orderRecord.data.code_type,
						ordered_uid: orderRecord.data.uid,
						create_date: new Date()
					});
					form.loadRecord(newResult[0]);
					me.getOrderResultSignBtn().setDisabled(true);
					observationStore = newResult[0].observations();
					observationGrid.reconfigure(observationStore);
					observationStore.load({
						params:
                        {
							loinc: orderRecord.data.code
						},
						callback: function(ObsRecords)
                        {
							for(i = 0; i < ObsRecords.length; i++)
                            {
								ObsRecords[i].phantom = true;
							}
						}
					});
				}
			}
		});
	},

	onResetOrderResultClicked: function()
    {
		this.resetOrderResultForm();
	},

	resetOrderResultForm: function()
    {
		var me = this,
			form = me.getLaboratoryResultForm(),
			observationGrid = me.getObservationsGrid(),
			store = Ext.create('App.store.patient.PatientsOrderObservations');

		form.reset();
		observationGrid.editingPlugin.cancelEdit();
		observationGrid.reconfigure(store);
	},

	onSaveOrderResultClicked: function()
    {
		var me = this,
			form = me.getLaboratoryResultForm(),
			values = form.getValues(),
			files = me.getUploadField().getEl().down('input[type=file]').dom.files,
			reader = new FileReader();

        // The form is not valid, go ahead and warn the user.
		if(!form.isValid())
        {
			app.msg(_('oops'), _('required_fields_missing'), true);
			return;
		}

		if(files.length > 0)
        {
			reader.onload = (function(){
				return function(e)
                {
					var sm = me.getOrdersGrid().getSelectionModel(),
						order = sm.getSelection(),
						params = {
							pid: order[0].data.pid,
							eid: order[0].data.eid,
							uid: app.user.id,
							docType: 'lab',
							title: 'Lab #' + values.lab_order_id + ' Result',
							document: e.target.result
						};
					File.savePatientBase64Document(params, function(provider, response){
						if(response.result.success){
							values.documentId = 'doc|' + response.result.id;
							me.saveOrderResult(form, values);
						}else{
							app.msg(_('oops'), response.result.error)
						}
					});
				};
			})(files[0]);
			reader.readAsDataURL(files[0]);
		}
        else
        {
			me.saveOrderResult(form, values);
		}
	},

	saveOrderResult: function(form, values)
    {
		var me = this,
			record = form.getRecord(),
			sm = me.getOrdersGrid().getSelectionModel(),
			order = sm.getSelection(),
			observationData = [];

		var observationStore = record.observations(),
			observations = observationStore.data.items;

		record.set(values);
        record.save({
			success: function(rec){

				for(var i = 0; i < observations.length; i++)
                {
					observations[i].set({result_id: rec.data.id});
				}

				observationStore.sync({
					callback:function(batch, options)
                    {

					}
				});
				order[0].set({status: 'Received'});
				order[0].save();
				app.msg(_('sweet'), _('record_saved'));
			}
		});
	},

	onOrderDocumentViewBtnClicked: function()
    {
		var me = this,
			form = me.getLaboratoryResultForm(),
			record = form.getRecord(),
			recordData = record.data.documentId.split('|'),
			type = null,
			id = null,
			win;

		if(recordData[0]) type = recordData[0];
		if(recordData[1]) id = recordData[1];

		if(type && id)
        {
			if(type == 'hl7')
            {
				win = Ext.widget('hl7messageviewer').show();
				win.body.mask(_('loading...'));
				HL7Messages.getMessageById(id, function(provider, response)
                {
					me.getMessageField().setValue(response.result.message);
					me.getAcknowledgeField().setValue(response.result.response);
					win.body.unmask();
				});
			}
            else if(type == 'doc')
            {
				app.onDocumentView(id);
			}
		}
        else
        {
			app.msg(_('oops'), _('no_document_found'), true)
		}
	},

	onOrderDocumentChange: function(field)
    {
		//		say(field);
		//		say(document.getElementById(field.inputEl.id).files[0]);
		//		say(field.inputEl);
		//
		//		var fr = new FileReader();
		//
		//
		//		fr.onload = function(e) {
		//			say(e.target.result);
		//		};
		//
		//		fr.readAsDataURL( field.value );
	}

});
