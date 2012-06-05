/**
 * layout.ejs.php
 * Description: Layout Screen Panel
 * v0.0.1
 *
 * Author: GI Technologies, 2011
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 */
Ext.define('App.view.administration.Documents', {
	extend              : 'App.classes.RenderPanel',
	id                  : 'panelDocuments',
	pageTitle           : 'Document Template Editor',
	pageLayout          : 'border',
	uses                : [
		'App.classes.GridPanel'
	],
	initComponent       : function() {

		var me = this;

        me.templatesDocumentsStore = Ext.create('App.store.administration.DocumentsTemplates');
		me.headersAndFooterStore   = Ext.create('App.store.administration.HeadersAndFooters');

        Ext.define('tokenModel', {
            extend: 'Ext.data.Model',
            fields: [
                {name: 'title',     type: 'string'},
                {name: 'token',     type: 'string'}
            ]
        });
        me.tokenStore =  Ext.create('Ext.data.Store', {
             model: 'tokenModel',
             data : [
	             {
		             title: 'Patient Name',
		             token: '[PATIENT_NAME]'
	             },
	             {
		             title: 'Patient Full Name',
		             token: '[PATIENT_FULL_NAME]'
	             },
                 {
                     title: 'Patient Mothers Maiden Name',
                     token: '[PATIENT_MAIDEN_NAME]'
                 },
	             {
                     title: 'Patient Last Name',
                     token: '[PATIENT_LAST_NAME]'
                 },
	             {
                     title: 'Patient Birthdate',
                     token: '[PATIENT_BIRTHDATE]'
                 },
	             {
                     title: 'Patient Marital Status',
                     token: '[PATIENT_MARITAL_STATUS]'
                 },
	             {
                     title: 'Patient Home Phone',
                     token: '[PATIENT_HOME_PHONE]'
                 },
	             {
                     title: 'Patient Mobile Phone',
                     token: '[PATIENT_MOBILE_PHONE]'
                 },
	             {
                     title: 'Patient Work Phone',
                     token: '[PATIENT_WORK_PHONE]'
                 },
	             {
                     title: 'Patient Email',
                     token: '[PATIENT_EMAIL]'
                 },
	             {
                     title: 'Patient Social Security',
                     token: '[PATIENT_SOCIAL_SECURITY]'
                 },
                 {
                     title: 'Patient Sex',
                     token: '[PATIENT_SEX]'
                 },
	             {
                     title: 'Patient Age',
                     token: '[PATIENT_AGE]'
                 },
	             {
                     title: 'Patient City',
                     token: '[PATIENT_CITY]'
                 },
	             {
                     title: 'Patient State',
                     token: '[PATIENT_STATE]'
                 },
	             {
                     title: 'Patient Home Address Line 1',
                     token: '[PATIENT_HOME_ADDRESS_LINE_ONE]'
                 },
	             {
                     title: 'Patient Home Address Line 2',
                     token: '[PATIENT_HOME_ADDRESS_LINE_TWO]'
                 },
	             {
		             title: 'Patient Home Address Zip Code',
		             token: '[PATIENT_HOME_ADDRESS_ZIP_CODE]'
	             },
	             {
		             title: 'Patient Home Address City',
		             token: '[PATIENT_HOME_ADDRESS_CITY]'
	             },
	             {
		             title: 'Patient Home Address State',
		             token: '[PATIENT_HOME_ADDRESS_STATE]'
	             },
	             {
                     title: 'Patient Postal Address Line 1',
                     token: '[PATIENT_POSTAL_ADDRESS_LINE_ONE]'
                 },
	             {
                     title: 'Patient Postal Address Line 2',
                     token: '[PATIENT_POSTAL_ADDRESS_LINE_TWO]'
                 },
	             {
		             title: 'Patient Postal Address Zip Code',
		             token: '[PATIENT_POSTAL_ADDRESS_ZIP_CODE]'
	             },
	             {
		             title: 'Patient Postal Address City',
		             token: '[PATIENT_POSTAL_ADDRESS_CITY]'
	             },
	             {
		             title: 'Patient Postal Address State',
		             token: '[PATIENT_POSTAL_ADDRESS_STATE]'
	             },
	             {
                     title: 'Patient Tabacco',
                     token: '[PATIENT_TABACCO]'
                 },
	             {
                     title: 'Patient Alcohol',
                     token: '[PATIENT_ALCOHOL]'
                 },
	             {
                     title: 'Patient Drivers License',
                     token: '[PATIENT_DRIVERS_LICENSE]'
                 },
	             {
                     title: 'Patient Employeer',
                     token: '[PATIENT_EMPLOYEER]'
                 },
                 {
                     title: 'Patient First Emergency Contact',
                     token: '[PATIENT_FIRST_EMERGENCY_CONTACT]'
                 },
	             {
                     title: 'Patient Referral',
                     token: '[PATIENT_REFERRAL]'
                 },
	             {
                     title: 'Patient Date Referred',
                     token: '[PATIENT_REFERRAL_DATE]'
                 },
                 {
                     title: 'Patient Balance',
                     token: '[PATIENT_BALANCE]'
                 },
                 {
                     title: 'Patient Picture',
                     token: '[PATIENT_PICTURE]'
                 },
                 {
                     title: 'Patient Primary Plan',
                     token: '[PATIENT_PRIMARY_PLAN]'
                 },
                 {
                     title: 'Patient Primary Plan Insured Person',
                     token: '[PATIENT_PRIMARY_INSURED_PERSON]'
                 },
                 {
                     title: 'Patient Primary Plan Contract Number',
                     token: '[PATIENT_PRIMARY_CONTRACT_NUMBER]'
                 },
                 {
                     title: 'Patient Primary Plan Expiration Date',
                     token: '[PATIENT_PRIMARY_EXPIRATION_DATE]'
                 },
                 {
                     title: 'Patient Secondary Plan',
                     token: '[PATIENT_SECONDARY_PLAN]'
                 },
                 {
                     title: 'Patient Secondary Insured Person',
                     token: '[PATIENT_SECONDARY_INSURED_PERSON]'
                 },
                 {
                     title: 'Patient Secondary Plan Contract Number',
                     token: '[PATIENT_SECONDARY_CONTRACT_NUMBER]'
                 },
                 {
                     title: 'Patient Secondary Plan Expiration Date',
                     token: '[PATIENT_SECONDARY_EXPIRATION_DATE]'
                 },
                 {
                     title: 'Patient Referral details',
                     token: '[PATIENT_REFERRAL_DETAILS]'
                 },
                 {
                     title: 'Patient Referral reason',
                     token: '[PATIENT_REFERRAL_REASON]'
                 },
                 {
                     title: 'Patient Head Circumference',
                     token: '[PATIENT_HEAD_CIRCUMFERENCE]'
                 },
                 {
                     title: 'Patient Height',
                     token: '[PATIENT_HEIGHT]'
                 },
                 {
                     title: 'Patient Pulse',
                     token: '[PATIENT_PULSE]'
                 },
                 {
                     title: 'Patient Respiratory Rate',
                     token: '[PATIENT_RESPIRATORY_RATE]'
                 },
                 {
                     title: 'Patient Temperature',
                     token: '[PATIENT_TEMPERATURE]'
                 },
                 {
                     title: 'Patient Weight',
                     token: '[PATIENT_WEIGHT]'
                 },
                 {
                     title: 'Patient Pulse Oximeter',
                     token: '[PATIENT_PULSE_OXIMETER]'
                 },
                 {
                     title: 'Patient Blood Preasure',
                     token: '[PATIENT_BLOOD_PREASURE]'
                 },
                 {
                     title: 'Patient Body Mass Index',
                     token: '[PATIENT_BMI]'
                 },
                 {
                     title: 'Patient Active Allergies List',
                     token: '[PATIENT_ACTIVE_ALLERGIES_LIST]'
                 },
	             {
                     title: 'Patient Inactive Allergies List',
                     token: '[PATIENT_INACTIVE_ALLERGIES_LIST]'
                 },
	             {
                     title: 'Patient Active Medications List',
                     token: '[PATIENT_ACTIVE_MEDICATIONS_LIST]'
                 },
	             {
                     title: 'Patient Inactive Medications List',
                     token: '[PATIENT_INACTIVE_MEDICATIONS_LIST]'
                 },
	             {
		             title: 'Patient Active Problems List',
		             token: '[PATIENT_ACTIVE_PROBLEMS_LIST]'
	             },
	             {
		             title: 'Patient Inactive Problems List',
		             token: '[PATIENT_INACTIVE_PROBLEMS_LIST]'
	             },
	             {
                     title: 'Patient Active Immunizations List',
                     token: '[PATIENT_ACTIVE_IMMUNIZATIONS_LIST]'
                 },
	             {
                     title: 'Patient Inactive Immunizations List',
                     token: '[PATIENT_INACTIVE_IMMUNIZATIONS_LIST]'
                 },
	             {
                     title: 'Patient Active Dental List',
                     token: '[PATIENT_ACTIVE_DENTAL_LIST]'
                 },
	             {
                     title: 'Patient Inactive Dental List',
                     token: '[PATIENT_INACTIVE_DENTAL_LIST]'
                 },
	             {
                     title: 'Patient Active Surgery List',
                     token: '[PATIENT_ACTIVE_SURGERY_LIST]'
                 },
	             {
                     title: 'Patient Inactive Surgery List',
                     token: '[PATIENT_INACTIVE_SURGERY_LIST]'
                 },
                 {
                     title: 'Encounter Date',
                     token: '[ENCOUNTER_DATE]'
                 },
                 {
                     title: 'Encounter Subjective Part',
                     token: '[ENCOUNTER_SUBJECTIVE]'
                 },
                 {
                     title: 'Encounter Objective Part',
                     token: '[ENCOUNTER_OBJECTIVE]'
                 },
                 {
                     title: 'Encounter Assesment',
                     token: '[ENCOUNTER_ASSESMENT]'
                 },
	             {
                     title: 'Encounter Assesment List',
                     token: '[ENCOUNTER_ASSESMENT_LIST]'
                 },
	             {
                     title: 'Encounter Assesment Code List',
                     token: '[ENCOUNTER_ASSESMENT_CODE_LIST]'
                 },
	             {
                     title: 'Encounter Assesment Full List',
                     token: '[ENCOUNTER_ASSESMENT_FULL_LIST]'
                 },
                 {
                     title: 'Encounter Plan',
                     token: '[ENCOUNTER_PLAN]'
                 },
                 {
                     title: 'Encounter Medications',
                     token: '[ENCOUNTER_MEDICATIONS]'
                 },
                 {
                     title: 'Encounter Immunizations',
                     token: '[ENCOUNTER_IMMUNIZATIONS]'
                 },
                 {
                     title: 'Encounter Allergies',
                     token: '[ENCOUNTER_ALLERGIES]'
                 },
                 {
                     title: 'Encounter Active Problems',
                     token: '[ENCOUNTER_ACTIVE_PROBLEMS]'
                 },
                 {
                     title: 'Encounter Surgeries',
                     token: '[ENCOUNTER_SURGERIES]'
                 },
                 {
                     title: 'Encounter Dental',
                     token: '[ENCOUNTER_DENTAL]'
                 },
                 {
                     title: 'Encounter Laboratories',
                     token: '[ENCOUNTER_LABORATORIES]'
                 },
                 {
                     title: 'Encounter Procedures Terms',
                     token: '[ENCOUNTER_PROCEDURES_TERMS]'
                 },
                 {
                     title: 'Encounter CPT Codes List',
                     token: '[ENCOUNTER_CPT_CODES]'
                 },
                 {
                     title: 'Encounter Signature',
                     token: '[ENCOUNTER_SIGNATURE]'
                 },
                 {
                     title: 'Orders Laboratories',
                     token: '[ORDERS_LABORATORIES]'
                 },
                 {
                     title: 'Orders X-Rays',
                     token: '[ORDERS_XRAYS]'
                 },
                 {
                     title: 'Orders Referral',
                     token: '[ORDERS_REFERRAL]'
                 },
                 {
                     title: 'Orders Other',
                     token: '[ORDERS_OTHER]'
                 },
                 {
                     title: 'Current Date',
                     token: '[CURRENT_DATE]'
                 },
                 {
                     title: 'Current Time',
                     token: '[CURRENT_TIME]'
                 },
                 {
                     title: 'Current User Name',
                     token: '[CURRENT_USER_NAME]'
                 },
                 {
                     title: 'Current User Full Name ',
                     token: '[CURRENT_USER_FULL_NAME]'
                 },
                 {
                     title: 'Current User License Number',
                     token: '[CURRENT_USER_LICENSE_NUMBER]'
                 },
                 {
                     title: 'Current User DEA License Number',
                     token: '[CURRENT_USER_DEA_LICENSE_NUMBER]'
                 },
	             {
                     title: 'Current User DM License Number',
                     token: '[CURRENT_USER_DM_LICENSE_NUMBER]'
                 },
	             {
                     title: 'Current User NPI License Number',
                     token: '[CURRENT_USER_NPI_LICENSE_NUMBER]'
                 }//,
//                 {
//                     title: '',
//                     token: '[]'
//                 },
//                 {
//                     title: '',
//                     token: '[]'
//                 },
//                 {
//                     title: '',
//                     token: '[]'
//                 },
//                 {
//                     title: '',
//                     token: '[]'
//                 },
//                 {
//                     title: '',
//                     token: '[]'
//                 },
//                 {
//                     title: '',
//                     token: '[]'
//                 },
//                 {
//                     title: '',
//                     token: '[]'
//                 }
             ]
         });



		me.HeaderFootergrid = Ext.create('Ext.grid.Panel', {
			title      : 'Header / Footer Templates',
			region     : 'north',
			height     : 250,
			split      : true,
			hideHeaders: true,
			store      : me.headersAndFooterStore,
			columns    : [
				{
					flex     : 1,
					sortable : true,
					dataIndex: 'title',
                    editor:{
                        xtype:'textfield',
                        allowBlank:false
                    }
				},
				{
					icon: 'ui_icons/delete.png',
					tooltip: 'Remove',
					scope:me,
					handler: me.onRemoveDocument
				}
			],
			listeners  : {
				scope    : me,
				itemclick: me.onDocumentsGridItemClick
			},
			tbar       :[
                '->',
                {
                    text : 'New',
                    scope: me,
                    handler: me.newHeaderOrFooterTemplate
                }
            ],
            plugins:[
                me.rowEditor2 = Ext.create('Ext.grid.plugin.RowEditing', {
                    clicksToEdit: 2
                })

            ]
		});

		me.DocumentsGrid = Ext.create('Ext.grid.Panel', {
			title      : 'Document Templates',
			region     : 'center',
			width      : 250,
			border     : true,
			split      : true,
            store      : me.templatesDocumentsStore,
			hideHeaders: true,
			columns    : [
				{
					flex     : 1,
					sortable : true,
					dataIndex: 'title',
                    editor:{
                        xtype:'textfield',
                        allowBlank:false
                    }
				},
				{
					icon: 'ui_icons/delete.png',
					tooltip: 'Remove',
					scope:me,
					handler: me.onRemoveDocument
				}
			],
			listeners  : {
				scope    : me,
				itemclick: me.onDocumentsGridItemClick
			},
            tbar       :[
                '->',
                {
                    text : 'New',
                    scope: me,
                    handler: me.newDocumentTemplate
                }
            ],
            plugins:[
                me.rowEditor = Ext.create('Ext.grid.plugin.RowEditing', {
                    clicksToEdit: 2
                })

            ]
		});

        me.LeftCol = Ext.create('Ext.container.Container',{
            region:'west',
            layout:'border',
            width      : 250,
            border     : false,
            split      : true,
            items:[ me.HeaderFootergrid, me.DocumentsGrid ]
        });

		me.TeamplateEditor = Ext.create('Ext.form.Panel', {
			title      : 'Document Editor',
			region     : 'center',
            layout     : 'fit',
            autoScroll : false,
			border     : true,
			split      : true,
			hideHeaders: true,
            items: {
                xtype: 'htmleditor',
                name:'body',
                margin:5
            },
            buttons    :[
                {
                    text     : 'Save',
                    scope    : me,
                    handler  : me.onSaveEditor
                },
                {
                    text     : 'Cancel',
                    scope    : me,
                    handler  : me.onCancelEditor
                }
            ]
		});


        me.TokensGrid = Ext.create('App.classes.GridPanel', {
            title      : 'Available Tokens',
            region     : 'east',
            width      : 250,
            border     : true,
            split      : true,
            hideHeaders: true,
            store:me.tokenStore,
            disableSelection:true,
            viewConfig:{
                stripeRows:false
            },
            columns    : [
                {
                    flex     : 1,
                    sortable : false,
                    dataIndex: 'token'
                },
                {
                    dataIndex: 'token',
                    width: 30,
                    xtype: "templatecolumn",
                    tpl: new Ext.XTemplate(
                        "<object id='clipboard{token}' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0' width='16' height='16' align='middle'>",
                        "<param name='allowScriptAccess' value='always' />",
                        "<param name='allowFullScreen' value='false' />",
                        "<param name='movie' value='lib/ClipBoard/clipboard.swf' />",
                        "<param name='quality' value='high' />",
                        "<param name='bgcolor' value='#ffffff' />",
                        "<param name='flashvars' value='callback=copyToClipBoard&callbackArg={token}' />",
                        "<embed src='lib/ClipBoard/clipboard.swf' flashvars='callback=copyToClipBoard&callbackArg={token}' quality='high' bgcolor='#ffffff' width='16' height='16' name='clipboard{token}' align='middle' allowscriptaccess='always' allowfullscreen='false' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' />",
                        "</object>",
                    null)
                }
            ]
        });

        me.pageBody = [ me.LeftCol, me.TeamplateEditor , me.TokensGrid ];
		me.callParent(arguments);
	},
	/**
	 * if the form is valid send the POST request
	 */
	onSave: function() {

	},
	/**
	 * Delete logic
	 */
	onDelete: function() {
		
	},

    onTokensGridItemClick:function(){

    },


    onSaveEditor:function(){
        var me = this,
            form = me.down('form').getForm(),
            record = form.getRecord(),
            values = form.getValues();
        record.set(values);
    },
    onCancelEditor:function(){
        var me = this,
            form = me.down('form').getForm(),
            grid = me.DocumentsGrid;
        form.reset();
        grid.getSelectionModel().deselectAll();
    },

    onDocumentsGridItemClick:function(grid, record){
        var me = this;
        var form = me.down('form').getForm();
        form.loadRecord(record);

    },
    newDocumentTemplate:function(){
        var me = this,
            store = me.templatesDocumentsStore;
        me.rowEditor.cancelEdit();
        store.insert(0,{
            title:'New Document',
            date: new Date(),
	        type: 1
        });
        me.rowEditor.startEdit(0, 0);

    },

	newHeaderOrFooterTemplate:function(){
        var me = this,
            store = me.headersAndFooterStore;
        me.rowEditor2.cancelEdit();
        store.insert(0,{
            title:'New Header Or Footer',
            date: new Date(),
	        type: 2
        });
        me.rowEditor2.startEdit(0, 0);

    },

    copyToClipBoard:function(grid, rowIndex, colIndex){
        var rec = grid.getStore().getAt(rowIndex),
            text = rec.get('token');
    },

	onRemoveDocument:function(){


	},
	
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive            : function(callback) {
        var me = this;
        me.templatesDocumentsStore.load();
        me.headersAndFooterStore.load();
		callback(true);
	}
});