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

Ext.define('App.view.sitesetup.SiteSetup',
	{
		extend:'Ext.window.Window',
		title:'GaiaEHR Site Setup',
		bodyPadding:5,
		y:90,
		width:902,
		height:500,
		modal:false,
		resizable:false,
		draggable:false,
		closable:false,
		bodyStyle:'background-color: #ffffff',
		layout:{
			type:'vbox',
			align:'stretch'
		},
		requires:[
			'App.ux.form.fields.Help',
			'App.ux.form.fields.plugin.HelpIcon',
			'App.ux.window.CopyRights',
			'App.ux.combo.Languages',
			'App.ux.combo.TimeZone',
			'App.ux.combo.Themes'
		],
		initComponent:function(){
			var me = this;
			/**
			 * array to store each step success and data
			 *
			 * me.step[0] = Welcome!
			 * me.step[1] = System Compatibility
			 * me.step[2] = Database Configuration
			 * me.step[3] = Site Configuration
			 * me.step[4] = Installation Complete!
			 *
			 * @type {Array}
			 */
			me.step = [];

			/*
			 * Store: requirementsStore
			 */
			me.requirementsStore = Ext.create('App.store.sitesetup.Requirements');

			/*
			 * Store: enviromentStore
			 */
			me.enviromentStore = Ext.create('Ext.data.Store',
				{
					fields:['value', 'name'],
					data:[
						{value:'Production', name:'Production/Clinic'},
						{value:'Development', name:'Development'},
						{value:'Testing', name:'Testing'}
					]
				});

			/*
			 * Simple bool store, yes or no.
			 */
			me.boolStore = Ext.create('Ext.data.Store',
				{
					fields:['value', 'name'],
					data:[
						{value:'Yes', name:'Yes'},
						{value:'No', name:'No'}
					]
				});

			/*
			 * Size of the clinic, hospital, ect.
			 */
			me.sizeStore = Ext.create('Ext.data.Store', {
				fields:['value', 'name'],
				data:[
					{value:'1', name:'1 User'},
					{value:'2-5', name:'2-5 Users'},
					{value:'6-10', name:'6-10 Users'},
					{value:'11-20', name:'11-20 Users'},
					{value:'20+', name:'20+ Users'}
				]
			});

			/**
			 * Copy Rights window
			 * @type {*}
			 */
			me.winCopyright = Ext.create('App.ux.window.CopyRights');

			/**
			 * Site Setup window
			 * @type {Array}
			 */
			me.items = [
				me.headerPanel = Ext.create('Ext.Container',
					{
						cls:'siteSetupHeader',
						height:45,
						items:[
							me.welcomeBtn = Ext.create('Ext.Button',
								{
									scale:'large',
									iconCls:'icoGrayFace',
									componentCls:'setupBts',
									margin:'0 38 0 0',
									iconAlign:'right',
									enableToggle:true,
									toggleGroup:'siteSetup',
									text:'1.Welcome!',
									scope:me,
									action:0,
									pressed:true,
									handler:me.onHeaderBtnPress
								}
							),
							me.compatibiltyBtn = Ext.create('Ext.Button',
								{
									scale:'large',
									iconCls:'icoGrayFace',
									componentCls:'setupBts',
									margin:'0 38 0 0',
									iconAlign:'right',
									enableToggle:true,
									toggleGroup:'siteSetup',
									disabled:true,
									text:'2.System Compatibility',
									scope:me,
									action:1,
									handler:me.onHeaderBtnPress
								}
							),
							me.databaseBtn = Ext.create('Ext.Button',
								{
									scale:'large',
									iconCls:'icoGrayFace',
									componentCls:'setupBts',
									margin:'0 38 0 0',
									iconAlign:'right',
									enableToggle:true,
									toggleGroup:'siteSetup',
									disabled:true,
									text:'3.Database Configuration',
									scope:me,
									action:2,
									handler:me.onHeaderBtnPress
								}
							),
							me.siteConfigurationBtn = Ext.create('Ext.Button',
								{
									scale:'large',
									iconCls:'icoGrayFace',
									componentCls:'setupBts',
									margin:'0 38 0 0',
									iconAlign:'right',
									enableToggle:true,
									toggleGroup:'siteSetup',
									disabled:true,
									text:'Site Configuration',
									scope:me,
									action:3,
									handler:me.onHeaderBtnPress
								}
							),
							me.completeBtn = Ext.create('Ext.Button',
								{
									scale:'large',
									iconCls:'icoGrayFace',
									componentCls:'setupBts',
									iconAlign:'right',
									enableToggle:true,
									toggleGroup:'siteSetup',
									disabled:true,
									text:'4.Installation Complete!',
									scope:me,
									action:4,
									handler:me.onHeaderBtnPress
								}
							)
						]
					}),

				me.mainPanel = Ext.create('Ext.Container',
					{
						flex:1,
						layout:'card',
						//                activeItem:3,
						items:[
							me.welcome = Ext.create('Ext.Container',
								{
									action:0,
									items:[
										{
											xtype:'panel',
											title:'Welcome to GaiaEHR Site Setup',
											styleHtmlContent:true,
											cls:'welcome',
											layout:'auto',
											items:[
												{
													xtype:'container',
													height:120,
													padding:'5 10 0 10',
													html:' <p>Please allow 10-15 minutes to complete the installation process.</p>' + '<p>The GaiaEHR Site Setup will do most of the work for you in just a few clicks.</p>' + '<p>However, you must know how to do the following:</p>' + '<ul>' + '<li>Set permissions on folders & subfolders using an FTP client</li>' + '<li>Create a MySQL database using phpMyAdmin (or by asking your hosting provider)</li>' + '</ul>'
												},
												{
													xtype:'fieldset',
													title:'License Agreement',
													defaultType:'textfield',
													layout:'anchor',
													margin:'0 5 5 5',
													items:[
														me.licence = Ext.create('Ext.Container',
															{
																height:170,
																styleHtmlContent:true,
																autoScroll:true,
																autoLoad:'gpl-licence-en.html'
															}), me.licAgreement = Ext.create('Ext.form.field.Checkbox',
															{
																boxLabel:'I agree to the GaiaEHR terms and conditions',
																name:'topping',
																margin:'5 0 0 0',
																inputValue:'1',
																scope:me,
																handler:me.licenceChecked
															})
													]}
											]
										}
									]
								}),
							me.requirementsGrid = Ext.create('Ext.grid.Panel',
								{
									store:me.requirementsStore,
									frame:false,
									title:'Requirements',
									action:1,
									viewConfig:{stripeRows:true},
									listeners:{
										scope:me,
										show:me.loadRequirements
									},
									columns:[
										{
											text:'Requirements',
											flex:1,
											sortable:false,
											dataIndex:'msg'
										},
										{
											text:'Status',
											width:150,
											sortable:true,
											renderer:me.statusRenderer,
											dataIndex:'status'
										}
									],
									tools:[
										{
											type:'refresh',
											tooltip:'ReCheck Requirements',
											handler:function(){
												me.requirementsStore.load(
													{
														scope:me,
														callback:me.onRequirementsStoreLoad
													});
											}
										}
									],
									bbar:['->', '-',
										{
											text:'Re-Check Requirements',
											handler:function(){
												me.requirementsStore.load(
													{
														scope:me,
														callback:me.onRequirementsStoreLoad
													});
											}
										}, '-'
									]
								}),
							me.databaseConfiguration = Ext.create('Ext.form.Panel',
								{
									title:'Database Configuration',
									defaultType:'textfield',
									bodyPadding:'0 10',
									action:2,
									items:[
										{
											xtype:'displayfield',
											padding:'10px',
											value:'Choose if you want to <a href="javascript:void(0);" onClick="Ext.getCmp(\'rootFieldset\').enable();">create a new database</a> or use an <a href="javascript:void(0);" onClick="Ext.getCmp(\'dbuserFieldset\').enable();">existing database</a><br>'
										},
										{
											xtype:'fieldset',
											id:'rootFieldset',
											title:'Create a New Database (Root Access Needed)',
											defaultType:'textfield',
											collapsed:true,
											disabled:true,
											layout:'anchor',
											defaults:{anchor:'100%'},
											items:[
												{
													fieldLabel:'Root User',
													name:'rootUser',
													value:'root',
													allowBlank:false
												},
												{
													fieldLabel:'Root Password',
													name:'rootPass',
													id:'rootPass',
													inputType:'password',
													// value:'pass',
													allowBlank:true
												},
												{
													fieldLabel:'SQL Server Host or IP address',
													name:'dbHost',
													value:'127.0.0.1',
													allowBlank:false
												},
												{
													fieldLabel:'SQL Server Port',
													name:'dbPort',
													value:'3306',
													allowBlank:false
												},
												{
													fieldLabel:'Database Name',
													name:'dbName',
													value:'gaiadb',
													allowBlank:false
												},
												{
													fieldLabel:'New Database User',
													name:'dbUser',
													//value     : 'test',
													allowBlank:false
												},
												{
													fieldLabel:'New Database Pass',
													name:'dbPass',
													inputType:'password',
													// value     : 'test',
													allowBlank:false
												}
											],
											listeners:{
												enable:function(){
													conn = 'root';
													Ext.getCmp('dbuserFieldset').collapse();
													Ext.getCmp('dbuserFieldset').disable();
													Ext.getCmp('rootFieldset').expand();
												}
											}
										},
										{
											xtype:'fieldset',
											id:'dbuserFieldset',
											title:'Install on a existing database',
											defaultType:'textfield',
											collapsed:true,
											disabled:true,
											layout:'anchor',
											defaults:{anchor:'100%'},
											items:[
												{
													fieldLabel:'Database Name',
													name:'dbName',
													// value     : 'gaiadb',
													allowBlank:false
												},
												{
													fieldLabel:'Database User',
													name:'dbUser',
													// value     : 'gaiadb',
													allowBlank:false
												},
												{
													fieldLabel:'Database Pass',
													name:'dbPass',
													id:'dbPass',
													inputType:'password',
													// value     : 'pass',
													allowBlank:false
												},
												{
													fieldLabel:'Database Host',
													name:'dbHost',
													value:'localhost',
													allowBlank:false
												},
												{
													fieldLabel:'Database Port',
													name:'dbPort',
													value:'3306',
													allowBlank:false
												}
											],
											listeners:{
												enable:function(){
													conn = 'user';
													Ext.getCmp('rootFieldset').collapse();
													Ext.getCmp('rootFieldset').disable();
													Ext.getCmp('dbuserFieldset').expand();
												}
											}
										}
									],
									bbar:[
										'**Database Connection Test is Required to Continue -->>', '->', '-', {
											text:'Database Connection Test',
											action:'dataTester',
											scope:me,
											handler:me.onDbTestCredentials
										}, '-'
									]
								}), me.siteConfiguration = Ext.create('Ext.form.Panel', {
								title:'Site configuration',
								defaultType:'textfield',
								bodyPadding:'7',
								action:3,
								listeners:{
									scope:me,
									show:me.onSiteConfigurationShow
								},
								items:[
									me.siteConfigurationContainer = Ext.create('Ext.container.Container', {
										//                                floating:true,
										//                                x:0,
										//                                y:0,
										//                                shadow:false,
										//                                hidden:false,
										//                                width:855,
										//                                height:335,
										items:[
											{
												xtype:'fieldset',
												title:'Site / Admin Info (required)',
												layout:'anchor',
												defaults:{ margin:'4 0'},
												items:[
													{
														xtype:'textfield',
														fieldLabel:'Site ID',
														name:'siteId',
														value:'default',
														enableKeyEvents:true,
														allowBlank:false,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'Most GaiaEHR installations will support only one site.<br>' + 'If that is the case for you, leave Site ID on <span style="font-weight: bold;">"default"</span>.<br>' + 'Otherwise, use a Site ID short identifier with no spaces<br>' + 'or special characters other dashes. It is case-sensitive,<br>' + 'we suggest sticking to lower case letters for ease of use'
															}
														],
														listeners:{
															scope:me,
															keyup:me.isReadyForInstall
														}
													},
													{
														xtype:'textfield',
														fieldLabel:'Admin username',
														name:'adminUsername',
														value:'admin',
														enableKeyEvents:true,
														allowBlank:false,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'**Username must be between <span style="font-weight: bold;">4 to 10</span> characters long<br>' + '**Do not use special characters. ei. <span style="font-weight: bold;">"!@#$%^&*()</span>'
															}
														],
														listeners:{
															scope:me,
															keyup:me.isReadyForInstall
														}
													},
													{

														xtype:'textfield',
														fieldLabel:'Admin password',
														inputType:'password',
														name:'adminPassword',
														// value          : 'pass',
														minLength: 4,
														maxLength: 15,
														enableKeyEvents:true,
														allowBlank:false,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'**Password must be between <span style="font-weight: bold;">6 to 8</span> characters long<br>' + '**Do not use special characters. ei. <span style="font-weight: bold;">"!@#$%^&*()</span>'
															}
														],
														listeners:{
															scope:me,
															keyup:me.isReadyForInstall
														}
													},
													{
														xtype:'themescombo',
														fieldLabel:'Site Theme',
														name:'theme',
														allowBlank:false,
														width:300,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'**The themes will change the visual aspect.<br>' + '**This can be change later in the Administrator -> Global Setting'
															}
														],
														listeners:{
															scope:me,
															change:me.isReadyForInstall
														}
													},
													{
														xtype:'languagescombo',
														fieldLabel:'Default Language',
														name:'lang',
														allowBlank:false,
														width:300,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'**This default language will be the default language during the Logon window.<br>' + '**This can be change later in the Administrator -> Global Setting'
															}
														],
														listeners:{
															scope:me,
															change:me.isReadyForInstall
														}
													},
													{
														xtype:'timezonecombo',
														fieldLabel:'Default TimeZone',
														name:'timezone',
														allowBlank:false,
														width:350,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'**This default language will be the default language during the Logon window.<br>' + '**This can be change later in the Administrator -> Global Setting'
															}
														],
														listeners:{
															scope:me,
															change:me.isReadyForInstall
														}
													}
												]
											},
											{
												xtype:'fieldset',
												title:'Site Options (optional)',
												layout:'anchor',
												margin:'0 0 7 0',
												defaults:{ margin:'4 0'},
												items:[
													{
														xtype:'checkboxfield',
														fieldLabel:'Load ICD9',
														name:'ICD9',
														inputValue:'1',
														action:'code',
														disabled: true,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'Load ICD9 Codes will add a <span style="font-weight: bold;">few minutes</span> to the installation process.'
															}
														]
													},
													{
														xtype:'checkboxfield',
														fieldLabel:'Load ICD10',
														name:'ICD10',
														inputValue:'1',
														action:'code',
														disabled: true,
														plugins:[
															{
																ptype:'helpicon',
																helpMsg:'Load ICD10 Codes will add a <span style="font-weight: bold;">few minutes</span> to the installation process.'
															}
														]
														//                                            },
														//                                            {
														//                                                xtype     : 'checkboxfield',
														//                                                fieldLabel: 'Load SNOMED',
														//                                                name      : 'SNOMED',
														//                                                inputValue: '1',
														//                                                action:'code',
														//                                                plugins   : [
														//                                                    {
														//                                                        ptype  : 'helpicon',
														//                                                        helpMsg: 'Load SNOMED Codes will add a <span style="font-weight: bold;">5 to 10 minutes</span> to the installation process.'
														//                                                    }
														//                                                ]
														//                                            },
														//                                            {
														//                                                xtype     : 'checkboxfield',
														//                                                fieldLabel: 'Load RxNorm',
														//                                                name      : 'RXNORM',
														//                                                inputValue: '1',
														//                                                action:'code',
														//                                                plugins   : [
														//                                                    {
														//                                                        ptype  : 'helpicon',
														//                                                        helpMsg: 'Load RxNorm Codes will add <span style="font-weight: bold;">30 to 60 minutes</span> to the installation process.'
														//                                                    }
														//                                                ]
													}
												]
											}
										]
									}),
									me.installationPregress = Ext.create('Ext.ProgressBar', {
										hidden:true,
										text:'progress'
									})
								]
							}), me.installationComplete = Ext.create('Ext.panel.Panel', {
								title:'Installation Complete',
								bodyPadding:'0 5',
								action:4,
								items:[
									me.installationDetails = Ext.create('Ext.container.Container', {
										height:180,
										padding:'0 5',
										styleHtmlContent:true,
										tpl:new Ext.XTemplate(
											'<h2><span style="color: #008000;">Sweet!</span> Your New site is ready.</h2>' +
												'<p>Installation Details:</p>' +
												'<ul>' +
												'   <li>Site Id: {siteId}</li>' +
												'   <li>User: {adminUsername}</li>' +
												'   <li>Password: {adminPassword}</li>' +
												'   <li>Site URL: <a href="{siteURL}" target="_self">{siteURL}</a></li>' +
												'</ul>' +
												'<p style="color: #008000;">Please take a few moment to answer the survey. We use this info to continue improve GaiaEHR user experience.</p>'
										)
									}),
									me.survey = Ext.create('Ext.form.Panel', {
										border:false,
										margin:0,
										padding:0,
										bodyPadding:0,
										layout:'absolute',
										items:[
											me.surveyFields = Ext.create('Ext.form.FieldSet', {
												title:'Survey (optional)',
												layout:'anchor',
												height:172,
												defaults:{ margin:'4 0', labelWidth:150, width:400},
												items:[
													{
														xtype:'combobox',
														fieldLabel:'Environment',
														name:'environment',
														store:me.enviromentStore,
														displayField:'name',
														valueField:'value',
														emptyText:'Select',
														queryMode:'local',
														editable:false
													},
													{
														xtype:'combobox',
														fieldLabel:'Clinic Size',
														name:'clinic_size',
														store:me.sizeStore,
														displayField:'name',
														valueField:'value',
														emptyText:'Select',
														queryMode:'local',
														editable:false
													},
													{
														xtype:'textfield',
														fieldLabel:'Current EMR if any',
														name:'current_emr'
													},
													{
														xtype:'checkbox',
														fieldLabel:'First Time Using GaiaEHR?',
														name:'first_time',
														inputValue:'1'
													},
													{
														xtype:'fieldcontainer',
														fieldLabel:'Rate the installation',
														defaultType:'radiofield',
														width:null,
														defaults:{
															flex:1
														},
														layout:'hbox',
														items:[

															{
																boxLabel:'Very Easy',
																name:'installation_rate',
																inputValue:'Easy'
															},
															{
																boxLabel:'Easy',
																name:'installation_rate',
																inputValue:'Easy'
															},
															{
																boxLabel:'Ok',
																name:'installation_rate',
																inputValue:'Ok'
															},
															{
																boxLabel:'Hard',
																name:'installation_rate',
																inputValue:'Hard'
															},
															{
																boxLabel:'Very Hard',
																name:'installation_rate',
																inputValue:'Very Hard'
															}
														]
													}
												]
											}),
											me.thanks = Ext.create('Ext.container.Container', {
												styleHtmlContent:true,
												padding:'0 10',
												height:0,
												width:0,
												y:32,
												x:20,
												cls:'thanks-box',
												hidden:true
											})
										]
									})
								]
							})
						]
					})
			];

			me.buttons = [
				{
					text:'Back',
					scope:me,
					hidden:true,
					id:'move-prev',
					handler:me.onStepBack
				},
				'->',
				{
					text:'Next',
					scope:me,
					disabled:true,
					action:'next',
					id:'move-next',
					handler:me.onNexStep
				}
			];

			me.installationDetails.update({});

			me.callParent();
		},

		/*
		 * Event: onDbTestCredentials
		 */
		onDbTestCredentials:function(){
			var me = this, form = me.databaseConfiguration.getForm(), success, dbInfo;
			if(typeof form.getValues().dbName !== 'undefined'){
				if(form.isValid()){
					me.databaseConfiguration.el.mask('Validating Database Info');
					SiteSetup.checkDatabaseCredentials(form.getValues(), function(provider, response){
						success = response.result.success;
						dbInfo = response.result.dbInfo;
						me.step[2] = { success:success, dbInfo:dbInfo };
						me.okToGoNext(success);
						me.databaseConfiguration.el.unmask();
						me.isReadyForInstall();
						if(!success) Ext.Msg.show(
							{
								title:'Oops!',
								msg:response.result.error,
								buttons:Ext.Msg.Ok,
								icon:Ext.Msg.ERROR
							});
					});
				}
			}
			else{
				Ext.Msg.show(
					{
						title:'Oops!',
						msg:'Please select one of the two options.',
						buttons:Ext.Msg.Ok,
						icon:Ext.Msg.ERROR
					});
			}
		},

		/*
		 * Event: onInstall
		 */
		onInstall:function(){
			var me = this,
				panel = me.siteConfiguration,
				form = panel.getForm(),
				values = Ext.Object.merge(form.getValues(), me.step[2].dbInfo),
				codeFields = me.query('checkboxfield[action="code"]'),
				codes = [];

			me.installationPregress.show();
			me.siteConfigurationContainer.el.mask('Installing New Site');
			me.installationPregress.updateProgress(0, 'Creating Directory and Sub Directories');
			SiteSetup.setSiteDirBySiteId(values.siteId, function(provider, response){
				if(response.result.success){

					me.installationPregress.updateProgress(.1, 'Creating Database Structure and Tables', true);
					SiteSetup.createDatabaseStructure(values, function(provider, response){
						if(response.result.success){

							me.installationPregress.updateProgress(.2, 'Dumping Data Into Database', true);
							SiteSetup.loadDatabaseData(values, function(provider, response){
								if(response.result.success){

									me.installationPregress.updateProgress(.4, 'Creating Configuration File', true);
									SiteSetup.createSConfigurationFile(values, function(provider, response){
										if(response.result.success){
											values['AESkey'] = response.result.AESkey;
											me.installationPregress.updateProgress(.6, 'Creating Administrator User', true);
											SiteSetup.createSiteAdmin(values, function(provider, response){
												if(response.result.success){

													for(var i = 0; i < codeFields.length; i++){
														if(codeFields[i].getValue()) codes.push(codeFields[i].name);
													}
													me.installProgress = .5;
													me.loadCodes(codes, function(){
														me.installationPregress.updateProgress(1, 'Done!', true);
														me.siteConfigurationContainer.el.unmask();

														me.step[3] = { success:true };
														me.okToGoNext(true);
														values.siteURL = values.siteId != 'default' ? document.URL + '?site=' + values.siteId : document.URL;

														me.onComplete(values)
													});
												}
											});
										}
									});
								}
							});
						}
					});

				}
			});
		},

		/*
		 * Event: loadCodes
		 */
		loadCodes:function(codes, callback){
			var me = this;
			say(codes[0]);
			if(codes[0]){
				me.installationPregress.updateProgress(me.installProgress + .1, 'Loading ' + codes[0] + ' Data', true);
				SiteSetup.loadCode(codes[0], function(provider, response){
					codes.shift();
					me.loadCodes(codes, callback());
				});
			}
			else{
				callback();
			}
		},

		/*
		 * Event: onSurveySubmit
		 */
		onSurveySubmit:function(){
			var me = this,
				succesMsg,
				failureMsg,
				form = me.survey.getForm(),
				values = form.getValues();
			Ext.data.JsonP.request(
				{
					url:'http://gaiaehr.org/survey.php',
					params:values,
					callback:function(data, success){
						succesMsg = '<h3>Sweet! Data successfully sent :-)</h3>' +
							'<p>Thanks for taking the time, to help us improve GaiaEHR<br>' +
							'Stay in touch through <a href="http://www.gaiaehr.org" target="_blank">www.gaiaehr.org</a><br>' +
							'We look foward to hear form you at our <a href="http://gaiaehr.org/forums/" target="_blank">forums</a></p>';
						failureMsg = '<h3>Oops! Unable to contact GaiaEHR server :-(</h3>' +
							'<p>No worries...<br>' +
							'If you want to help, go to <a href="http://www.gaiaehr.org" target="_blank">www.gaiaehr.org</a> and stay in touch with the community.<br>' +
							'If not, Enjoy GaiaEHR :-)</p>';

						me.surveyFields.removeAll();
						me.thanks.update(success ? succesMsg : failureMsg);
						me.thanks.addCls(success ? 'green-box' : 'red-box');
						me.thanks.show();
						Ext.create('Ext.fx.Anim',
							{
								target:me.thanks,
								duration:1000,
								from:{
									width:0,
									height:0
								},
								to:{
									width:825,
									height:120
								}
							});
						Ext.getCmp('move-next').setVisible(false);
					}
				})
		},

		/*
		 * Event: onComplete
		 */
		onComplete:function(data){
			var me = this,
				btn = Ext.getCmp('move-next');
			btn.action = 'next';
			me.onNexStep(btn);
			btn.action = 'complete';
			btn.setText('Send');
			btn.setVisible(true);
			btn.setDisabled(false);
			Ext.getCmp('move-prev').setVisible(false);
			me.headerPanel.getComponent(4).setIconCls('icoGreenFace');
			me.installationDetails.update(data);
		},

		/*
		 * Event: onNexStep
		 */
		onNexStep:function(btn){
			if(btn.action == 'install'){
				this.onInstall();
			}
			else if(btn.action == 'complete'){
				this.onSurveySubmit();
			}
			else{
				this.navigate(this.mainPanel, 'next');
			}
		},

		/*
		 * Event: onStepBack
		 */
		onStepBack:function(){
			this.navigate(this.mainPanel, 'prev');
		},

		/*
		 * Event: navigate
		 */
		navigate:function(panel, to){
			var me = this,
				layout = panel.getLayout(),
				currCard;
			if(typeof to == 'string'){
				layout[to]();
			}
			else{
				layout.setActiveItem(to);
			}
			currCard = layout.getActiveItem();
			me.headerPanel.getComponent(currCard.action).toggle(true);

			if(me.step[currCard.action]){
				Ext.getCmp('move-next').setDisabled(false);
			}
			else{
				Ext.getCmp('move-next').setDisabled(true);
			}

			Ext.getCmp('move-prev').setVisible(layout.getPrev());
			me.isReadyForInstall();
		},

		/*
		 * Event: loadRequirements
		 */
		loadRequirements:function(){
			var me = this;
			me.requirementsStore.load(
				{
					scope:me,
					callback:me.onRequirementsStoreLoad
				});
		},

		/*
		 * Event: licenceChecked
		 */
		licenceChecked:function(checkbox, checked){
			var me = this;
			me.step[0] = { success:checked };
			me.okToGoNext(checked);
			me.isReadyForInstall();
		},

		/*
		 * Event: onRequirementsStoreLoad
		 */
		onRequirementsStoreLoad:function(records){
			var me = this, errorCount = 0;
			for(var i = 0; i < records.length; i++){
				if(records[i].data.status != 'Ok') errorCount++;
			}
			me.step[1] = { success:errorCount === 0 };
			me.okToGoNext(me.step[1].success);
			me.isReadyForInstall();
		},

		/*
		 * Event: onHeaderBtnPress
		 */
		onHeaderBtnPress:function(btn, pressed){
			if(pressed){
				this.navigate(this.mainPanel, btn.action);
			}
		},

		/*
		 * Event: isReadyForInstall
		 */
		isReadyForInstall:function(){
			var me = this,
				btn = Ext.getCmp('move-next'),
				onSiteCofPanel = me.mainPanel.getLayout().getActiveItem().action == 3;

			if(me.checkForError() || !onSiteCofPanel){
				btn.setText('Next');
				btn.action = 'next';
				if(me.mainPanel.getLayout().getActiveItem().action == 3) btn.setDisabled(true);
			}
			else{
				btn.setText('Install');
				btn.action = 'install';
				btn.setDisabled(false);
			}
		},

		/*
		 * Event: checkForError
		 */
		checkForError:function(){
			var me = this, form = me.siteConfiguration.getForm(), error = 0;
			for(var i = 0; i < me.step.length; i++){
				if(!me.step[i].success) error++;
			}
			if(error == 0 && i == 3){
				if(form.isValid()){
					me.headerPanel.getComponent(3).setIconCls('icoGreenFace');
					return false;
				}
				else{
					me.headerPanel.getComponent(3).setIconCls('icoRedFace');
					return true;
				}
			}
			else{
				return true
			}
		},

		/*
		 * Event: okToGoNext
		 */
		okToGoNext:function(ok){
			var me = this, layout = me.mainPanel.getLayout();
			me.headerPanel.getComponent(layout.getActiveItem().action).setIconCls(ok ? 'icoGreenFace' : 'icoRedFace');
			if(layout.getNext()) me.headerPanel.getComponent(layout.getNext().action).setDisabled(!ok);
			if(me.mainPanel.getLayout().getActiveItem().action != 3) Ext.getCmp('move-next').setDisabled(!ok);
		},

		/*
		 * Event: onSiteConfigurationShow
		 */
		onSiteConfigurationShow:function(){
			this.siteConfigurationContainer.setVisible(true);
		},

		/*
		 * Event: statusRenderer
		 */
		statusRenderer:function(val){
			if(val == 'Ok'){
				return '<span style="color:green;">' + val + '</span>';
			}
			else{
				return '<span style="color:red;">' + val + '</span>';
			}
		}

	});

