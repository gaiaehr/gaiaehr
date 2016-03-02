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

Ext.define('App.view.patient.Patient', {
	extend: 'Ext.panel.Panel',
	requires: [
		'App.ux.AddTabButton',
		'App.view.patient.InsuranceForm'
	],
	layout: {
		type: 'vbox',
		align: 'stretch'
	},
	xtype: 'patientdeomgraphics',
	itemId: 'PatientDemographicsPanel',
	newPatient: true,
	pid: null,
	defaultPatientImage: 'resources/images/patientPhotoPlaceholder.jpg',
	defaultQRCodeImage: 'resources/images/QRCodeImage.png',

	initComponent: function(){
		var me = this,
            configs;

		me.store = Ext.create('App.store.patient.Patient');
		me.patientAlertsStore = Ext.create('App.store.patient.MeaningfulUseAlert');
		me.patientContacsStore = Ext.create('App.store.patient.PatientContacts', {
			autoLoad: false
		});

		me.compactDemographics = eval(g('compact_demographics'));

		me.insTabPanel = Ext.widget('tabpanel', {
			itemId: 'PatientInsurancesPanel',
			flex: 1,
			defaults: {
				autoScroll: true,
				padding: 10
			},
			plugins: [
				{
					ptype: 'AddTabButton',
					iconCls: 'icoAdd',
					toolTip: _('new_insurance'),
					btnText: _('add_insurance'),
					forceText: true,
					panelConfig: {
						xtype: 'patientinsuranceform'
					}
				}
			],
			listeners: {
				scope: me,
				beforeadd: me.insurancePanelAdd
			}
		});

		configs = {
			items: [
				me.demoForm = Ext.widget('form', {
					action: 'demoFormPanel',
					itemId: 'PatientDemographicForm',
					type: 'anchor',
					border: false,
					autoScroll: true,
					padding: (me.compactDemographics ? 0 : 10),
					fieldDefaults: {
						labelAlign: 'right',
						msgTarget: 'side'
					}
				})
			]
		};

		if(me.compactDemographics){
			configs.items.push(me.insTabPanel);
		}

		configs.bbar = [
			{
				xtype: 'button',
				action: 'readOnly',
				text: _('possible_duplicates'),
				minWidth: 75,
				itemId: 'PatientPossibleDuplicatesBtn'
			},
			'-',
			'->',
			'-',
			{
				xtype: 'button',
				action: 'readOnly',
				text: _('save'),
				itemId: 'PatientDemographicSaveBtn',
				minWidth: 75,
				scope: me,
				handler: me.formSave
			},
			'-',
			{
				xtype: 'button',
				text: _('cancel'),
				action: 'readOnly',
				itemId: 'PatientDemographicCancelBtn',
				minWidth: 75,
				scope: me,
				handler: me.formCancel
			}
		];

		configs.listeners = {
			scope: me,
			beforerender: me.beforePanelRender
		};

		Ext.apply(me, configs);

		me.callParent(arguments);

		if(!me.compactDemographics){

			Ext.Function.defer(function(){
				me.insTabPanel.title = _('insurance');
				me.insTabPanel.addDocked({
					xtype: 'toolbar',
					dock: 'bottom',
					items: [
						'->',
						'-',
						{
							xtype: 'button',
							action: 'readOnly',
							text: _('save'),
							minWidth: 75,
							scope: me,
							handler: me.formSave
						},
						'-',
						{
							xtype: 'button',
							text: _('cancel'),
							action: 'readOnly',
							minWidth: 75,
							scope: me,
							handler: me.formCancel
						}
					]
				});

				me.up('tabpanel').insert(1, me.insTabPanel);
			}, 300);
		}
	},

	beforePanelRender: function(){
		var me = this,
			whoPanel,
			PatientContactsTab;

        // Part of the Override custome function, this function calls the FormBuilder (a PHP method)
        // to dynamically insert the fields configured on the administration panel. All the fields
        // are in the GaiaEHR database.
		me.getFormItems(me.demoForm, 1, function(formPanel){

			var form = me.demoForm.getForm(),
				fname = form.findField('fname'),
				mname = form.findField('mname'),
				lname = form.findField('lname'),
				sex = form.findField('sex'),
				dob = form.findField('DOB'),
                crtl;

			if(fname) fname.vtype = 'nonspecialcharacters';
			if(mname) mname.vtype = 'nonspecialcharacters';
			if(lname) lname.vtype = 'nonspecialcharacters';

			if(dob) dob.setMaxValue(new Date());

			if(me.newPatient){
				crtl = App.app.getController('patient.Patient');

				fname.on('blur', crtl.checkForPossibleDuplicates, crtl);
				lname.on('blur', crtl.checkForPossibleDuplicates, crtl);
				sex.on('blur', crtl.checkForPossibleDuplicates, crtl);
				dob.dateField.on('blur', crtl.checkForPossibleDuplicates, crtl);
			}else{
				whoPanel = formPanel.query('[action=DemographicWhoFieldSet]')[0];
				whoPanel.insert(0,
					me.patientImages = Ext.create('Ext.panel.Panel', {
						action: 'patientImage',
						layout: 'hbox',
						style: 'float:right',
						bodyPadding: 5,
						height: 160,
						width: 255,
						items: [
							{
								xtype: 'image',
								width: 119,
								height: 119,
								itemId: 'image',
								margin: '0 5 0 0',
								src: me.defaultPatientImage
							},
							{
								xtype: 'textareafield',
								name: 'image',
								hidden: true
							},
							{
								xtype: 'image',
								itemId: 'qrcode',
								width: 119,
								height: 119,
								margin: 0,
								src: me.defaultQRCodeImage
							}
						],
						bbar: [
							'-',
							{
								text: _('take_picture'),
								action: 'onWebCam'
								//handler: me.getPhotoIdWindow
							},
							'-',
							'->',
							'-',
							{
								text: _('print_qrcode'),
								scope: me,
								handler: function(){
									window.printQRCode(app.patient.pid);
								}
							},
							'-'
						]
					})
				);

				//Patient Contacts
				PatientContactsTab = Ext.ComponentQuery.query('#Demographics')[0];
				PatientContactsTab.add(
					me.patientContacts = Ext.create('Ext.grid.Panel', {
						itemId: 'PatientSummaryContactsPanel',
						bodyPadding: 0,
						title: _('contacts'),
						store: me.patientContacsStore,
						columns: [
							{
								text: _('name'),
								dataIndex: 'fullname',
								flex: 1
							},
							{
								header: _('relationship'),
								dataIndex: 'relationship_name'
							},
							{
								header: _('active'),
								dataIndex: 'active',
								renderer: me.boolRenderer
							}
						],
						plugins: Ext.create('App.ux.grid.RowFormEditing', {
							autoCancel: false,
							errorSummary: false,
							clicksToEdit: 2,
							items: [
								{
									xtype: 'container',
									layout: 'hbox',
									defaults: {
										margin: '5 10 0 0'
									},
									items: [
										{
											xtype: 'container',
											layout: 'vbox',
											defaults: {
												layout: '50%',
												margin: '5 10 0 0'
											},
											items: [
												{
													xtype: 'fieldcontainer',
													layout: 'hbox',
													fieldLabel: _('name'),
													defaults: {
														layout: '100%',
														xtype: 'textfield'
													},
													items: [
														{
															name: 'first_name',
															emptyText: _('first_name'),
															width: 150,
															maxLength: 100,
															allowBlank: false
														},
														{
															name: 'middle_name',
															emptyText: _('middle_name'),
															width: 120,
															maxLength: 100
														},
														{
															name: 'last_name',
															emptyText: _('last_name'),
															width: 150,
															maxLength: 100
														}
													]
												},
												{
													xtype: 'gaiaehr.listcombo',
													fieldLabel: _('relationship'),
													name: 'relationship',
													displayField: 'option_name',
													valueField: 'option_value',
													width: 350,
													loadStore: true,
													queryMode: 'local',
													list: 134
												},
												{
													xtype: 'fieldcontainer',
													layout: 'hbox',
													fieldLabel: _('phone'),
													defaults: {
														xtype: 'textfield',
														vtype: 'numeric'
													},
													items: [
														{
															name: 'phone_use_code',
															emptyText: _('code'),
															width: 50,
															maxLength: 4
														},
														{
															name: 'phone_area_code',
															emptyText: _('area_code'),
															width: 50,
															maxLength: 4
														},
														{
															name: 'phone_local_number',
															emptyText: _('local_number'),
															width: 120,
															maxLength: 7
														}
													]
												},
												{
													fieldLabel: _('address'),
													xtype: 'textfield',
													name: 'street_mailing_address',
													emptyText: _('street'),
													width: 610,
													maxLength: 200
												},
												{
													xtype: 'fieldcontainer',
													layout: 'hbox',
													fieldLabel: _('address_cont'),
													defaults: {
														xtype: 'textfield'
													},
													items: [
														{
															name: 'city',
															emptyText: _('city'),
															width: 125,
															maxLength: 70
														},
														{
															xtype: 'gaiaehr.listcombo',
															name: 'state',
															emptyText: _('state'),
															displayField: 'option_name',
															valueField: 'option_value',
															width: 125,
															loadStore: true,
															queryMode: 'local',
															list: 20
														},
														{
															xtype: 'gaiaehr.listcombo',
															name: 'country',
															emptyText: _('country'),
															displayField: 'option_name',
															valueField: 'option_value',
															width: 125,
															loadStore: true,
															queryMode: 'local',
															list: 3
														},
														{
															emptyText: _('zip'),
															name: 'zip',
															width: 125,
															maxLength: 20
														}
													]
												}
											]
										},
										{
											xtype: 'fieldcontainer',
											layout: 'vbox',
											defaults: {
												layout: '50%',
												margin: '5 10 0 0'
											},
											items: [
												{
													xtype: 'gaiaehr.listcombo',
													name: 'publicity',
													fieldLabel: _('publicity'),
													emptyText: _('select'),
													displayField: 'option_name',
													valueField: 'option_value',
													width: 400,
													loadStore: true,
													queryMode: 'local',
													list: 132
												},
												{
													xtype: 'checkboxfield',
													name: 'active',
													fieldLabel: _('active')
												}
											]
										}
									]
								}
							]
						}),
						tbar: [
							{
								text: _('add_contact'),
								iconCls: 'icoAdd',
								action: 'patientContact',
								handler: me.onAddNewContact
							}
						]
					})
				);
			}
		});
	},

	onAddNewContact: function(btn){
		var grid = btn.up('grid'),
			store = grid.store,
			record;

		record = {
			created_date: new Date(),
			pid: app.patient.pid,
			uid: app.user.id
		};

		grid.plugins[0].cancelEdit();
		store.insert(0, record);
		grid.plugins[0].startEdit(0, 0);
	},

	insurancePanelAdd: function(tapPanel, panel){
		var me = this,
			record = panel.insurance || Ext.create('App.model.patient.Insurance', {pid: me.pid});

		panel.title = _('insurance') + ' (' + (record.data.insurance_type ? record.data.insurance_type : _('new')) + ')';

		me.insuranceFormLoadRecord(panel, record);
		if(record.data.image !== '') panel.down('image').setSrc(record.data.image);
	},

	insuranceFormLoadRecord: function(form, record){
		form.getForm().loadRecord(record);
		app.fireEvent('insurancerecordload', form, record);
	},

	getValidInsurances: function(){
		var me = this,
			forms = me.insTabPanel.items.items,
			records = [],
			form,
			rec;

		for(var i = 0; i < forms.length; i++){
			form = forms[i].getForm();
			if(!form.isValid()){
				me.insTabPanel.setActiveTab(forms[i]);
				return false;
			}
			rec = form.getRecord();
			app.fireEvent('beforepatientinsuranceset', form, rec);
			rec.set(form.getValues());
			app.fireEvent('afterpatientinsuranceset', form, rec);
			records.push(rec);
		}
		return records;
	},

	getPatientImages: function(record){
		var me = this;
		if(me.patientImages){
			me.patientImages.getComponent('image').setSrc(
				(record.data.image !== '' ? record.data.image : me.defaultPatientImage)
			);
		}
		if(me.patientImages){
			me.patientImages.getComponent('qrcode').setSrc(
				(record.data.qrcode !== '' ? record.data.qrcode : me.defaultQRCodeImage)
			);
		}
	},

	getPatientContacts: function(pid){
		var me = this;

		me.patientContacsStore.clearFilter(true);
		me.patientContacsStore.load({
			params: {
				pid: pid
			},
			filters: [
				{
					property: 'pid',
					value: pid
				}
			]
		});
	},

	/**
	 * verify the patient required info and add a yellow background if empty
	 */
	verifyPatientRequiredInfo: function(){
		var me = this,
			field;
		me.patientAlertsStore.load({
			scope: me,
			params: {pid: me.pid},
			callback: function(records, operation, success){
				for(var i = 0; i < records.length; i++){
					field = me.demoForm.getForm().findField(records[i].data.name);
					if(records[i].data.val){
						if(field) field.removeCls('x-field-yellow');
					}else{
						if(field) field.addCls('x-field-yellow');
					}
				}
			}
		});
	},

	/**
	 * allow to edit the field if the filed has no data
	 * @param fields
	 */
	readOnlyFields: function(fields){
		//        for(var i = 0; i < fields.items.length; i++){
		//            var f = fields.items[i], v = f.getValue(), n = f.name;
		//            if(n == 'SS' || n == 'DOB' || n == 'sex'){
		//                if(v == null || v == ''){
		//                    f.setReadOnly(false);
		//                }else{
		//                    f.setReadOnly(true);
		//                }
		//            }
		//        }
	},

	formSave: function(){
		var me = this,
			form = me.demoForm.getForm(),
			record = form.getRecord(),
			values = form.getValues(),
			insRecs = me.getValidInsurances();

		if(form.isValid() && insRecs !== false){
			record.set(values);

			// fire global event
			app.fireEvent('beforedemographicssave', record, me);

			record.save({
				scope: me,
				callback: function(record){

					app.setPatient(record.data.pid, null, null, function(){

						var insStore = record.insurance();

						for(var i = 0; i < insRecs.length; i++){
							if(insRecs[i].data.id === 0){
								insStore.add(insRecs[i]);
							}
						}

						insStore.sync();

						if(me.newPatient){
							app.openPatientSummary();
						}else{
							me.getPatientImages(record);
							me.verifyPatientRequiredInfo();
							me.readOnlyFields(form.getFields());
						}
					});

					// fire global event
					app.fireEvent('afterdemographicssave', record, me);

					me.msg(_('sweet'), _('record_saved'));
					app.AuditLog('Patient new record ' + (me.newPatient ? 'created' : 'updated'));
				}
			});
		}else{
			me.msg(_('oops'), _('missing_required_data'), true);
		}
	},

	formCancel: function(btn){
		var form = btn.up('form').getForm(), record = form.getRecord();
		form.loadRecord(record);
	},

	loadNew: function(){
		var patient = Ext.create('App.model.patient.Patient', {
			'create_uid': app.user.id,
			'update_uid': app.user.id,
			'create_date': new Date(),
			'update_date': new Date(),
			'DOB': '0000-00-00 00:00:00'
		});

		// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
		app.AuditLog('Patient new record created');
		this.demoForm.getForm().loadRecord(patient);
	},

	loadPatient: function(pid){
		var me = this,
			form = me.demoForm.getForm();

		me.pid = pid;

		form.reset();

		// GAIAEH-177 GAIAEH-173 170.302.r Audit Log (core)
		app.AuditLog('Patient record viewed');

		me.getPatientContacts(pid);

		app.patient.record.insurance().load({
			filters: [
				{
					property: 'pid',
					value: app.patient.record.data.pid
				}
			],
			callback: function(records){

				form.loadRecord(app.patient.record);
				me.setReadOnly(app.patient.readOnly);
				me.setButtonsDisabled(me.query('button[action="readOnly"]'));
				me.verifyPatientRequiredInfo();

				// set the insurance panel
				me.insTabPanel.removeAll(true);
				for(var i = 0; i < records.length; i++){
					me.insTabPanel.add(
						Ext.widget('patientinsuranceform', {
							closable: false,
							insurance: records[i]
						})
					);
				}

				if(me.insTabPanel.items.length !== 0) me.insTabPanel.setActiveTab(0);
			}
		});
	}
});
