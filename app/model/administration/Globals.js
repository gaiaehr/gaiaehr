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

Ext.define('App.model.administration.Globals',
    {
        extend: 'Ext.data.Model',
        table: {
            name:'globals',
            engine:'InnoDB',
            autoIncrement:1,
            charset:'utf8',
            collate:'utf8_bin',
            comment:'Account'
        },
        fields: [
            {
                name: 'fullname',
                type: 'string'
            },
            {
                name: 'default_top_pane',
                type: 'string'
            },
            {
                name: 'main_navigation_menu_left',
                type: 'string'
            },
            {
                name: 'css_header',
                type: 'string'
            },
            {
                name: 'gbl_nav_area_width',
                type: 'int'
            },
            {
                name: 'GaiaEHR_name',
                type: 'string'
            },
            {
                name: 'full_new_patient_form',
                type: 'string'
            },
            {
                name: 'online_support_link',
                type: 'string'
            },
            {
                name: 'language_default',
                type: 'string'
            },
            {
                name: 'units_of_measurement',
                type: 'string'
            },
            {
                name: 'disable_deprecated_metrics_form',
                type: 'auto'
            },
            {
                name: 'phone_country_code',
                type: 'string'
            },
            {
                name: 'date_display_format',
                type: 'string'
            },
            {
                name: 'time_display_format',
                type: 'string'
            },
            {
                name: 'currency_decimals',
                type: 'string'
            },
            {
                name: 'currency_dec_point',
                type: 'auto'
            },
            {
                name: 'currency_thousands_sep',
                type: 'auto'
            },
            {
                name: 'gbl_currency_symbol',
                type: 'auto'
            },
            {
                name: 'specific_application',
                type: 'auto'
            },
            {
                name: 'inhouse_pharmacy',
                type: 'auto'
            },
            {
                name: 'disable_chart_tracker',
                type: 'auto'
            },
            {
                name: 'disable_phpmyadmin_link',
                type: 'auto'
            },
            {
                name: 'disable_immunizations',
                type: 'auto'
            },
            {
                name: 'disable_prescriptions',
                type: 'auto'
            },
            {
                name: 'omit_employers',
                type: 'auto'
            },
            {
                name: 'select_multi_providers',
                type: 'auto'
            },
            {
                name: 'disable_non_default_groups',
                type: 'auto'
            },
            {
                name: 'ignore_pnotes_authorization',
                type: 'auto'
            },
            {
                name: 'support_encounter_claims',
                type: 'auto'
            },
            {
                name: 'advance_directives_warning',
                type: 'auto'
            },
            {
                name: 'configuration_import_export',
                type: 'auto'
            },
            {
                name: 'restrict_user_facility',
                type: 'auto'
            },
            {
                name: 'set_facility_cookie',
                type: 'auto'
            },
            {
                name: 'discount_by_money',
                type: 'auto'
            },
            {
                name: 'gbl_visit_referral_source',
                type: 'auto'
            },
            {
                name: 'gbl_mask_patient_id',
                type: 'auto'
            },
            {
                name: 'gbl_mask_invoice_number',
                type: 'auto'
            },
            {
                name: 'gbl_mask_product_id',
                type: 'auto'
            },
            {
                name: 'force_billing_widget_open',
                type: 'auto'
            },
            {
                name: 'activate_ccr_ccd_report',
                type: 'auto'
            },
            {
                name: 'disable_calendar',
                type: 'auto'
            },
            {
                name: 'schedule_start',
                type: 'auto'
            },
            {
                name: 'schedule_end',
                type: 'auto'
            },
            {
                name: 'calendar_interval',
                type: 'auto'
            },
            {
                name: 'calendar_appt_style',
                type: 'auto'
            },
            {
                name: 'docs_see_entire_calendar',
                type: 'auto'
            },
            {
                name: 'auto_create_new_encounters',
                type: 'auto'
            },
            {
                name: 'timeout',
                type: 'auto'
            },
            {
                name: 'secure_password',
                type: 'auto'
            },
            {
                name: 'password_history',
                type: 'auto'
            },
            {
                name: 'password_expiration_days',
                type: 'auto'
            },
            {
                name: 'password_grace_time',
                type: 'auto'
            },
            {
                name: 'is_client_ssl_enabled',
                type: 'auto'
            },
            {
                name: 'certificate_authority_crt',
                type: 'auto'
            },
            {
                name: 'certificate_authority_key',
                type: 'auto'
            },
            {
                name: 'client_certificate_valid_in_days',
                type: 'auto'
            },
            {
                name: 'Emergency_Login_email_id',
                type: 'auto'
            },
            {
                name: 'practice_return_email_path',
                type: 'auto'
            },
            {
                name: 'EMAIL_METHOD',
                type: 'auto'
            },
            {
                name: 'SMTP_HOST',
                type: 'auto'
            },
            {
                name: 'SMTP_PORT',
                type: 'auto'
            },
            {
                name: 'SMTP_USER',
                type: 'auto'
            },
            {
                name: 'SMTP_PASS',
                type: 'auto'
            },
            {
                name: 'EMAIL_NOTIFICATION_HOUR',
                type: 'auto'
            },
            {
                name: 'SMS_NOTIFICATION_HOUR',
                type: 'auto'
            },
            {
                name: 'SMS_GATEWAY_USENAME',
                type: 'auto'
            },
            {
                name: 'SMS_GATEWAY_PASSWORD',
                type: 'auto'
            },
            {
                name: 'SMS_GATEWAY_APIKEY',
                type: 'auto'
            },
            {
                name: 'enable_auditlog',
                type: 'auto'
            },
            {
                name: 'audit_events_patient-record',
                type: 'auto'
            },
            {
                name: 'audit_events_scheduling',
                type: 'auto'
            },
            {
                name: 'audit_events_order',
                type: 'auto'
            },
            {
                name: 'audit_events_security-administration',
                type: 'auto'
            },
            {
                name: 'audit_events_backup',
                type: 'auto'
            },
            {
                name: 'audit_events_other',
                type: 'auto'
            },
            {
                name: 'audit_events_query',
                type: 'auto'
            },
            {
                name: 'enable_atna_audit',
                type: 'auto'
            },
            {
                name: 'atna_audit_host',
                type: 'auto'
            },
            {
                name: 'atna_audit_port',
                type: 'auto'
            },
            {
                name: 'atna_audit_localcert',
                type: 'auto'
            },
            {
                name: 'atna_audit_cacert',
                type: 'auto'
            },
            {
                name: 'mysql_bin_dir',
                type: 'auto'
            },
            {
                name: 'perl_bin_dir',
                type: 'auto'
            },
            {
                name: 'temporary_files_dir',
                type: 'auto'
            },
            {
                name: 'backup_log_dir',
                type: 'auto'
            },
            {
                name: 'state_data_type',
                type: 'auto'
            },
            {
                name: 'state_list',
                type: 'auto'
            },
            {
                name: 'state_custom_addlist_widget',
                type: 'auto'
            },
            {
                name: 'country_data_type',
                type: 'auto'
            },
            {
                name: 'country_list',
                type: 'auto'
            },
            {
                name: 'print_command',
                type: 'auto'
            },
            {
                name: 'default_chief_complaint',
                type: 'auto'
            },
            {
                name: 'default_new_encounter_form',
                type: 'auto'
            },
            {
                name: 'patient_id_category_name',
                type: 'auto'
            },
            {
                name: 'patient_photo_category_name',
                type: 'auto'
            },
            {
                name: 'MedicareReferrerIsRenderer',
                type: 'auto'
            },
            {
                name: 'post_to_date_benchmark',
                type: 'auto'
            },
            {
                name: 'enable_hylafax',
                type: 'auto'
            },
            {
                name: 'hylafax_server',
                type: 'auto'
            },
            {
                name: 'hylafax_basedir',
                type: 'auto'
            },
            {
                name: 'hylafax_enscript',
                type: 'auto'
            },
            {
                name: 'enable_scanner',
                type: 'auto'
            },
            {
                name: 'scanner_output_directory',
                type: 'auto'
            },
            {
                name: 'autosave',
                type: 'auto'
            }
        ]
    });