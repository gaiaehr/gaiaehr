/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/15/12
 * Time: 4:30 PM
 *
 * @namespace Immunization.getImmunizationsList
 * @namespace Immunization.getPatientImmunizations
 * @namespace Immunization.addPatientImmunization
 */
Ext.define('App.view.patient.windows.NewDocuments', {
	extend     : 'App.ux.window.Window',
	title      : i18n('order_window'),
	layout     : 'fit',
	closeAction: 'hide',
    height       : 750,
   	width        : 1200,
	bodyStyle  : 'background-color:#fff',
	modal      : true,
	defaults   : {
		margin: 5
	},
	pid:null,
    eid:null,
	initComponent: function() {
		var me = this;
		me.patientPrescriptionStore = Ext.create('App.store.patient.PatientsPrescription');
		me.patientPrescriptionsStore = Ext.create('App.store.patient.PatientsPrescriptions');
		me.patientsLabsOrdersStore = Ext.create('App.store.patient.PatientsLabsOrders');
		
		me.items = [
			me.tabPanel = Ext.create('Ext.tab.Panel', {

				items: [
					{
						title: i18n('new_lab_order'),
						items: [
							{
								xtype  : 'grid',
								margin : 10,
								store  : me.patientsLabsOrdersStore,
								height : 640,
								columns: [
									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon   : 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												scope  : me,
												handler: me.onRemoveLabs
											}
										]
									},
									{
										header   : i18n('lab'),
										flex    : 1,
										dataIndex: 'laboratories'
									}
								],
								bbar:{
									xtype     : 'mitos.labstypescombo',
									margin:5,
									fieldLabel: i18n('add'),
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.onAddLabs
									}
								}
							}
						],


						bbar : [
							'->', {
								text   : i18n('create'),
								scope  : me,
								handler: me.onCreateLabs
							}, {
								text   : i18n('cancel'),
								scope  : me,
								handler: me.onCancel
							}
						]
					},
					{
						title: i18n('new_xray_order'),
						items: [

							{

								xtype  : 'grid',
								margin : 10,
								store  : me.patientPrescriptionStore,
								height : 640,
								columns: [

									{
										xtype: 'actioncolumn',
										width: 20,
										items: [
											{
												icon   : 'resources/images/icons/delete.png',
												tooltip: i18n('remove'),
												scope  : me,
												handler: me.onRemove
											}
										]
									},
									{
										header   : i18n('medication'),
										width    : 100,
										dataIndex: 'medication'
									},
									{
										header   : i18n('dispense'),
										width    : 100,
										dataIndex: 'dispense'
									},
									{
										header   : i18n('refill'),
										flex     : 1,
										dataIndex: 'refill'
									}

								],

								bbar:{
								xtype:'textfield',
								margin:5,
								fieldLabel: i18n('add'),
								hideLabel:false,
								listeners:{
									scope:me,
									select:me.addMedications
								}
							}

							}
						],
						bbar : [
							'->', {
								text   : i18n('create'),
								scope  : me,
								handler: me.Create
							}, {
								text   : i18n('cancel'),
								scope  : me,
								handler: me.onCancel
							}
						]
					},
					{
						title: i18n('new_prescription'),
						items: [
							{
								xtype     : 'mitos.pharmaciescombo',
								fieldLabel: i18n('pharmacies'),
								width     : 250,
								labelWidth: 75,
								margin    : '10 0 0 10'

							},
							{

								xtype  : 'grid',
								margin : 10,
                                title  : i18n('prescriptions'),
								store  : me.patientPrescriptionsStore,
								height : 300,
                                plugins: [
                                        me.edditing = Ext.create('Ext.grid.plugin.RowEditing', {
                                        clicksToEdit: 2,
                                        errorSummary : false
                                     })
                                ],
								columns: [
									{
										header   : i18n('date'),
                                        xtype:'datecolumn',
                                        format:'Y-m-d',
                                        width:100,
                                        dataIndex: 'created_date',
                                        name: 'created_date',
                                        action: 'created_date'

									},
									{
										header   : i18n('notes'),
										flex     : 1,
                                        editor:{
                                            xtype:'textfield'
                                        },
										dataIndex: 'note'
									}

								],

                                listeners:{
                                    scope:me,
                                    beforeedit:me.beforePrescriptionsEdit,
                                    itemclick: me.medicationsOfThePrescription

                                },

                                tbar   : [
                                    '->',
                                    {
                                        text   : i18n('clone_prescription'),
                                        scope  : me,
                                        handler: me.onClonePrescriptions

                                    },
                                    {
                                        text   : i18n('new_prescription'),
                                        scope  : me,
                                        handler: me.onAddNewPrescriptions

                                    }
                                ]

							},
                            {

								xtype  : 'grid',
								margin : 10,
                                title  : i18n('medications'),
                                disabled: true,
                                action: 'prescription_grid',
								store  : me.patientPrescriptionStore,
								height : 300,
								columns: [
									{
										header   : i18n('name'),
										flex     : 1,
										dataIndex: 'medication'
									},
									{
										header   : i18n('refill'),
										width     : 100,
										dataIndex: 'refill'
									}

								],

                                plugins: Ext.create('App.ux.grid.RowFormEditing', {
                                    autoCancel  : false,
                                    errorSummary: false,
                                    clicksToEdit: 1,
                                    listeners   :{
                                        scope   : me,
                                        beforeedit    : me.onEditPrescription
                                    },
                                    formItems   : [


                                        {
                                            title : i18n('general'),
                                            xtype : 'container',
                                            layout: 'vbox',
                                            items : [
                                                {
                                                    /**
                                                     * Line one
                                                     */
                                                    xtype   : 'fieldcontainer',
                                                    layout  : 'hbox',
                                                    defaults: { margin: '5 0 5 5' },
                                                    items   : [
                                                        {
                                                            xtype     : 'rxnormlivetsearch',
                                                            fieldLabel: i18n('medication'),
                                                            hideLabel : false,
                                                            action    : 'medication_id',
                                                            name      : 'RXCUI',
                                                            width     : 350,
                                                            labelWidth: 80,
                                                            allowBlank: false,
                                                            listeners : {
                                                                scope : me,
                                                                select: me.addPrescription
                                                            }
                                                        },
                                                        {
                                                            xtype:'textfield',
                                                            hidden:true,
                                                            name:'medication',
                                                            action:'medication'
                                                        },
                                                        {
                                                            xtype     : 'textfield',
                                                            fieldLabel: i18n('dose'),
                                                            labelWidth: 40,
                                                            action    : 'dose',
                                                            name      : 'dose',
                                                            allowBlank: false,
                                                            width     : 250
                                                        }
                                                    ]

                                                },
                                                {
                                                    /**
                                                     * Line two
                                                     */
                                                    xtype   : 'fieldcontainer',
                                                    layout  : 'hbox',
                                                    defaults: { margin: '5 0 5 3'},

                                                    items: [
                                                        {
                                                            xtype     : 'numberfield',
                                                            fieldLabel: i18n('take'),
                                                            margin    : '5 0 5 5',
                                                            name      : 'take_pills',
                                                            allowBlank: false,
                                                            width     : 130,
                                                            labelWidth: 80,
                                                            value     : 0,
                                                            minValue  : 0
                                                        },
                                                        {
                                                            xtype     : 'mitos.prescriptiontypes',
                                                            fieldLabel: i18n('type'),
                                                            allowBlank: false,
                                                            hideLabel : true,
                                                            name      : 'type',
                                                            width     : 120
                                                        },
                                                        {
                                                            xtype     : 'mitos.prescriptionhowto',
                                                            fieldLabel: i18n('route'),
                                                            allowBlank: false,
                                                            name      : 'route',
                                                            hideLabel : true,
                                                            width     : 100
                                                        },
                                                        {
                                                            xtype: 'mitos.prescriptionoften',
                                                            name : 'prescription_often',
                                                            allowBlank: false,
                                                            width: 120
                                                        },
                                                        {
                                                            xtype: 'mitos.prescriptionwhen',
                                                            name : 'prescription_when',
                                                            width: 100
                                                        }
                                                    ]

                                                },
                                                {
                                                    /**
                                                     * Line three
                                                     */
                                                    xtype   : 'fieldcontainer',
                                                    layout  : 'hbox',
                                                    defaults: { margin: '5 0 5 5'},
                                                    items   : [
                                                        {

                                                            fieldLabel: i18n('dispense'),
                                                            xtype     : 'numberfield',
                                                            name      : 'dispense',
                                                            width     : 130,
                                                            allowBlank: false,
                                                            labelWidth: 80,
                                                            value     : 0,
                                                            minValue  : 0
                                                        },
                                                        {
                                                            fieldLabel: i18n('refill'),
                                                            xtype     : 'numberfield',
                                                            name      : 'refill',
                                                            labelWidth: 35,
                                                            allowBlank: false,
                                                            width     : 140,
                                                            value     : 0,
                                                            minValue  : 0
                                                        },
                                                        {
                                                            fieldLabel: i18n('begin_date'),
                                                            xtype     : 'datefield',
                                                            width     : 190,
                                                            labelWidth: 70,
                                                            format: globals['date_display_format'],
                                                            name      : 'begin_date'

                                                        },
                                                        {
                                                            fieldLabel: i18n('end_date'),
                                                            xtype     : 'datefield',
                                                            width     : 180,
                                                            labelWidth: 60,
                                                            format: globals['date_display_format'],
                                                            name      : 'end_date'
                                                        }
                                                    ]

                                                }

                                            ]

                                        }
                                    ]
                                }),
								tbar   : [
									'->',
									{
										text   : i18n('add_medication'),
										scope  : me,
                                        action : 'add_medication',
                                        disabled: true,
										handler: me.onAddNewPrescription

									}
								]

							}

						],
						bbar : [
							'->', {
								text   : i18n('create'),
								scope  : me,
								handler: me.onCreatePrescription
							}, {
								text   : i18n('cancel'),
								scope  : me,
								handler: me.onCancel
							}
						]

					},
					{
						title: i18n('new_doctors_note'),
						items: [
							{
								xtype     : 'mitos.templatescombo',
								fieldLabel: i18n('template'),
								action: 'template',
								width     : 250,
								labelWidth: 75,
								margin    : '10 0 0 10',
								enableKeyEvents: true,
								listeners      : {
									scope   : me,
									select: me.onTemplateTypeSelect
								}
							},
							{

								xtype: 'htmleditor',
								name:'body',
								action:'body',
								itemId:'body',
								enableFontSize: false,
								height : 605,
								width  : 1170,
								margin:5

							}
						],
						bbar : [
							'->', {
								text   : i18n('create_prescription'),
								scope  : me,
								handler: me.onCreateDoctorsNote
							}
						]
					}
				]

			})
		];
		me.listeners = {
			scope: me,
			show : me.onDocumentsWinShow,
            hide : me.onDocumentsWinHide
		};
		me.callParent(arguments);
	},

	onTemplateTypeSelect:function(combo,record){
		var me          = this,
			htmlEditor  = combo.up('panel').getComponent('body'),
			value       = record[0].data.body;
		htmlEditor.setValue(value);
	},

	cardSwitch          : function(action) {

		var layout = this.tabPanel.getLayout();

		if(action == 'lab') {
			layout.setActiveItem(0);
		} else if(action == 'xRay') {
			layout.setActiveItem(1);
		} else if(action == 'prescription') {
			layout.setActiveItem(2);
		} else if(action == 'notes') {
			layout.setActiveItem(3);
		}
	},
	onAddNewPrescription: function(btn) {
		var grid = btn.up('grid'),
            prescription_id = btn.up('panel').up('panel').down('grid').getSelectionModel().getLastSelected().data.id;
		grid.editingPlugin.cancelEdit();

		this.patientPrescriptionStore.insert(0,{
            prescription_id:prescription_id,
            created_uid:app.user.id,

            eid:app.patient.eid
        });
		grid.editingPlugin.startEdit(0, 0);
        say(prescription_id);
	},
    onAddNewPrescriptions: function(btn) {
		var grid = btn.up('grid');
		grid.editingPlugin.cancelEdit();

		this.patientPrescriptionsStore.insert(0,{
            eid:app.patient.eid
        });
		grid.editingPlugin.startEdit(0, 0);

	},

    onClonePrescriptions: function (btn){
        var grid = btn.up('grid'),
            bottomGrid = grid.up('panel').up('panel').query('panel[action="prescription_grid"]')[0],
            obj = bottomGrid.store.data.items;


      		grid.editingPlugin.cancelEdit();

      		this.patientPrescriptionsStore.insert(0,{
                  eid:app.patient.eid
              });
      		grid.editingPlugin.startEdit(0, 0);

	},

    medicationsOfThePrescription: function(grid,model){
        var me = this,
            value = model.data.id,
            eid= model.data.eid,
            addMedication = grid.up('panel').up('panel').query('button[action="add_medication"]')[0],
            bottomGrid = grid.up('panel').up('panel').query('panel[action="prescription_grid"]')[0];
    me.patientPrescriptionStore.load({
              params:{
                  prescription_id:value
              }
          });
        if(eid == app.patient.eid){
            addMedication.setDisabled(false);
            bottomGrid.setDisabled(false);
        }else{
            addMedication.setDisabled(true);
            bottomGrid.setDisabled(true);

        }

    },

	onRemove: function(grid, rowIndex){
		var me = this,
			store = grid.getStore(),
			record = store.getAt(rowIndex);
			grid.editingPlugin.cancelEdit();
			store.remove(record);
	},
	onRemoveLabs: function(grid, rowIndex){
		var me = this,
			store = grid.getStore(),
			record = store.getAt(rowIndex);
			store.remove(record);
	},
	addPrescription     : function(combo, model) {
		var me      = this,
			field   = combo.up('fieldcontainer').query('[action="dose"]')[0],
			field3  = combo.up('fieldcontainer').query('[action="medication"]')[0],
			strength    = model[0].data.RXN_AVAILABLE_STRENGTH,
			medication      = model[0].data.STR;
		field.setValue(strength);
		field3.setValue(medication);
	},
	onEditPrescription: function(editor,e){
//        var me = this,
//            eid = e.record.data.eid;
//       say(this.up('panel'));
//        if(eid == app.patient.eid){
//
//        }else{
//
//        }
	},
	onCreatePrescription: function (){
        say('hello');
		var records = this.patientPrescriptionStore.data.items,
			data = [];
        say(records);
        for(var i=0; i < records.length; i++ ){
            data.push(records[i].data);
        }
        say('stuck');
		DocumentHandler.createDocument({medications:data, pid:app.patient.pid, docType:'Rx', documentId:5, eid: app.patient.eid}, function(provider, response){
			say(response.result);
		});
		this.close();

	},
	onCreateLabs: function (){
		var records = this.patientsLabsOrdersStore.data.items,
			data = [];
        for(var i=0; i < records.length; i++ ){
            data.push(records[i].data);
        }
		DocumentHandler.createDocument({labs:data, pid:app.patient.pid, docType:'Orders', documentId:4, eid: app.patient.eid}, function(provider, response){
			say(response.result);
		});
		this.close();

	},
	onCreateDoctorsNote: function (bbar){
		var me = this,
			htmlEditor  = bbar.up('toolbar').up('panel').getComponent('body'),
			value = htmlEditor.getValue();
		DocumentHandler.createDocument({DoctorsNote:value, pid:app.patient.pid, docType:'DoctorsNotes', eid: app.patient.eid}, function(provider, response){

			say(response.result);
		});
		this.close();

	},

	addMedications: function(){

	},
	onAddLabs: function(field, model){

		this.patientsLabsOrdersStore.add({
			laboratories:model[0].data.loinc_name
		});
		field.reset();
	},
	onDocumentsWinShow  : function() {
        var me = this,
	        doctorsNoteBody = me.query('[action="body"]')[0],
            template = me.query('[action="template"]')[0],
	        p = app.patient;
		me.pid = p.pid;
        me.setTitle(p.name + (p.readOnly ? ' - <span style="color:red">[' + i18n('read_mode') + ']</span>' : ''));
		me.setReadOnly(app.patient.readOnly);
		me.patientPrescriptionStore.removeAll();
		me.patientPrescriptionsStore.load({
            params:{
                pid:app.patient.pid
            }
        });


		me.patientsLabsOrdersStore.removeAll();
		doctorsNoteBody.reset();
		template.reset();


        var dock = this.tabPanel.getDockedItems()[0],
            visible = this.eid != null;
        dock.items.items[0].setVisible(visible);
        dock.items.items[1].setVisible(visible);
        dock.items.items[2].setVisible(visible);
        if(!visible) me.cardSwitch('notes');
	},
    onDocumentsWinHide : function(){
        if(app.currCardCmp.id == 'panelSummary'){
           app.currCardCmp.patientDocumentsStore.load({params: {pid: this.pid}});
        }
    },

    beforePrescriptionsEdit : function(editor, e) {
        var form = editor.editor.getForm(),
            search = form.findField('created_date'),
            newRecord = e.record.data.created_date == '';
        search.setVisible(newRecord);
        if(newRecord){
            e.record.data.created_date = new Date();
        }
    }



});