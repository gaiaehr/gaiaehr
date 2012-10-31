 /*
 GaiaEHR (Electronic Health Records)
 Globals.js
 Gloabl Settings
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
Ext.define('App.view.administration.Globals',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelGlobals',
	pageTitle : 'GaiaEHR ' + i18n('global_settings'),
	uses : ['App.ux.form.fields.Checkbox'],
	initComponent : function()
	{
		var me = this;
		// *************************************************************************************
		// Global Model and Data store
		// *************************************************************************************
		Ext.define('GlobalSettingsModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'fullname',
				type : 'string'
			},
			{
				name : 'default_top_pane',
				type : 'string'
			},
			{
				name : 'main_navigation_menu_left',
				type : 'string'
			},
			{
				name : 'css_header',
				type : 'string'
			},
			{
				name : 'gbl_nav_area_width',
				type : 'int'
			},
			{
				name : 'GaiaEHR_name',
				type : 'string'
			},
			{
				name : 'full_new_patient_form',
				type : 'string'
			},
			{
				name : 'online_support_link',
				type : 'string'
			},
			{
				name : 'language_default',
				type : 'string'
			},
			{
				name : 'units_of_measurement',
				type : 'string'
			},
			{
				name : 'disable_deprecated_metrics_form',
				type : 'auto'
			},
			{
				name : 'phone_country_code',
				type : 'string'
			},
			{
				name : 'date_display_format',
				type : 'string'
			},
			{
				name : 'time_display_format',
				type : 'string'
			},
			{
				name : 'currency_decimals',
				type : 'string'
			},
			{
				name : 'currency_dec_point',
				type : 'auto'
			},
			{
				name : 'currency_thousands_sep',
				type : 'auto'
			},
			{
				name : 'gbl_currency_symbol',
				type : 'auto'
			},
			{
				name : 'specific_application',
				type : 'auto'
			},
			{
				name : 'inhouse_pharmacy',
				type : 'auto'
			},
			{
				name : 'disable_chart_tracker',
				type : 'auto'
			},
			{
				name : 'disable_phpmyadmin_link',
				type : 'auto'
			},
			{
				name : 'disable_immunizations',
				type : 'auto'
			},
			{
				name : 'disable_prescriptions',
				type : 'auto'
			},
			{
				name : 'omit_employers',
				type : 'auto'
			},
			{
				name : 'select_multi_providers',
				type : 'auto'
			},
			{
				name : 'disable_non_default_groups',
				type : 'auto'
			},
			{
				name : 'ignore_pnotes_authorization',
				type : 'auto'
			},
			{
				name : 'support_encounter_claims',
				type : 'auto'
			},
			{
				name : 'advance_directives_warning',
				type : 'auto'
			},
			{
				name : 'configuration_import_export',
				type : 'auto'
			},
			{
				name : 'restrict_user_facility',
				type : 'auto'
			},
			{
				name : 'set_facility_cookie',
				type : 'auto'
			},
			{
				name : 'discount_by_money',
				type : 'auto'
			},
			{
				name : 'gbl_visit_referral_source',
				type : 'auto'
			},
			{
				name : 'gbl_mask_patient_id',
				type : 'auto'
			},
			{
				name : 'gbl_mask_invoice_number',
				type : 'auto'
			},
			{
				name : 'gbl_mask_product_id',
				type : 'auto'
			},
			{
				name : 'force_billing_widget_open',
				type : 'auto'
			},
			{
				name : 'activate_ccr_ccd_report',
				type : 'auto'
			},
			{
				name : 'disable_calendar',
				type : 'auto'
			},
			{
				name : 'schedule_start',
				type : 'auto'
			},
			{
				name : 'schedule_end',
				type : 'auto'
			},
			{
				name : 'calendar_interval',
				type : 'auto'
			},
			{
				name : 'calendar_appt_style',
				type : 'auto'
			},
			{
				name : 'docs_see_entire_calendar',
				type : 'auto'
			},
			{
				name : 'auto_create_new_encounters',
				type : 'auto'
			},
			{
				name : 'timeout',
				type : 'auto'
			},
			{
				name : 'secure_password',
				type : 'auto'
			},
			{
				name : 'password_history',
				type : 'auto'
			},
			{
				name : 'password_expiration_days',
				type : 'auto'
			},
			{
				name : 'password_grace_time',
				type : 'auto'
			},
			{
				name : 'is_client_ssl_enabled',
				type : 'auto'
			},
			{
				name : 'certificate_authority_crt',
				type : 'auto'
			},
			{
				name : 'certificate_authority_key',
				type : 'auto'
			},
			{
				name : 'client_certificate_valid_in_days',
				type : 'auto'
			},
			{
				name : 'Emergency_Login_email_id',
				type : 'auto'
			},
			{
				name : 'practice_return_email_path',
				type : 'auto'
			},
			{
				name : 'EMAIL_METHOD',
				type : 'auto'
			},
			{
				name : 'SMTP_HOST',
				type : 'auto'
			},
			{
				name : 'SMTP_PORT',
				type : 'auto'
			},
			{
				name : 'SMTP_USER',
				type : 'auto'
			},
			{
				name : 'SMTP_PASS',
				type : 'auto'
			},
			{
				name : 'EMAIL_NOTIFICATION_HOUR',
				type : 'auto'
			},
			{
				name : 'SMS_NOTIFICATION_HOUR',
				type : 'auto'
			},
			{
				name : 'SMS_GATEWAY_USENAME',
				type : 'auto'
			},
			{
				name : 'SMS_GATEWAY_PASSWORD',
				type : 'auto'
			},
			{
				name : 'SMS_GATEWAY_APIKEY',
				type : 'auto'
			},
			{
				name : 'enable_auditlog',
				type : 'auto'
			},
			{
				name : 'audit_events_patient-record',
				type : 'auto'
			},
			{
				name : 'audit_events_scheduling',
				type : 'auto'
			},
			{
				name : 'audit_events_order',
				type : 'auto'
			},
			{
				name : 'audit_events_security-administration',
				type : 'auto'
			},
			{
				name : 'audit_events_backup',
				type : 'auto'
			},
			{
				name : 'audit_events_other',
				type : 'auto'
			},
			{
				name : 'audit_events_query',
				type : 'auto'
			},
			{
				name : 'enable_atna_audit',
				type : 'auto'
			},
			{
				name : 'atna_audit_host',
				type : 'auto'
			},
			{
				name : 'atna_audit_port',
				type : 'auto'
			},
			{
				name : 'atna_audit_localcert',
				type : 'auto'
			},
			{
				name : 'atna_audit_cacert',
				type : 'auto'
			},
			{
				name : 'mysql_bin_dir',
				type : 'auto'
			},
			{
				name : 'perl_bin_dir',
				type : 'auto'
			},
			{
				name : 'temporary_files_dir',
				type : 'auto'
			},
			{
				name : 'backup_log_dir',
				type : 'auto'
			},
			{
				name : 'state_data_type',
				type : 'auto'
			},
			{
				name : 'state_list',
				type : 'auto'
			},
			{
				name : 'state_custom_addlist_widget',
				type : 'auto'
			},
			{
				name : 'country_data_type',
				type : 'auto'
			},
			{
				name : 'country_list',
				type : 'auto'
			},
			{
				name : 'print_command',
				type : 'auto'
			},
			{
				name : 'default_chief_complaint',
				type : 'auto'
			},
			{
				name : 'default_new_encounter_form',
				type : 'auto'
			},
			{
				name : 'patient_id_category_name',
				type : 'auto'
			},
			{
				name : 'patient_photo_category_name',
				type : 'auto'
			},
			{
				name : 'MedicareReferrerIsRenderer',
				type : 'auto'
			},
			{
				name : 'post_to_date_benchmark',
				type : 'auto'
			},
			{
				name : 'enable_hylafax',
				type : 'auto'
			},
			{
				name : 'hylafax_server',
				type : 'auto'
			},
			{
				name : 'hylafax_basedir',
				type : 'auto'
			},
			{
				name : 'hylafax_enscript',
				type : 'auto'
			},
			{
				name : 'enable_scanner',
				type : 'auto'
			},
			{
				name : 'scanner_output_directory',
				type : 'auto'
			},
			{
				name : 'autosave',
				type : 'auto'
			}]
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'GlobalSettingsModel',
			proxy :
			{
				type : 'direct',
				api :
				{
					read : Globals.getGlobals
				}
			},
			autoLoad : false
		});

		//------------------------------------------------------------------------------
		// When the data is loaded semd values to de form
		//------------------------------------------------------------------------------
		me.store.on('load', function()
		{
			var rec = me.store.getAt(0);
			// get the record from the store
			me.globalFormPanel.getForm().loadRecord(rec);
		});
		// *************************************************************************************
		// DataStores for all static combos
		// *************************************************************************************
		me.default_top_pane_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('dashboard'),
				"option_id" : "app/dashboard/dashboard.ejs.php"
			},
			{
				"title" : i18n('calendar'),
				"option_id" : "app/calendar/calendar.ejs.php"
			},
			{
				"title" : i18n('messages'),
				"option_id" : "app/messages/messages.ejs.php"
			}]
		});
		me.fullname_store = Ext.create('Ext.data.Store',
		{
			fields : ['format', 'option_id'],
			data : [
			{
				"format" : i18n('last_first_middle'),
				"option_id" : "0"
			},
			{
				"format" : i18n('first_middle_last'),
				"option_id" : "1"
			}]
		});
		me.main_navigation_menu_left_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('main_navigation_menu_left'),
				"option_id" : "west"
			},
			{
				"title" : i18n('main_navigation_menu_right'),
				"option_id" : "east"
			}]
		});
		me.css_header_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('grey_default'),
				"option_id" : "ext-all-gray.css"
			},
			{
				"title" : i18n('blue'),
				"option_id" : "ext-all.css"
			},
			{
				"title" : i18n('access'),
				"option_id" : "ext-all-access.css"
			}]
		});
		me.full_new_patient_form_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('oldstyle_static_form_without_search_or_duplication_check'),
				"option_id" : "0"
			},
			{
				"title" : i18n('all_demographics_fields_with_search_and_duplication_check'),
				"option_id" : "1"
			},
			{
				"title" : i18n('mandatory_or_specified_fields_only_search_and_dup_check'),
				"option_id" : "2"
			},
			{
				"title" : i18n('mandatory_or_specified_fields_only_dup_check_no_search'),
				"option_id" : "3"
			}]
		});
		me.patient_search_results_style_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('encounter_statistics'),
				"option_id" : "0"
			},
			{
				"title" : i18n('mandatory_and_specified_fields'),
				"option_id" : "1"
			}]
		});
		me.units_of_measurement_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('show_both_us_and_metric_main_unit_is_us'),
				"option_id" : "1"
			},
			{
				"title" : i18n('show_both_us_and_metric_main_unit_is_metric'),
				"option_id" : "2"
			},
			{
				"title" : i18n('show_us_only'),
				"option_id" : "3"
			},
			{
				"title" : i18n('show_metric_only'),
				"option_id" : "4"
			}]
		});
		me.date_display_format_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('yyyy_mm_dd'),
				"option_id" : "Y-m-d"
			},
			{
				"title" : i18n('mm_dd_yyyy'),
				"option_id" : "m/d/Y"
			},
			{
				"title" : i18n('dd_mm_yyyy'),
				"option_id" : "d/m/Y"
			}]
		});
		me.time_display_format_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('24_hr'),
				"option_id" : "H:i"
			},
			{
				"title" : i18n['12 hr'],
				"option_id" : "g:i a"
			}]
		});
		me.currency_decimals_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('0'),
				"option_id" : "0"
			},
			{
				"title" : i18n('1'),
				"option_id" : "1"
			},
			{
				"title" : i18n('2'),
				"option_id" : "2"
			}]
		});
		me.currency_dec_point_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('comma'),
				"option_id" : ","
			},
			{
				"title" : i18n('period'),
				"option_id" : "."
			}]
		});
		me.currency_thousands_sep_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('comma'),
				"option_id" : ","
			},
			{
				"title" : i18n('period'),
				"option_id" : "."
			},
			{
				"title" : i18n('space'),
				"option_id" : " "
			},
			{
				"title" : i18n('none'),
				"option_id" : ""
			}]
		});
		me.EMAIL_METHOD_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : "PHPMAIL",
				"option_id" : "PHPMAIL"
			},
			{
				"title" : "SENDMAIL",
				"option_id" : "SENDMAIL"
			},
			{
				"title" : "SMTP",
				"option_id" : "SMTP"
			}]
		});
		me.state_country_data_type_store = Ext.create('Ext.data.Store',
		{
			fields : ['title', 'option_id'],
			data : [
			{
				"title" : i18n('text_field'),
				"option_id" : "2"
			},
			{
				"title" : i18n('single_selection_list'),
				"option_id" : "1"
			},
			{
				"title" : i18n('single_selection_list_with_ability_to_add_to_the_list'),
				"option_id" : "26"
			}]
		});
		//**************************************************************************
		// Dummy Store
		//**************************************************************************
		me.dummyStore = new Ext.data.ArrayStore(
		{
			fields : ['title', 'option_id'],
			data : [[i18n('option_1'), 'Option 1'], [i18n('option_2'), 'Option 2'], [i18n('option_3'), 'Option 3'], [i18n('option_5'), 'Option 5'], [i18n('option_6'), 'Option 6'], [i18n('option_7'), 'Option 7']]
		});
		//**************************************************************************
		// Global Form Panel
		//**************************************************************************
		me.globalFormPanel = Ext.create('App.ux.form.Panel',
		{
			layout : 'fit',
			autoScroll : true,
			bodyStyle : 'padding: 0;',
			fieldDefaults :
			{
				msgTarget : 'side',
				labelWidth : 220,
				width : 520
			},
			defaults :
			{
				anchor : '100%'
			},
			items : [
			{
				xtype : 'tabpanel',
				activeTab : 0,
				defaults :
				{
					bodyStyle : 'padding:10px',
					autoScroll : true
				},
				items : [
				{
					title : i18n('appearance'),
					defaults :
					{
						anchor : '100%'
					},
					items : [
                        {
                            xtype : 'combo',
                            fieldLabel : i18n('main_top_pane_screen'),
                            name : 'default_top_pane',
                            displayField : 'title',
                            valueField : 'option_id',
                            editable : false,
                            store : me.default_top_pane_store
                        },
                        {
                            xtype : 'combo',
                            fieldLabel : i18n('layout_style'),
                            name : 'main_navigation_menu_left',
                            displayField : 'title',
                            valueField : 'option_id',
                            editable : false,
                            store : me.main_navigation_menu_left_store
                        },
                        {
                            xtype : 'combo',
                            fieldLabel : i18n('theme'),
                            name : 'css_header',
                            displayField : 'title',
                            valueField : 'option_id',
                            editable : false,
                            store : me.css_header_store
                        },
                        {
                            xtype : 'textfield',
                            fieldLabel : i18n('navigation_area_width'),
                            name : 'gbl_nav_area_width'
                        }
                    ]
				},
				{
					title : 'Locale',
					defaultType : 'textfield',
					items : [
					{
						xtype : 'combo',
						fieldLabel : i18n('fullname_format'),
						name : 'fullname',
						displayField : 'format',
						valueField : 'option_id',
						editable : false,
						store : me.fullname_store
					},
					{
						xtype : 'languagescombo',
						fieldLabel : i18n('default_language'),
						name : 'language_default'
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('units_for_visits_forms'),
						name : 'units_of_measurement',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.units_of_measurement_store
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('telephone_country_code'),
						name : 'phone_country_code'
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('date_display_format'),
						name : 'date_display_format',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.date_display_format_store
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('time_display_format'),
						name : 'time_display_format',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.time_display_format_store
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('currency_decimal_places'),
						name : 'currency_decimals',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.currency_decimals_store
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('currency_decimal_point_symbol'),
						name : 'currency_dec_point',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.currency_dec_point_store
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('currency_thousands_separator'),
						name : 'currency_thousands_sep',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.currency_thousands_sep_store
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('currency_designator'),
						name : 'gbl_currency_symbol'
					}]
				},
				{
					title : 'Features',
					defaultType : 'mitos.checkbox',
					items : [
                        {
                            fieldLabel : i18n('autosave_forms'),
                            name : 'autosave'
                        },
                        {
                            fieldLabel : i18n('disable_chart_tracker'),
                            name : 'disable_charts'
                        },
                        {
                            fieldLabel : i18n('disable_immunizations'),
                            name : 'disable_immunizations'
                        },
                        {
                            fieldLabel : i18n('disable_prescriptions'),
                            name : 'disable_prescriptions'
                        },
    //					{
    //						fieldLabel : i18n('restrict_users_to_facilities'),
    //						name : 'restrict_user_facility'
    //					},
                        {
                            fieldLabel : i18n('force_billing_widget_open'),
                            name : 'force_billing_widget_open'
                        },
                        {
                            fieldLabel : i18n('actiate_ccr_ccd_reporting'),
                            name : 'activate_ccr_ccd_report'
                        }
                    ]
				},
//				{
//					title : i18n('calendar'),
//					defaultType : 'combo',
//					items : [
//                        {
//                            xtype : 'mitos.checkbox',
//                            fieldLabel : i18n('disable_calendar'),
//                            name : 'Cal1'
//                        },
//                        {
//                            fieldLabel : i18n('calendar_starting_hour'),
//                            name : 'Cal2',
//                            displayField : 'title',
//                            valueField : 'option_id',
//                            editable : false,
//                            store : me.dummyStore
//                        },
//                        {
//                            fieldLabel : i18n('calendar_ending_hour'),
//                            name : 'Cal3',
//                            displayField : 'title',
//                            valueField : 'option_id',
//                            editable : false,
//                            store : me.dummyStore
//                        },
//                        {
//                            fieldLabel : i18n('calendar_interval'),
//                            name : 'Cal4',
//                            displayField : 'title',
//                            valueField : 'option_id',
//                            editable : false,
//                            store : me.dummyStore
//                        },
//                        {
//                            fieldLabel : i18n('appointment_display_style'),
//                            name : 'Cal5',
//                            displayField : 'title',
//                            valueField : 'option_id',
//                            editable : false,
//                            store : me.dummyStore
//                        },
//                        {
//                            xtype : 'mitos.checkbox',
//                            fieldLabel : i18n('provider_see_entire_calendar'),
//                            name : 'Cal6'
//                        },
//                        {
//                            xtype : 'mitos.checkbox',
//                            fieldLabel : i18n('auto_create_new_encounters'),
//                            name : 'Cal7'
//                        },
//                        {
//                            fieldLabel : i18n('appointment_event_color'),
//                            name : 'Cal8',
//                            displayField : 'title',
//                            valueField : 'option_id',
//                            editable : false,
//                            store : me.dummyStore
//                        }
//                    ]
//				},
				{
					title : 'Security',
					defaultType : 'textfield',
					items : [
					{
						fieldLabel : i18n('idle_session_timeout_seconds'),
						name : 'timeout'
					},
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('require_strong_passwords'),
						name : 'secure_password',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.dummyStore
					},
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('require_unique_passwords'),
						name : 'password_history'
					},
					{
						fieldLabel : i18n('defaults_password_expiration_days'),
						name : 'password_expiration_days'
					},
					{
						fieldLabel : i18n('password_expiration_grace_period'),
						name : 'password_grace_time'
					},
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('enable_clients_ssl'),
						name : 'is_client_ssl_enabled'
					},
					{
						fieldLabel : i18n('path_to_ca_certificate_file'),
						name : 'certificate_authority_crt'
					},
					{
						fieldLabel : i18n('path_to_ca_key_file'),
						name : 'certificate_authority_key'
					},
					{
						fieldLabel : i18n('client_certificate_expiration_days'),
						name : 'client_certificate_valid_in_days'
					},
					{
						fieldLabel : i18n('emergency_login_email_address'),
						name : 'Emergency_Login_email_id'
					}]
				},
				{
					title : i18n('notifications'),
					defaultType : 'textfield',
					items : [
					{
						fieldLabel : i18n('notification_email_address'),
						name : 'practice_return_email_path'
					},
					{
						xtype : 'combo',
						fieldLabel : i18n('email_transport_method'),
						name : 'EMAIL_METHOD',
						displayField : 'title',
						valueField : 'option_id',
						editable : false,
						store : me.EMAIL_METHOD_store
					},
					{
						fieldLabel : i18n('smpt_server_hostname'),
						name : 'SMTP_HOST'
					},
					{
						fieldLabel : i18n('smpt_server_port_number'),
						name : 'SMTP_PORT'
					},
					{
						fieldLabel : i18n('smpt_user_for_authentication'),
						name : 'SMTP_USER'
					},
					{
						fieldLabel : i18n('smpt_password_for_authentication'),
						name : 'SMTP_PASS'
					},
					{
						fieldLabel : i18n('email_notification_hours'),
						name : 'EMAIL_NOTIFICATION_HOUR'
					},
					{
						fieldLabel : i18n('sms_notification_hours'),
						name : 'SMS_NOTIFICATION_HOUR'
					},
					{
						fieldLabel : i18n('sms_gateway_usarname'),
						name : 'SMS_GATEWAY_USENAME'
					},
					{
						fieldLabel : i18n('sms_gateway_password'),
						name : 'SMS_GATEWAY_PASSWORD'
					},
					{
						fieldLabel : i18n('sms_gateway_api_Key'),
						name : 'SMS_GATEWAY_APIKEY'
					}]
				},
				{
					title : i18n('logging'),
					defaultType : 'mitos.checkbox',
					items : [
					{
						fieldLabel : i18n('enable_audit_logging'),
						name : 'enable_auditlog'
					},
					{
						fieldLabel : i18n('audit_logging_patient_record'),
						name : 'audit_events_patient'
					},
					{
						fieldLabel : i18n('audid_logging_scheduling'),
						name : 'audit_events_scheduling'
					},
					{
						fieldLabel : i18n('audid_logging_order'),
						name : 'audit_events_order'
					},
					{
						fieldLabel : i18n('audid_logging_security_administration'),
						name : 'audit_events_security'
					},
					{
						fieldLabel : i18n('audid_logging_backups'),
						name : 'audit_events_backup'
					},
					{
						fieldLabel : i18n('audid_logging_miscellaeous'),
						name : 'audit_events_other'
					},
					{
						fieldLabel : i18n('audid_logging_select_query'),
						name : 'audit_events_query'
					},
					{
						fieldLabel : i18n('enable_atna_auditing'),
						name : 'enable_atna_audit'
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('atna_audit_host'),
						name : 'atna_audit_host'
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('atna_audit_post'),
						name : 'atna_audit_port'
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('atna_audit_local_certificate'),
						name : 'atna_audit_localcert'
					},
					{
						xtype : 'textfield',
						fieldLabel : i18n('atna_audit_ca_certificate'),
						name : 'atna_audit_cacert'
					}]
				},
				{
					title : i18n('miscellaneous'),
					defaultType : 'textfield',
					items : [
					{
						fieldLabel : i18n('state_list'),
						name : 'state_list'
					},
					{
						fieldLabel : i18n('country_list'),
						name : 'country_list'
					},
					{
						fieldLabel : i18n('print_command'),
						name : 'print_command'
					},
					{
						fieldLabel : i18n('default_reason_for_visit'),
						name : 'default_chief_complaint'
					},
					{
						fieldLabel : i18n('patient_id_category_name'),
						name : 'patient_id_category_name'
					},
					{
						fieldLabel : i18n('patient_photo_category_name'),
						name : 'patient_photo_category_name'
					},
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('medicare_referrer_is_renderer'),
						name : 'MedicareReferrerIsRenderer'
					},
					{
						fieldLabel : i18n('final_close_date_yyy_mm_dd'),
						name : 'post_to_date_benchmark'
					},
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('enable_hylafax_support'),
						name : 'enable_hylafax'
					},
					{
						fieldLabel : i18n('hylafax_server'),
						name : 'hylafax_server'
					},
					{
						fieldLabel : i18n('hylafax_directory'),
						name : 'hylafax_basedir'
					},
					{
						fieldLabel : i18n('hylafax_enscript_command'),
						name : 'hylafax_enscript'
					},
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('enable_scanner_support'),
						name : 'enable_scanner'
					},
					{
						fieldLabel : i18n('scanner_directory'),
						name : 'scanner_output_directory'
					}]
				},
				{
					title : i18n('connectors'),
					defaultType : 'textfield',
					items : [
					{
						xtype : 'mitos.checkbox',
						fieldLabel : i18n('enable_lab_exchange'),
						name : 'Conn1'
					},
					{
						fieldLabel : i18n('lab_exchange_site_id'),
						name : 'Conn2'
					},
					{
						fieldLabel : i18n('lab_exchange_token_id'),
						name : 'Conn3'
					},
					{
						fieldLabel : i18n('lab_exchange_site_address'),
						name : 'Conn4'
					}]
				}],
				dockedItems : [
				{
					xtype : 'toolbar',
					dock : 'top',
					items : [
					{
						text : i18n('save_configuration'),
						iconCls : 'save',
						handler : function()
						{
							var form = me.globalFormPanel.getForm();
							me.onGloblasSave(form, me.store);
						}
					}]
				}]
			}]
		});
		me.pageBody = [me.globalFormPanel];
		me.callParent(arguments);
	}, // end of initComponent
	onGloblasSave : function(form, store)
	{
		var record = form.getRecord(), values = form.getValues();
		Globals.updateGlobals(values, function()
		{
			store.load();
		});

		this.msg(i18n('new_global_configuration_saved'), i18n('refresh_the_application'));
	},
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.store.load();
		callback(true);
	}
});
//ens LogPage class