/**
 * Users.ejs.php
 * Description: Users Screen
 * v0.0.4
 *
 * Author: Ernesto J Rodriguez
 * Modified: n/a
 *
 * GaiaEHR (Eletronic Health Records) 2011
 *
 * @namespace Globals.getGlobals
 * @namespace Globals.updateGlobals
 *
 */
Ext.define('App.view.administration.Globals', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelGlobals',
	pageTitle    : 'GaiaEHR Global Settings',
	uses         : [ 'App.classes.form.fields.Checkbox' ],
	initComponent: function() {
		var me = this;
		// *************************************************************************************
		// Global Model and Data store
		// *************************************************************************************
		Ext.define('GlobalSettingsModel', {
			extend: 'Ext.data.Model',
			fields: [
				{ name: 'fullname', type: 'auto' },
				{ name: 'default_top_pane', type: 'auto' },
				{ name: 'concurrent_layout', type: 'auto' },
				{ name: 'css_header', type: 'auto' },
				{ name: 'gbl_nav_area_width', type: 'auto' },
				{ name: 'GaiaEHR_name', type: 'auto' },
				{ name: 'full_new_patient_form', type: 'auto' },
				{ name: 'patient_search_results_style', type: 'auto' },
				{ name: 'simplified_demographics', type: 'auto' },
				{ name: 'simplified_prescriptions', type: 'auto' },
				{ name: 'simplified_copay', type: 'auto' },
				{ name: 'use_charges_panel', type: 'auto' },
				{ name: 'online_support_link', type: 'auto' },
				{ name: 'language_default', type: 'auto' },
				{ name: 'language_menu_showall', type: 'auto' },
				{ name: 'translate_layout', type: 'auto' },
				{ name: 'translate_lists', type: 'auto' },
				{ name: 'translate_gacl_groups', type: 'auto' },
				{ name: 'translate_form_titles', type: 'auto' },
				{ name: 'translate_document_categories', type: 'auto' },
				{ name: 'translate_appt_categories', type: 'auto' },
				{ name: 'units_of_measurement', type: 'auto' },
				{ name: 'disable_deprecated_metrics_form', type: 'auto' },
				{ name: 'phone_country_code', type: 'auto' },
				{ name: 'date_display_format', type: 'auto' },
				{ name: 'currency_decimals', type: 'auto' },
				{ name: 'currency_dec_point', type: 'auto' },
				{ name: 'currency_thousands_sep', type: 'auto' },
				{ name: 'gbl_currency_symbol', type: 'auto' },
				{ name: 'specific_application', type: 'auto' },
				{ name: 'inhouse_pharmacy', type: 'auto' },
				{ name: 'disable_chart_tracker', type: 'auto' },
				{ name: 'disable_phpmyadmin_link', type: 'auto' },
				{ name: 'disable_immunizations', type: 'auto' },
				{ name: 'disable_prescriptions', type: 'auto' },
				{ name: 'omit_employers', type: 'auto' },
				{ name: 'select_multi_providers', type: 'auto' },
				{ name: 'disable_non_default_groups', type: 'auto' },
				{ name: 'ignore_pnotes_authorization', type: 'auto' },
				{ name: 'support_encounter_claims', type: 'auto' },
				{ name: 'advance_directives_warning', type: 'auto' },
				{ name: 'configuration_import_export', type: 'auto' },
				{ name: 'restrict_user_facility', type: 'auto' },
				{ name: 'set_facility_cookie', type: 'auto' },
				{ name: 'discount_by_money', type: 'auto' },
				{ name: 'gbl_visit_referral_source', type: 'auto' },
				{ name: 'gbl_mask_patient_id', type: 'auto' },
				{ name: 'gbl_mask_invoice_number', type: 'auto' },
				{ name: 'gbl_mask_product_id', type: 'auto' },
				{ name: 'force_billing_widget_open', type: 'auto' },
				{ name: 'activate_ccr_ccd_report', type: 'auto' },
				{ name: 'disable_calendar', type: 'auto' },
				{ name: 'schedule_start', type: 'auto' },
				{ name: 'schedule_end', type: 'auto' },
				{ name: 'calendar_interval', type: 'auto' },
				{ name: 'calendar_appt_style', type: 'auto' },
				{ name: 'docs_see_entire_calendar', type: 'auto' },
				{ name: 'auto_create_new_encounters', type: 'auto' },
				{ name: 'timeout', type: 'auto' },
				{ name: 'secure_password', type: 'auto' },
				{ name: 'password_history', type: 'auto' },
				{ name: 'password_expiration_days', type: 'auto' },
				{ name: 'password_grace_time', type: 'auto' },
				{ name: 'is_client_ssl_enabled', type: 'auto' },
				{ name: 'certificate_authority_crt', type: 'auto' },
				{ name: 'certificate_authority_key', type: 'auto' },
				{ name: 'client_certificate_valid_in_days', type: 'auto' },
				{ name: 'Emergency_Login_email_id', type: 'auto' },
				{ name: 'practice_return_email_path', type: 'auto' },
				{ name: 'EMAIL_METHOD', type: 'auto' },
				{ name: 'SMTP_HOST', type: 'auto' },
				{ name: 'SMTP_PORT', type: 'auto' },
				{ name: 'SMTP_USER', type: 'auto' },
				{ name: 'SMTP_PASS', type: 'auto' },
				{ name: 'EMAIL_NOTIFICATION_HOUR', type: 'auto' },
				{ name: 'SMS_NOTIFICATION_HOUR', type: 'auto' },
				{ name: 'SMS_GATEWAY_USENAME', type: 'auto' },
				{ name: 'SMS_GATEWAY_PASSWORD', type: 'auto' },
				{ name: 'SMS_GATEWAY_APIKEY', type: 'auto' },
				{ name: 'enable_auditlog', type: 'auto' },
				{ name: 'audit_events_patient-record', type: 'auto' },
				{ name: 'audit_events_scheduling', type: 'auto' },
				{ name: 'audit_events_order', type: 'auto' },
				{ name: 'audit_events_security-administration', type: 'auto' },
				{ name: 'audit_events_backup', type: 'auto' },
				{ name: 'audit_events_other', type: 'auto' },
				{ name: 'audit_events_query', type: 'auto' },
				{ name: 'enable_atna_audit', type: 'auto' },
				{ name: 'atna_audit_host', type: 'auto' },
				{ name: 'atna_audit_port', type: 'auto' },
				{ name: 'atna_audit_localcert', type: 'auto' },
				{ name: 'atna_audit_cacert', type: 'auto' },
				{ name: 'mysql_bin_dir', type: 'auto' },
				{ name: 'perl_bin_dir', type: 'auto' },
				{ name: 'temporary_files_dir', type: 'auto' },
				{ name: 'backup_log_dir', type: 'auto' },
				{ name: 'state_data_type', type: 'auto' },
				{ name: 'state_list', type: 'auto' },
				{ name: 'state_custom_addlist_widget', type: 'auto' },
				{ name: 'country_data_type', type: 'auto' },
				{ name: 'country_list', type: 'auto' },
				{ name: 'print_command', type: 'auto' },
				{ name: 'default_chief_complaint', type: 'auto' },
				{ name: 'default_new_encounter_form', type: 'auto' },
				{ name: 'patient_id_category_name', type: 'auto' },
				{ name: 'patient_photo_category_name', type: 'auto' },
				{ name: 'MedicareReferrerIsRenderer', type: 'auto' },
				{ name: 'post_to_date_benchmark', type: 'auto' },
				{ name: 'enable_hylafax', type: 'auto' },
				{ name: 'hylafax_server', type: 'auto' },
				{ name: 'hylafax_basedir', type: 'auto' },
				{ name: 'hylafax_enscript', type: 'auto' },
				{ name: 'enable_scanner', type: 'auto' },
				{ name: 'scanner_output_directory', type: 'auto' }
			]
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'GlobalSettingsModel',
			proxy   : {
				type: 'direct',
				api : {
					read: Globals.getGlobals
				}
			},
			autoLoad: false
		});

		//------------------------------------------------------------------------------
		// When the data is loaded semd values to de form
		//------------------------------------------------------------------------------
		me.store.on('load', function() {
			var rec = me.store.getAt(0); // get the record from the store
			me.globalFormPanel.getForm().loadRecord(rec);
		});
		// *************************************************************************************
		// DataStores for all static combos
		// *************************************************************************************
		me.default_top_pane_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Dashboard", "option_id": "app/dashboard/dashboard.ejs.php"},
				{"title": "Calendar", "option_id": "app/calendar/calendar.ejs.php"},
				{"title": "Messages", "option_id": "app/messages/messages.ejs.php"}
			]
		});
		me.fullname_store = Ext.create('Ext.data.Store', {
			fields: ['format', 'option_id'],
			data  : [
				{"format": "Last, First Middle", "option_id": "0"},
				{"format": "First Middle Last", "option_id": "1"}
			]
		});
		me.concurrent_layout_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Main Navigation Menu (left)", "option_id": "west"},
				{"title": "Main Navigation Menu (right)", "option_id": "east"}
			]
		});
		me.css_header_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Grey (default)", "option_id": "ext-all-gray.css"},
				{"title": "Blue", "option_id": "ext-all.css"},
				{"title": "Access", "option_id": "ext-all-access.css"}
			]
		});
		me.full_new_patient_form_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Old-style static form without search or duplication check", "option_id": "0"},
				{"title": "All demographics fields, with search and duplication check", "option_id": "1"},
				{"title": "Mandatory or specified fields only, search and dup check", "option_id": "2"},
				{"title": "Mandatory or specified fields only, dup check, no search", "option_id": "3"}
			]
		});
		me.patient_search_results_style_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Encounter statistics", "option_id": "0"},
				{"title": "Mandatory and specified fields", "option_id": "1"}
			]
		});
		me.units_of_measurement_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Show both US and metric (main unit is US)", "option_id": "1"},
				{"title": "Show both US and metric (main unit is metric)", "option_id": "2"},
				{"title": "Show US only", "option_id": "3"},
				{"title": "Show metric only", "option_id": "4"}
			]
		});
		me.date_display_format_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "YYYY-MM-DD", "option_id": "0"},
				{"title": "MM/DD/YYYY", "option_id": "1"},
				{"title": "DD/MM/YYYY", "option_id": "2"}
			]
		});
		me.time_display_format_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "24 hr", "option_id": "0"},
				{"title": "12 hr", "option_id": "1"}
			]
		});
		me.currency_decimals_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "0", "option_id": "0"},
				{"title": "1", "option_id": "1"},
				{"title": "2", "option_id": "2"}
			]
		});
		me.currency_dec_point_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Comma", "option_id": ","},
				{"title": "Period", "option_id": "."}
			]
		});
		me.currency_thousands_sep_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Comma", "option_id": ","},
				{"title": "Period", "option_id": "."},
				{"title": "Space", "option_id": " "},
				{"title": "None", "option_id": ""}
			]
		});
		me.EMAIL_METHOD_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "PHPMAIL", "option_id": "PHPMAIL"},
				{"title": "SENDMAIL", "option_id": "SENDMAIL"},
				{"title": "SMTP", "option_id": "SMTP"}
			]
		});
		me.state_country_data_type_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data  : [
				{"title": "Text field", "option_id": "2"},
				{"title": "Single-selection list", "option_id": "1"},
				{"title": "Single-selection list with ability to add to the list", "option_id": "26"}
			]
		});
		//**************************************************************************
		// Dummy Store
		//**************************************************************************
		me.dummyStore = new Ext.data.ArrayStore({
			fields: ['title', 'option_id'],
			data  : [
				['Option 1', 'Option 1'],
				['Option 2', 'Option 2'],
				['Option 3', 'Option 3'],
				['Option 5', 'Option 5'],
				['Option 6', 'Option 6'],
				['Option 7', 'Option 7']
			]
		});
		//**************************************************************************
		// Global Form Panel
		//**************************************************************************
		me.globalFormPanel = Ext.create('App.classes.form.FormPanel', {
			layout       : 'fit',
			autoScroll   : true,
			bodyStyle    : 'padding: 0;',
			fieldDefaults: { msgTarget: 'side', labelWidth: 220, width: 520 },
			defaults     : { anchor: '100%' },
			items        : [
				{
					xtype      : 'tabpanel',
					activeTab  : 0,
					defaults   : {bodyStyle: 'padding:10px', autoScroll: true },
					items      : [
						{
							title   : 'Appearance',
							defaults: {anchor: '100%'},
							items   : [
								{
									xtype       : 'combo',
									fieldLabel  : 'Main Top Pane Screen',
									name        : 'default_top_pane',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.default_top_pane_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Layout Style',
									name        : 'concurrent_layout',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.concurrent_layout_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Theme',
									name        : 'css_header',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.css_header_store
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'Navigation Area Width',
									name      : 'gbl_nav_area_width'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'Application Title',
									name      : 'GaiaEHR_name'
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'New Patient Form',
									name        : 'full_new_patient_form',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.full_new_patient_form_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Patient Search Resuls Style',
									name        : 'patient_search_results_style',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.patient_search_results_style_store
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Simplified Demographics',
									name      : 'simplified_demographics'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Simplified Prescriptions',
									name      : 'simplified_prescriptions'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Simplified Co-Pay',
									name      : 'simplified_copay'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'User Charges Panel',
									name      : 'use_charges_panel'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'Online Support Link',
									name      : 'online_support_link'
								}
							]
						},
						{
							title      : 'Locale',
							//defaults: {},
							defaultType: 'textfield',
							items      : [
								{
									xtype       : 'combo',
									fieldLabel  : 'Fullname Format',
									name        : 'fullname',
									displayField: 'format',
									valueField  : 'option_id',
									editable    : false,
									store       : me.fullname_store
								},
								{
									xtype     : 'languagescombo',
									fieldLabel: 'Default Language',
									name      : 'language_default'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'All Language Allowed',
									name      : 'language_menu_showall'
								},
								{
									xtype      : 'languagescombo',
									fieldLabel : 'Allowed Languages -??-',
									name       : 'lang_description2', // ???????
									multiSelect: true
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Allow Debuging Language -??-',
									name      : 'Loc4'  // ???????
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Translate Layouts',
									name      : 'translate_layout'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Translate List',
									name      : 'translate_lists'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Translate Access Control Roles',
									name      : 'translate_gacl_groups'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Translate Patient Note Titles',
									name      : 'translate_form_titles'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Translate Documents Categoies',
									name      : 'translate_document_categories',
									id        : 'translate_document_categories'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Translate Appointment Categories',
									name      : 'translate_appt_categories'
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Units for Visits Forms',
									name        : 'units_of_measurement',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.units_of_measurement_store
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Disable Old Metric Vitals Form',
									name      : 'disable_deprecated_metrics_form'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'Telephone Country Code',
									name      : 'phone_country_code'
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Date Display Format',
									name        : 'date_display_format',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.date_display_format_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Time Display Format -??-',
									name        : 'date_display_format', // ??????
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.time_display_format_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Currency Decimal Places',
									name        : 'currency_decimals',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.currency_decimals_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Currency Decimal Point Symbol',
									name        : 'currency_dec_point',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.currency_dec_point_store
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Currency Thousands Separator',
									name        : 'currency_thousands_sep',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.currency_thousands_sep_store
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'Currency Designator',
									name      : 'gbl_currency_symbol'
								}
							]
						},
						{
							title      : 'Features',
							defaultType: 'mitos.checkbox',
							items      : [
								{
									xtype       : 'combo',
									fieldLabel  : 'Specific Application',
									name        : 'date_display_format',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Drugs and Prodructs',
									name        : 'date_display_format',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									fieldLabel: 'Disable Chart Tracker',
									name      : 'date_display_format'
								},
								{
									fieldLabel: 'Disable Immunizations',
									name      : 'disable_immunizations'
								},
								{
									fieldLabel: 'Disable Prescriptions',
									name      : 'disable_prescriptions'
								},
								{
									fieldLabel: 'Omit Employers',
									name      : 'omit_employers'
								},
								{
									fieldLabel: 'Support Multi-Provider Events',
									name      : 'select_multi_providers'
								},
								{
									fieldLabel: 'Disable User Groups',
									name      : 'disable_non_default_groups'
								},
								{
									fieldLabel: 'Skip Authorization of Patient Notes',
									name      : 'ignore_pnotes_authorization'
								},
								{
									fieldLabel: 'Allow Encounters Claims',
									name      : 'support_encounter_claims'
								},
								{
									fieldLabel: 'Advance Directives Warning',
									name      : 'advance_directives_warning'
								},
								{
									fieldLabel: 'Configuration Export/Import',
									name      : 'configuration_import_export'
								},
								{
									fieldLabel: 'Restrict Users to Facilities',
									name      : 'restrict_user_facility'
								},
								{
									fieldLabel: 'Remember Selected Facility',
									name      : 'set_facility_cookie'
								},
								{
									fieldLabel: 'Discounts as monetary Ammounts',
									name      : 'discount_by_money'
								},
								{
									fieldLabel: 'Referral Source for Encounters',
									name      : 'gbl_visit_referral_source'
								},
								{
									fieldLabel: 'Maks for Patients IDs',
									xtype     : 'textfield',
									name      : 'gbl_mask_patient_id'
								},
								{
									fieldLabel: 'Mask of Invoice Numbers',
									xtype     : 'textfield',
									name      : 'gbl_mask_invoice_number'
								},
								{
									fieldLabel: 'Mask for Product IDs',
									xtype     : 'textfield',
									name      : 'gbl_mask_product_id'
								},
								{
									fieldLabel: 'Force Billing Widget Open',
									name      : 'force_billing_widget_open'
								},
								{
									fieldLabel: 'Actiate CCR/CCD Reporting',
									name      : 'activate_ccr_ccd_report'
								},
								{
									fieldLabel: 'Hide Encryption/Decryption Options in Document Managment -??-',
									name      : 'Feat22'   // ?????
								}
							]
						},
						{
							title      : 'Calendar',
							defaultType: 'combo',
							items      : [
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Disable Calendar',
									name      : 'Cal1'
								},
								{
									fieldLabel  : 'Calendar Starting Hour',
									name        : 'Cal2',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									fieldLabel  : 'Calendar Ending Hour',
									name        : 'Cal3',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									fieldLabel  : 'Calendar Interval',
									name        : 'Cal4',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									fieldLabel  : 'Appointment Display Style',
									name        : 'Cal5',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Provider See Entire Calendar',
									name      : 'Cal6'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Auto-Create New Encounters',
									name      : 'Cal7'
								},
								{
									fieldLabel  : 'Appointment/Event Color',
									name        : 'Cal8',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								}
							]
						},
						{
							title      : 'Security',
							defaultType: 'textfield',
							items      : [
								{
									fieldLabel: 'Idle Session Timeout Seconds',
									name      : 'timeout'
								},
								{
									xtype       : 'mitos.checkbox',
									fieldLabel  : 'Require Strong Passwords',
									name        : 'secure_password',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.dummyStore
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Require Unique Passwords',
									name      : 'password_history'
								},
								{
									fieldLabel: 'Defaults Password Expiration Days',
									name      : 'password_expiration_days'
								},
								{
									fieldLabel: 'Password Expiration Grace Period',
									name      : 'password_grace_time'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Enable Clients SSL',
									name      : 'is_client_ssl_enabled'
								},
								{
									fieldLabel: 'Path to CA Certificate File',
									name      : 'certificate_authority_crt'
								},
								{
									fieldLabel: 'Path to CA Key File',
									name      : 'certificate_authority_key'
								},
								{
									fieldLabel: 'Client Certificate Expiration Days',
									name      : 'client_certificate_valid_in_days'
								},
								{
									fieldLabel: 'Emergency Login Email Address',
									name      : 'Emergency_Login_email_id'
								}
							]
						},
						{
							title      : 'Notifications',
							defaultType: 'textfield',
							items      : [
								{
									fieldLabel: 'Notification Email Address',
									name      : 'practice_return_email_path'
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Email Transport Method',
									name        : 'EMAIL_METHOD',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.EMAIL_METHOD_store
								},
								{
									fieldLabel: 'SMPT Server Hostname',
									name      : 'SMTP_HOST'
								},
								{
									fieldLabel: 'SMPT Server Port Number',
									name      : 'SMTP_PORT'
								},
								{
									fieldLabel: 'SMPT User for Authentication',
									name      : 'SMTP_USER'
								},
								{
									fieldLabel: 'SMPT Password for Authentication',
									name      : 'SMTP_PASS'
								},
								{
									fieldLabel: 'Email Notification Hours',
									name      : 'EMAIL_NOTIFICATION_HOUR'
								},
								{
									fieldLabel: 'SMS Notification Hours',
									name      : 'SMS_NOTIFICATION_HOUR'
								},
								{
									fieldLabel: 'SMS Gateway Usarname',
									name      : 'SMS_GATEWAY_USENAME'
								},
								{
									fieldLabel: 'SMS Gateway Password',
									name      : 'SMS_GATEWAY_PASSWORD'
								},
								{
									fieldLabel: 'SMS Gateway API Key',
									name      : 'SMS_GATEWAY_APIKEY'
								}
							]
						},
						{
							title      : 'Logging',
							defaultType: 'mitos.checkbox',
							items      : [
								{
									fieldLabel: 'Enable Audit Logging',
									name      : 'enable_auditlog'
								},
								{
									fieldLabel: 'Audid Logging Patient Record',
									name      : 'audit_events_patient'
								},
								{
									fieldLabel: 'Audid Logging Scheduling',
									name      : 'audit_events_scheduling'
								},
								{
									fieldLabel: 'Audid Logging Order',
									name      : 'audit_events_order'
								},
								{
									fieldLabel: 'Audid Logging Security Administration',
									name      : 'audit_events_security'
								},
								{
									fieldLabel: 'Audid Logging Backups',
									name      : 'audit_events_backup'
								},
								{
									fieldLabel: 'Audid Loging Miscellaeous',
									name      : 'audit_events_other'
								},
								{
									fieldLabel: 'Audid Logging SELECT Query',
									name      : 'audit_events_query'
								},
								{
									fieldLabel: 'Enable ATNA Auditing',
									name      : 'enable_atna_audit'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'ATNA audit host',
									name      : 'atna_audit_host'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'ATNA audit post',
									name      : 'atna_audit_port'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'ATNA audit local certificate',
									name      : 'atna_audit_localcert'
								},
								{
									xtype     : 'textfield',
									fieldLabel: 'ATNA audit CA certificate',
									name      : 'atna_audit_cacert'
								}
							]
						},
						{
							title      : 'Miscellaneous',
							defaultType: 'textfield',
							items      : [
								{
									fieldLabel: 'Path to MySQL Binaries',
									name      : 'mysql_bin_dir'
								},
								{
									fieldLabel: 'Path to Perl Binaries',
									name      : 'perl_bin_dir'
								},
								{
									fieldLabel: 'Path to Temporary Files',
									name      : 'temporary_files_dir'
								},
								{
									fieldLabel: 'Path for Event Log Backup',
									name      : 'backup_log_dir'
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'State Data Type',
									name        : 'state_data_type',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.state_country_data_type_store
								},
								{
									fieldLabel: 'State Lsit',
									name      : 'state_list'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'State List Widget Custom Fields',
									name      : 'state_custom_addlist_widget'
								},
								{
									xtype       : 'combo',
									fieldLabel  : 'Country Data Type',
									name        : 'country_data_type',
									displayField: 'title',
									valueField  : 'option_id',
									editable    : false,
									store       : me.state_country_data_type_store
								},
								{
									fieldLabel: 'Country list',
									name      : 'country_list'
								},
								{
									fieldLabel: 'Print Command',
									name      : 'print_command'
								},
								{
									fieldLabel: 'Default Reason for Visit',
									name      : 'default_chief_complaint'
								},
								{
									fieldLabel: 'Default Encounter Form ID',
									name      : 'default_new_encounter_form'
								},
								{
									fieldLabel: 'patient ID Category Name',
									name      : 'patient_id_category_name'
								},
								{
									fieldLabel: 'patient Photo Category name',
									name      : 'patient_photo_category_name'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Medicare Referrer is Renderer',
									name      : 'MedicareReferrerIsRenderer'
								},
								{
									fieldLabel: 'Final Close Date (yyy-mm-dd)',
									name      : 'post_to_date_benchmark'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Enable Hylafax Support',
									name      : 'enable_hylafax'
								},
								{
									fieldLabel: 'Halafax Server',
									name      : 'hylafax_server'
								},
								{
									fieldLabel: 'Halafax Directory',
									name      : 'hylafax_basedir'
								},
								{
									fieldLabel: 'Halafax Enscript Command',
									name      : 'hylafax_enscript'
								},
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Enable Scanner Support',
									name      : 'enable_scanner'
								},
								{
									fieldLabel: 'Scanner Directory',
									name      : 'scanner_output_directory'
								}
							]
						},
						{
							title      : 'Connectors',
							defaultType: 'textfield',
							items      : [
								{
									xtype     : 'mitos.checkbox',
									fieldLabel: 'Enable Lab Exchange',
									name      : 'Conn1'
								},
								{
									fieldLabel: 'Lab Exchange Site ID',
									name      : 'Conn2'
								},
								{
									fieldLabel: 'Lab Exchange Token ID',
									name      : 'Conn3'
								},
								{
									fieldLabel: 'Lab Exchange Site Address',
									name      : 'Conn4'
								}
							]
						}
					],
					dockedItems: [
						{
							xtype: 'toolbar',
							dock : 'top',
							items: [
								{
									text   : 'Save Configuration',
									iconCls: 'save',
									handler: function() {
										var form = me.globalFormPanel.getForm();
										me.onSave(form, me.store);
									}
								}
							]
						}
					]
				}
			]
		});
		me.pageBody = [ me.globalFormPanel ];
		me.callParent(arguments);
	}, // end of initComponent
	onSave       : function(form, store) {
		var record = form.getRecord(),
			values = form.getValues();
		Globals.updateGlobals(values, function() {
			store.load();
		});

		this.msg('New Global Configuration Saved', 'For some settings to take place you will have to refresh the application.');
	},
	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive     : function(callback) {
		this.store.load();
		callback(true);
	}
}); //ens LogPage class