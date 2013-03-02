/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

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

Ext.define('App.view.patient.Patient', {
    extend: 'Ext.panel.Panel',
	layout:{
		type:'vbox',
		align:'stretch'
	},
	alias:'widget.patientdeomgraphics',
	newPatient:true,
    pid: null,

    initComponent: function(){
        var me = this;

	    me.store = Ext.create('App.store.patient.Patient');
	    me.patientAlertsStore = Ext.create('App.store.patient.MeaningfulUseAlert');

		Ext.apply(me,{
			items:[

				me.demoForm = Ext.widget('form',{
					action: 'demoFormPanel',
					type:'anchor',
					border: false,
					fieldDefaults: { msgTarget: 'side' }
				}),

				me.insPanel = Ext.widget('tabpanel',{
					flex:1,
					defaults:{
						autoScroll:true,
						padding:10
					},
					items:[
						me.ins1 = Ext.widget('form',{
							title:i18n('first_insurance'),
							border:false,
							bodyBorder:false
						}),
						me.ins2 = Ext.widget('form',{
							title:i18n('second_insurance'),
							border:false,
							bodyBorder:false
						}),
						me.ins3 = Ext.widget('form',{
							title:i18n('supplemental_insurance'),
							border:false,
							bodyBorder:false
						})
					]
				})
			],
			bbar: [
				'->',
				{
					xtype: 'button',
					action: 'readOnly',
					text: i18n('save'),
					minWidth: 75,
					scope: me,
					handler: me.formSave
				}, '-', {
					xtype: 'button',
					text: i18n('cancel'),
					action: 'readOnly',
					minWidth: 75,
					scope: me,
					handler: me.formCancel
				}
			],
			listeners:{
				scope: me,
				render: me.beforePanelRender
			}
        });

	    me.callParent(arguments);
    },

    beforePanelRender: function(){
        var me = this,
            whoPanel;

        me.getFormItems(me.demoForm, 1, function(formPanel){
            if(!me.newPatient){
                whoPanel = formPanel.query('tabpanel')[0].items.items[0];
                whoPanel.insert(0,
	                Ext.create('Ext.panel.Panel', {
		                action: 'patientImgs',
		                layout: 'hbox',
		                style: 'float:right',
		                bodyPadding: 5,
		                height: 160,
		                width: 255,
		                items: [me.patientImg = Ext.create('Ext.container.Container', {
			                html: '<img src="resources/images/icons/patientPhotoId.jpg" height="119" width="119" />',
			                margin: '0 5 0 0'
		                }), me.patientQRcode = Ext.create('Ext.container.Container', {
			                html: '<img src="resources/images/icons/patientDataQrCode.png" height="119" width="119" />',
			                margin: 0
		                })],
		                bbar: ['-', {
			                text: i18n('take_picture'),
			                scope: me,
			                handler: me.getPhotoIdWindow
		                }, '-', '->', '-', {
			                text: i18n('print_qrcode'),
			                scope: me,
			                handler: function(){
				                window.printQRCode(app.patient.pid);
			                }
		                }, '-']
	                })
                );

//                me.getFormItems(me.demoForm, 1, function(formPanel, items){
//		            var primary = formPanel.getForm().findField('primary_subscriber_relationship');
//		            primary.on('select', me.copyData, me);
//		            var secondary = formPanel.getForm().findField('secondary_subscriber_relationship');
//		            secondary.on('select', me.copyData, me);
//		            var tertiary = formPanel.getForm().findField('tertiary_subscriber_relationship');
//		            tertiary.on('select', me.copyData, me);
//                });
            }
        });

        me.getFormItems(me.ins1, 11, function(formPanel){
	        if(!me.newPatient){
		        formPanel.insert(0,
			        Ext.widget('panel', {
				        style: 'float:right',
				        height: 182,
				        width: 255,
				        items: [
					        me.primaryInsuranceImg = Ext.create('Ext.container.Container', {
					            html: '<img src="resources/images/icons/no_card.jpg" height="154" width="254" />'
				            }),
					        me.primaryInsuranceImgUpload = Ext.create('Ext.window.Window', {
						        draggable: false,
						        closable: false,
						        closeAction: 'hide',
						        items: [
							        {
								        xtype: 'form',
								        bodyPadding: 10,
								        width: 310,
								        items: [
									        {
										        xtype: 'filefield',
										        name: 'filePath',
										        buttonText: i18n('select_a_file') + '...',
										        anchor: '100%'
									        }
								        ],
								        api: {
									        submit: DocumentHandler.uploadDocument
								        }
							        }
						        ],
						        buttons: [
							        {
								        text: i18n('cancel'),
								        handler: function(btn){
									        btn.up('window').close();
								        }
							        },
							        {
								        text: i18n('upload'),
								        scope: me,
								        action: 'Primary Insurance',
								        handler: me.onInsuranceUpload
							        }
						        ]
					        })
				        ],
				        bbar: ['->', '-', {
					        text: i18n('upload'),
					        action: 'primaryInsurance',
					        scope: me,
					        handler: me.uploadInsurance
				        }, '-']
			        })
		        );
	        }

        });

        me.getFormItems(me.ins2, 11, function(formPanel){
	        if(!me.newPatient){
		        formPanel.insert(0,
			        Ext.widget('panel',{
				        style: 'float:right',
				        height: 182,
				        width: 255,
				        items: [
					        me.secondaryInsuranceImg = Ext.create('Ext.container.Container', {
						        html: '<img src="resources/images/icons/no_card.jpg" height="154" width="254" />'
					        }),
					        me.secondaryInsuranceImgUpload = Ext.create('Ext.window.Window', {
						        draggable: false,
						        closable: false,
						        closeAction: 'hide',
						        items: [
							        {
								        xtype: 'form',
								        bodyPadding: 10,
								        width: 310,
								        items: [
									        {
										        xtype: 'filefield',
										        name: 'filePath',
										        buttonText: i18n('select_a_file') + '...',
										        anchor: '100%'
									        }
								        ],
								        api: {
									        submit: DocumentHandler.uploadDocument
								        }
							        }
						        ],
						        buttons: [
							        {
								        text: i18n('cancel'),
								        handler: function(btn){
									        btn.up('window').close();
								        }
							        },
							        {
								        text: i18n('upload'),
								        scope: me,
								        action: 'Secondary Insurance',
								        handler: me.onInsuranceUpload
							        }
						        ]
					        })
				        ],
				        bbar: ['->', '-', {
					        text: i18n('upload'),
					        action: 'secondaryInsurance',
					        scope: me,
					        handler: me.uploadInsurance
				        }, '-']
			        })
		        );
	        }
        });

        me.getFormItems(me.ins3, 11, function(formPanel){
	        if(!me.newPatient){
		        formPanel.insert(0,
			        Ext.widget('panel', {
				        style: 'float:right',
				        height: 182,
				        width: 255,
				        items: [
					        me.tertiaryInsuranceImg = Ext.create('Ext.container.Container', {
						        html: '<img src="resources/images/icons/no_card.jpg" height="154" width="254" />'
					        }),
					        me.tertiaryInsuranceImgUpload = Ext.create('Ext.window.Window', {
						        draggable: false,
						        closable: false,
						        closeAction: 'hide',
						        items: [
							        {
								        xtype: 'form',
								        bodyPadding: 10,
								        width: 310,
								        items: [
									        {
										        xtype: 'filefield',
										        name: 'filePath',
										        buttonText: i18n('select_a_file') + '...',
										        anchor: '100%'
									        }
								        ],
								        api: {
									        submit: DocumentHandler.uploadDocument
								        }
							        }
						        ],
						        buttons: [
							        {
								        text: i18n('cancel'),
								        handler: function(btn){
									        btn.up('window').close();
								        }
							        },
							        {
								        text: i18n('upload'),
								        scope: me,
								        action: 'Tertiary Insurance',
								        handler: me.onInsuranceUpload
							        }
						        ]
					        })
				        ],
				        bbar: ['->', '-', {
					        text: 'Upload',
					        scope: me,
					        action: 'tertiaryInsurance',
					        handler: me.uploadInsurance
				        }, '-']
			        })
		        );
	        }
        });
    },

    uploadInsurance: function(btn){
        var me = this,
        action = btn.action;

        if(action == 'primaryInsurance'){
            me.primaryInsuranceImgUpload.show();
            me.primaryInsuranceImgUpload.alignTo(me.primaryInsuranceImg.el.dom, 'br-br', [0, 0]);
        }else if(action == 'secondaryInsurance'){
            me.secondaryInsuranceImgUpload.show();
            me.secondaryInsuranceImgUpload.alignTo(me.secondaryInsuranceImg.el.dom, 'br-br', [0, 0]);
        }
        if(action == 'tertiaryInsurance'){
            me.tertiaryInsuranceImgUpload.show();
            me.tertiaryInsuranceImgUpload.alignTo(me.tertiaryInsuranceImg.el.dom, 'br-br', [0, 0]);
        }
    },


    getPatientImgs: function(){
        var me = this, number = Ext.Number.randomInt(1, 1000);
        me.patientImg.update('<img src="' + settings.site_url + '/patients/' + me.pid + '/patientPhotoId.jpg?' + number + '" height="119" width="119" />');
        me.patientQRcode.update('<a ondblclick="printQRCode(app.patient.pid)"><img src="' + settings.site_url + '/patients/' + me.pid + '/patientDataQrCode.png?' + number + '" height="119" width="119" title="Print QRCode" /></a>');
    },

    getPhotoIdWindow: function(){
        var me = this;
        me.PhotoIdWindow = Ext.create('App.ux.PhotoIdWindow', {
            title: i18n('patient_photo_id'),
            loadMask: true,
            modal: true
        }).show();
    },

    completePhotoId: function(){
        this.PhotoIdWindow.close();
        this.getPatientImgs();
    },

    onInsuranceUpload: function(btn){
        var me = this,
	        action = btn.action,
	        win = btn.up('window'),
	        form,
	        imgCt;

        if(action == 'Primary Insurance'){
            form = me.primaryInsuranceImgUpload.down('form').getForm();
            imgCt = me.primaryInsuranceImg;
        }else if(action == 'Secondary Insurance'){
            form = me.secondaryInsuranceImgUpload.down('form').getForm();
            imgCt = me.secondaryInsuranceImg;
        }
        if(action == 'Tertiary Insurance'){
            form = me.tertiaryInsuranceImgUpload.down('form').getForm();
            imgCt = me.tertiaryInsuranceImg;
        }
        if(form.isValid()){
            form.submit({
                waitMsg: i18n('uploading_insurance') + '...',
                params: {
                    pid: app.patient.pid,
                    docType: action
                },
                success: function(fp, o){
                    say(o.result.doc);
                    win.close();
                    imgCt.update('<img src="' + o.result.doc.url + '" height="154" width="254" />');
                },
                failure: function(fp, o){
                    say(o.result.error);
                    win.close();
                }
            });
        }
    },

    /**
     * verify the patient required info and add a yellow background if empty
     */
    verifyPatientRequiredInfo: function(){
        var me = this,
	        field;

        me.patientAlertsStore.load({
                scope: me,
                params: {
                    pid: me.pid
                },
                callback: function(records, operation, success){
                    for(var i = 0; i < records.length; i++){
                        field = me.demoForm.getForm().findField(records[i].data.name);
                        if(records[i].data.val){
                            field.removeCls('x-field-yellow');
                        }else{
                            field.addCls('x-field-yellow');
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

	copyData:function(combo, records){
		var form = this.demoForm.getForm(),
			values,
			patientData;

		if(combo.value == 'self'){

			values = form.getValues();

			patientData = {
				primary_subscriber_title:values.title,
				primary_subscriber_fname:values.fname,
				primary_subscriber_mname:values.mname,
				primary_subscriber_lname:values.lname,
				primary_subscriber_street:values.address,
				primary_subscriber_city:values.city,
				primary_subscriber_state:values.state,
				primary_subscriber_country:values.country,
				primary_subscriber_zip_code:values.zipcode,
				primary_subscriber_phone:values.home_phone,
				primary_subscriber_employer:values.employer_name,
				primary_subscriber_employer_street:values.employer_address,
				primary_subscriber_employer_city:values.employer_city,
				primary_subscriber_employer_state:values.employer_state,
				primary_subscriber_employer_country:values.employer_country,
				primary_subscriber_employer_zip_code:values.employer_postal_code
			};

			form.setValues(patientData);
		}
	},


	formSave: function(){
		var me = this,
			form = me.demoForm.getForm(),
			record = form.getRecord(),
			values = form.getValues();

		say(values);

		record.set(values);
		record.save({
			scope: me,
			callback: function(record){

				app.setPatient(record.data.pid, null);

				if(!me.newPatient){
					me.getPatientImgs();
					me.verifyPatientRequiredInfo();
					me.readOnlyFields(form.getFields());
				}

				var insurances = record.insuranceStore.data.items;

				for(var i=0; i < insurances.length; i++){
					insurances[i].set({pid:record.data.pid});
				}

				record.insuranceStore.sync();
				me.msg('Sweet!', i18n('record_saved'))
			}
		});
	},

	formCancel: function(btn){
		var form = btn.up('form').getForm(), record = form.getRecord();
		form.loadRecord(record);
	},

	loadNew:function(){
		var patient = Ext.create('App.model.patient.Patient',{
			'create_uid':app.user.id,
			'write_uid':app.user.id,
			'create_date':new Date(),
			'update_date':new Date(),
			'DOB':'000-00-00 00:00:00'
		}),	store;

		store = patient.insurance();

		for(var i=0; i < 3; i++){
			this.insPanel.items.items[i].getForm().loadRecord(
				store.add({
					'create_uid':app.user.id,
					'write_uid':app.user.id,
					'create_date':new Date(),
					'update_date':new Date(),
					'type': (i == 2 ? 'supplemental':'main')
				})
			);
		}

		say(store);

		this.demoForm.getForm().loadRecord(patient);
	},

	loadPatient:function(pid){
		var me = this,
			form = me.demoForm.getForm();

		me.pid = pid;

		me.store.load({
			filters:[
				{
					property:'pid',
					value:me.pid
				}
			],
			callback:function(record){
				form.reset();
				form.loadRecord(record[0]);

				me.setReadOnly(app.patient.readOnly);
				me.setButtonsDisabled(me.query('button[action="readOnly"]'));

				me.getPatientImgs();
				me.verifyPatientRequiredInfo();

				Patient.getPatientInsurancesCardsUrlByPid(me.pid, function(insurance){
					var noCard = 'resources/images/icons/no_card.jpg',
						Ins1 = insurance.Primary.url ? insurance.Primary.url : noCard,
						Ins2 = insurance.Secondary.url ? insurance.Secondary.url : noCard,
						Ins3 = insurance.Tertiary.url ? insurance.Tertiary.url : noCard;
					me.primaryInsuranceImg.update('<img src="'+Ins1+'" height="154" width="254" />');
					me.secondaryInsuranceImg.update('<img src="'+Ins2+'" height="154" width="254" />');
					me.tertiaryInsuranceImg.update('<img src="'+Ins3+'" height="154" width="254" />');
				});
			}
		})
	}
});