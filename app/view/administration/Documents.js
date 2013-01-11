/*
 GaiaEHR (Electronic Health Records)
 Documents.js
 Documents
 Copyright (C) 2012 Ernesto Rodriguez

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
Ext.define('App.view.administration.Documents',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelDocuments',
	pageTitle : i18n('document_template_editor'),
	pageLayout : 'border',
	uses : ['App.ux.GridPanel'],
	initComponent : function()
	{

		var me = this;

		me.templatesDocumentsStore = Ext.create('App.store.administration.DocumentsTemplates');
		//		me.headersAndFooterStore   = Ext.create('App.store.administration.HeadersAndFooters');
		me.defaultsDocumentsStore = Ext.create('App.store.administration.DefaultDocuments');

		Ext.define('tokenModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'title',
				type : 'string'
			},
			{
				name : 'token',
				type : 'string'
			}]
		});
		me.tokenStore = Ext.create('Ext.data.Store',
		{
			model : 'tokenModel',
			data : [
			{
				title : i18n('patient_name'),
				token : '[PATIENT_NAME]'
			},
			{
				title : i18n('patient_full_name'),
				token : '[PATIENT_FULL_NAME]'
			},
			{
				title : i18n('patient_mothers_maiden_name'),
				token : '[PATIENT_MAIDEN_NAME]'
			},
			{
				title : i18n('patient_last_name'),
				token : '[PATIENT_LAST_NAME]'
			},
			{
				title : i18n('patient_birthdate'),
				token : '[PATIENT_BIRTHDATE]'
			},
			{
				title : i18n('patient_marital_status'),
				token : '[PATIENT_MARITAL_STATUS]'
			},
			{
				title : i18n('patient_home_phone'),
				token : '[PATIENT_HOME_PHONE]'
			},
			{
				title : i18n('patient_mobile_phone'),
				token : '[PATIENT_MOBILE_PHONE]'
			},
			{
				title : i18n('patient_work_phone'),
				token : '[PATIENT_WORK_PHONE]'
			},
			{
				title : i18n('patient_email'),
				token : '[PATIENT_EMAIL]'
			},
			{
				title : i18n('patient_social_security'),
				token : '[PATIENT_SOCIAL_SECURITY]'
			},
			{
				title : i18n('patient_sex'),
				token : '[PATIENT_SEX]'
			},
			{
				title : i18n('patient_age'),
				token : '[PATIENT_AGE]'
			},
			{
				title : i18n('patient_city'),
				token : '[PATIENT_CITY]'
			},
			{
				title : i18n('patient_state'),
				token : '[PATIENT_STATE]'
			},
			{
				title : i18n('patient_home_address_line_1'),
				token : '[PATIENT_HOME_ADDRESS_LINE_ONE]'
			},
			{
				title : i18n('patient_home_address_line_1'),
				token : '[PATIENT_HOME_ADDRESS_LINE_TWO]'
			},
			{
				title : i18n('patient_home_address_zip_code'),
				token : '[PATIENT_HOME_ADDRESS_ZIP_CODE]'
			},
			{
				title : i18n('patient_home_address_city'),
				token : '[PATIENT_HOME_ADDRESS_CITY]'
			},
			{
				title : i18n('patient_home_address_state'),
				token : '[PATIENT_HOME_ADDRESS_STATE]'
			},
			{
				title : i18n('patient_postal_address_line_1'),
				token : '[PATIENT_POSTAL_ADDRESS_LINE_ONE]'
			},
			{
				title : i18n('patient_postal_address_line_2'),
				token : '[PATIENT_POSTAL_ADDRESS_LINE_TWO]'
			},
			{
				title : i18n('patient_postal_address_zip_code'),
				token : '[PATIENT_POSTAL_ADDRESS_ZIP_CODE]'
			},
			{
				title : i18n('patient_postal_address_city'),
				token : '[PATIENT_POSTAL_ADDRESS_CITY]'
			},
			{
				title : i18n('patient_postal_address_state'),
				token : '[PATIENT_POSTAL_ADDRESS_STATE]'
			},
			{
				title : i18n('patient_tabacco'),
				token : '[PATIENT_TABACCO]'
			},
			{
				title : i18n('patient_alcohol'),
				token : '[PATIENT_ALCOHOL]'
			},
			{
				title : i18n('patient_drivers_license'),
				token : '[PATIENT_DRIVERS_LICENSE]'
			},
			{
				title : i18n('patient_employeer'),
				token : '[PATIENT_EMPLOYEER]'
			},
			{
				title : i18n('patient_first_emergency_contact'),
				token : '[PATIENT_FIRST_EMERGENCY_CONTACT]'
			},
			{
				title : i18n('patient_referral'),
				token : '[PATIENT_REFERRAL]'
			},
			{
				title : i18n('patient_date_referred'),
				token : '[PATIENT_REFERRAL_DATE]'
			},
			{
				title : i18n('patient_balance'),
				token : '[PATIENT_BALANCE]'
			},
			{
				title : i18n('patient_picture'),
				token : '[PATIENT_PICTURE]'
			},
			{
				title : i18n('patient_primary_plan'),
				token : '[PATIENT_PRIMARY_PLAN]'
			},
			{
				title : i18n('patient_primary_plan_insured_person'),
				token : '[PATIENT_PRIMARY_INSURED_PERSON]'
			},
			{
				title : i18n('patient_primary_plan_contract_number'),
				token : '[PATIENT_PRIMARY_CONTRACT_NUMBER]'
			},
			{
				title : i18n('patient_primary_plan_expiration_date'),
				token : '[PATIENT_PRIMARY_EXPIRATION_DATE]'
			},
			{
				title : i18n('patient_secondary_plan'),
				token : '[PATIENT_SECONDARY_PLAN]'
			},
			{
				title : i18n('patient_secondary_insured_person'),
				token : '[PATIENT_SECONDARY_INSURED_PERSON]'
			},
			{
				title : i18n('patient_secondary_plan_contract_number'),
				token : '[PATIENT_SECONDARY_CONTRACT_NUMBER]'
			},
			{
				title : i18n('patient_secondary_plan_expiration_date'),
				token : '[PATIENT_SECONDARY_EXPIRATION_DATE]'
			},
			{
				title : i18n('patient_referral_details'),
				token : '[PATIENT_REFERRAL_DETAILS]'
			},
			{
				title : i18n('patient_referral_reason'),
				token : '[PATIENT_REFERRAL_REASON]'
			},
			{
				title : i18n('patient_head_circumference'),
				token : '[PATIENT_HEAD_CIRCUMFERENCE]'
			},
			{
				title : i18n('patient_height'),
				token : '[PATIENT_HEIGHT]'
			},
			{
				title : i18n('patient_pulse'),
				token : '[PATIENT_PULSE]'
			},
			{
				title : i18n('patient_respiratory_rate'),
				token : '[PATIENT_RESPIRATORY_RATE]'
			},
			{
				title : i18n('patient_temperature'),
				token : '[PATIENT_TEMPERATURE]'
			},
			{
				title : i18n('patient_weight'),
				token : '[PATIENT_WEIGHT]'
			},
			{
				title : i18n('patient_pulse_oximeter'),
				token : '[PATIENT_PULSE_OXIMETER]'
			},
			{
				title : i18n('patient_blood_preasure'),
				token : '[PATIENT_BLOOD_PREASURE]'
			},
			{
				title : i18n('patient_body_mass_index'),
				token : '[PATIENT_BMI]'
			},
			{
				title : i18n('patient_active_allergies_list'),
				token : '[PATIENT_ACTIVE_ALLERGIES_LIST]'
			},
			{
				title : i18n('patient_inactive_allergies_list'),
				token : '[PATIENT_INACTIVE_ALLERGIES_LIST]'
			},
			{
				title : i18n('patient_active_medications_list'),
				token : '[PATIENT_ACTIVE_MEDICATIONS_LIST]'
			},
			{
				title : i18n('patient_inactive_medications_list'),
				token : '[PATIENT_INACTIVE_MEDICATIONS_LIST]'
			},
			{
				title : i18n('patient_active_problems_list'),
				token : '[PATIENT_ACTIVE_PROBLEMS_LIST]'
			},
			{
				title : i18n('patient_inactive_problems_list'),
				token : '[PATIENT_INACTIVE_PROBLEMS_LIST]'
			},
			{
				title : i18n('patient_active_immunizations_list'),
				token : '[PATIENT_ACTIVE_IMMUNIZATIONS_LIST]'
			},
			{
				title : i18n('patient_inactive_immunizations_list'),
				token : '[PATIENT_INACTIVE_IMMUNIZATIONS_LIST]'
			},
			{
				title : i18n('patient_active_dental_list'),
				token : '[PATIENT_ACTIVE_DENTAL_LIST]'
			},
			{
				title : i18n('patient_inactive_dental_list'),
				token : '[PATIENT_INACTIVE_DENTAL_LIST]'
			},
			{
				title : i18n('patient_active_surgery_list'),
				token : '[PATIENT_ACTIVE_SURGERY_LIST]'
			},
			{
				title : i18n('patient_inactive_surgery_list'),
				token : '[PATIENT_INACTIVE_SURGERY_LIST]'
			},
			{
				title : i18n('encounter_date'),
				token : '[ENCOUNTER_DATE]'
			},
			{
				title : i18n('encounter_subjective_part'),
				token : '[ENCOUNTER_SUBJECTIVE]'
			},
			{
				title : i18n('encounter_subjective_part'),
				token : '[ENCOUNTER_OBJECTIVE]'
			},
			{
				title : i18n('encounter_assessment'),
				token : '[ENCOUNTER_ASSESSMENT]'
			},
			{
				title : i18n('encounter_assessment_list'),
				token : '[ENCOUNTER_ASSESSMENT_LIST]'
			},
			{
				title : i18n('encounter_assessment_code_list'),
				token : '[ENCOUNTER_ASSESSMENT_CODE_LIST]'
			},
			{
				title : i18n('encounter_assessment_full_list'),
				token : '[ENCOUNTER_ASSESSMENT_FULL_LIST]'
			},
			{
				title : i18n('encounter_plan'),
				token : '[ENCOUNTER_PLAN]'
			},
			{
				title : i18n('encounter_medications'),
				token : '[ENCOUNTER_MEDICATIONS]'
			},
			{
				title : i18n('encounter_immunizations'),
				token : '[ENCOUNTER_IMMUNIZATIONS]'
			},
			{
				title : i18n('encounter_allergies'),
				token : '[ENCOUNTER_ALLERGIES]'
			},
			{
				title : i18n('encounter_active_problems'),
				token : '[ENCOUNTER_ACTIVE_PROBLEMS]'
			},
			{
				title : i18n('encounter_surgeries'),
				token : '[ENCOUNTER_SURGERIES]'
			},
			{
				title : i18n('encounter_dental'),
				token : '[ENCOUNTER_DENTAL]'
			},
			{
				title : i18n('encounter_laboratories'),
				token : '[ENCOUNTER_LABORATORIES]'
			},
			{
				title : i18n('encounter_procedures_terms'),
				token : '[ENCOUNTER_PROCEDURES_TERMS]'
			},
			{
				title : i18n('encounter_cpt_codes_list'),
				token : '[ENCOUNTER_CPT_CODES]'
			},
			{
				title : i18n('encounter_signature'),
				token : '[ENCOUNTER_SIGNATURE]'
			},
			{
				title : i18n('orders_laboratories'),
				token : '[ORDERS_LABORATORIES]'
			},
			{
				title : i18n('orders_x_rays'),
				token : '[ORDERS_XRAYS]'
			},
			{
				title : i18n('orders_referral'),
				token : '[ORDERS_REFERRAL]'
			},
			{
				title : i18n('orders_other'),
				token : '[ORDERS_OTHER]'
			},
			{
				title : i18n('current_date'),
				token : '[CURRENT_DATE]'
			},
			{
				title : i18n('current_time'),
				token : '[CURRENT_TIME]'
			},
			{
				title : i18n('current_user_name'),
				token : '[CURRENT_USER_NAME]'
			},
			{
				title : i18n('current_user_full_name'),
				token : '[CURRENT_USER_FULL_NAME]'
			},
			{
				title : i18n('current_user_license_number'),
				token : '[CURRENT_USER_LICENSE_NUMBER]'
			},
			{
				title : i18n('current_user_dea_license_number'),
				token : '[CURRENT_USER_DEA_LICENSE_NUMBER]'
			},
			{
				title : i18n('current_user_dm_license_number'),
				token : '[CURRENT_USER_DM_LICENSE_NUMBER]'
			},
			{
				title : i18n('current_user_npi_license_number'),
				token : '[CURRENT_USER_NPI_LICENSE_NUMBER]'
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

		//		me.HeaderFootergrid = Ext.create('Ext.grid.Panel', {
		//			title      : i18n('header_footer_templates'),
		//			region     : 'south',
		//			height     : 250,
		//			split      : true,
		//			hideHeaders: true,
		//			store      : me.headersAndFooterStore,
		//			columns    : [
		//				{
		//					flex     : 1,
		//					sortable : true,
		//					dataIndex: 'title',
		//                    editor:{
		//                        xtype:'textfield',
		//                        allowBlank:false
		//                    }
		//				},
		//				{
		//					icon: 'resources/images/icons/delete.png',
		//					tooltip: i18n('remove'),
		//					scope:me,
		//					handler: me.onRemoveDocument
		//				}
		//			],
		//			listeners  : {
		//				scope    : me,
		//				itemclick: me.onDocumentsGridItemClick
		//			},
		//			tbar       :[
		//                '->',
		//                {
		//                    text : i18n('new'),
		//                    scope: me,
		//                    handler: me.newHeaderOrFooterTemplate
		//                }
		//            ],
		//            plugins:[
		//                me.rowEditor2 = Ext.create('Ext.grid.plugin.RowEditing', {
		//                    clicksToEdit: 2
		//                })
		//
		//            ]
		//		});

		me.DocumentsDefaultsGrid = Ext.create('Ext.grid.Panel',
		{
			title : i18n('documents_defaults'),
			region : 'north',
			width : 250,
			border : true,
			split : true,
			store : me.defaultsDocumentsStore,
			hideHeaders : true,
			columns : [
			{
				flex : 1,
				sortable : true,
				dataIndex : 'title',
				editor :
				{
					xtype : 'textfield',
					allowBlank : false
				}
			},
			{
				icon : 'resources/images/icons/delete.png',
				tooltip : i18n('remove'),
				scope : me,
				handler : me.onRemoveDocument
			}],
			listeners :
			{
				scope : me,
				itemclick : me.onDocumentsGridItemClick
			},
			tbar : ['->',
			{
				text : i18n('new'),
				scope : me,
				handler : me.newDefaultTemplates
			}],
			plugins : [me.rowEditor3 = Ext.create('Ext.grid.plugin.RowEditing',
			{
				clicksToEdit : 2
			})]
		});

		me.DocumentsGrid = Ext.create('Ext.grid.Panel',
		{
			title : i18n('document_templates'),
			region : 'center',
			width : 250,
			border : true,
			split : true,
			store : me.templatesDocumentsStore,
			hideHeaders : true,
			columns : [
			{
				flex : 1,
				sortable : true,
				dataIndex : 'title',
				editor :
				{
					xtype : 'textfield',
					allowBlank : false
				}
			},
			{
				icon : 'resources/images/icons/delete.png',
				tooltip : i18n('remove'),
				scope : me,
				handler : me.onRemoveDocument
			}],
			listeners :
			{
				scope : me,
				itemclick : me.onDocumentsGridItemClick
			},
			tbar : ['->',
			{
				text : i18n('new'),
				scope : me,
				handler : me.newDocumentTemplate
			}],
			plugins : [me.rowEditor = Ext.create('Ext.grid.plugin.RowEditing',
			{
				clicksToEdit : 2
			})]
		});

		me.LeftCol = Ext.create('Ext.container.Container',
		{
			region : 'west',
			layout : 'border',
			width : 250,
			border : false,
			split : true,
			items : [me.DocumentsDefaultsGrid, me.DocumentsGrid]
		});

		me.TeamplateEditor = Ext.create('Ext.form.Panel',
		{
			title : i18n('document_editor'),
			region : 'center',
			layout : 'fit',
			autoScroll : false,
			border : true,
			split : true,
			hideHeaders : true,
			items :
			{
				xtype : 'htmleditor',
				enableFontSize : false,
				name : 'body',
				margin : 5
			},
			buttons : [
			{
				text : i18n('save'),
				scope : me,
				handler : me.onSaveEditor
			},
			{
				text : i18n('cancel'),
				scope : me,
				handler : me.onCancelEditor
			}]
		});

		me.TokensGrid = Ext.create('App.ux.GridPanel',
		{
			title : i18n('available_tokens'),
			region : 'east',
			width : 250,
			border : true,
			split : true,
			hideHeaders : true,
			store : me.tokenStore,
			disableSelection : true,
			viewConfig :
			{
				stripeRows : false
			},
			columns : [
			{
				flex : 1,
				sortable : false,
				dataIndex : 'token'
			},
			{
				dataIndex : 'token',
				width : 30,
				xtype : "templatecolumn",
				tpl : new Ext.XTemplate("<object id='clipboard{token}' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0' width='16' height='16' align='middle'>", "<param name='allowScriptAccess' value='always' />", "<param name='allowFullScreen' value='false' />", "<param name='movie' value='lib/ClipBoard/clipboard.swf' />", "<param name='quality' value='high' />", "<param name='bgcolor' value='#ffffff' />", "<param name='flashvars' value='callback=copyToClipBoard&callbackArg={token}' />", "<embed src='lib/ClipBoard/clipboard.swf' flashvars='callback=copyToClipBoard&callbackArg={token}' quality='high' bgcolor='#ffffff' width='16' height='16' name='clipboard{token}' align='middle' allowscriptaccess='always' allowfullscreen='false' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' />", "</object>", null)
			}]
		});

		me.pageBody = [me.LeftCol, me.TeamplateEditor, me.TokensGrid];
		me.callParent();
	},
	/**
	 * Delete logic
	 */
	onDelete : function()
	{

	},

	onTokensGridItemClick : function()
	{

	},

	onSaveEditor : function()
	{
		var me = this, form = me.down('form').getForm(), record = form.getRecord(), values = form.getValues();
		record.set(values);
	},
	onCancelEditor : function()
	{
		var me = this, form = me.down('form').getForm(), grid = me.DocumentsGrid;
		form.reset();
		grid.getSelectionModel().deselectAll();
	},

	onDocumentsGridItemClick : function(grid, record)
	{
		var me = this;
		var form = me.down('form').getForm();
		form.loadRecord(record);

	},
	newDocumentTemplate : function()
	{
		var me = this, store = me.templatesDocumentsStore;
		me.rowEditor.cancelEdit();
		store.insert(0,
		{
			title : i18n('new_document'),
			template_type : 'documenttemplate',
			date : new Date(),
			type : 1
		});
		me.rowEditor.startEdit(0, 0);

	},

	newDefaultTemplates : function()
	{
		var me = this, store = me.defaultsDocumentsStore;
		me.rowEditor3.cancelEdit();
		store.insert(0,
		{
			title : i18n('new_defaults'),
			template_type : 'defaulttemplate',
			date : new Date(),
			type : 1
		});
		me.rowEditor3.startEdit(0, 0);

	},

	//	newHeaderOrFooterTemplate:function(){
	//        var me = this,
	//            store = me.headersAndFooterStore;
	//        me.rowEditor2.cancelEdit();
	//        store.insert(0,{
	//            title: i18n('new_header_or_footer'),
	//	        template_type:'headerorfootertemplate',
	//            date: new Date(),
	//	        type: 2
	//        });
	//        me.rowEditor2.startEdit(0, 0);
	//
	//    },

	copyToClipBoard : function(grid, rowIndex, colIndex)
	{
		var rec = grid.getStore().getAt(rowIndex), text = rec.get('token');
	},

	onRemoveDocument : function()
	{

	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		var me = this
		me.templatesDocumentsStore.load();
		//        me.headersAndFooterStore.load();
		me.defaultsDocumentsStore.load();
		callback(true);
	}
});
