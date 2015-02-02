/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.store.administration.DocumentToken', {
    model: 'App.model.administration.DocumentToken',
    extend: 'Ext.data.Store',
    data: [
        {
            title: i18n('patient_id'),
            token: '[PATIENT_ID]'
        },
        {
            title: i18n('patient_name'),
            token: '[PATIENT_NAME]'
        },
        {
            title: i18n('patient_full_name'),
            token: '[PATIENT_FULL_NAME]'
        },
        {
            title: i18n('patient_mothers_maiden_name'),
            token: '[PATIENT_MAIDEN_NAME]'
        },
        {
            title: i18n('patient_last_name'),
            token: '[PATIENT_LAST_NAME]'
        },
        {
            title: i18n('patient_birthdate'),
            token: '[PATIENT_BIRTHDATE]'
        },
        {
            title: i18n('patient_marital_status'),
            token: '[PATIENT_MARITAL_STATUS]'
        },
        {
            title: i18n('patient_home_phone'),
            token: '[PATIENT_HOME_PHONE]'
        },
        {
            title: i18n('patient_mobile_phone'),
            token: '[PATIENT_MOBILE_PHONE]'
        },
        {
            title: i18n('patient_work_phone'),
            token: '[PATIENT_WORK_PHONE]'
        },
        {
            title: i18n('patient_email'),
            token: '[PATIENT_EMAIL]'
        },
        {
            title: i18n('patient_social_security'),
            token: '[PATIENT_SOCIAL_SECURITY]'
        },
        {
            title: i18n('patient_sex'),
            token: '[PATIENT_SEX]'
        },
        {
            title: i18n('patient_age'),
            token: '[PATIENT_AGE]'
        },
        {
            title: i18n('patient_city'),
            token: '[PATIENT_CITY]'
        },
        {
            title: i18n('patient_state'),
            token: '[PATIENT_STATE]'
        },
        {
            title: i18n('patient_home_address_line_1'),
            token: '[PATIENT_HOME_ADDRESS_LINE_ONE]'
        },
        {
            title: i18n('patient_home_address_line_1'),
            token: '[PATIENT_HOME_ADDRESS_LINE_TWO]'
        },
        {
            title: i18n('patient_home_address_zip_code'),
            token: '[PATIENT_HOME_ADDRESS_ZIP_CODE]'
        },
        {
            title: i18n('patient_home_address_city'),
            token: '[PATIENT_HOME_ADDRESS_CITY]'
        },
        {
            title: i18n('patient_home_address_state'),
            token: '[PATIENT_HOME_ADDRESS_STATE]'
        },
        {
            title: i18n('patient_postal_address_line_1'),
            token: '[PATIENT_POSTAL_ADDRESS_LINE_ONE]'
        },
        {
            title: i18n('patient_postal_address_line_2'),
            token: '[PATIENT_POSTAL_ADDRESS_LINE_TWO]'
        },
        {
            title: i18n('patient_postal_address_zip_code'),
            token: '[PATIENT_POSTAL_ADDRESS_ZIP_CODE]'
        },
        {
            title: i18n('patient_postal_address_city'),
            token: '[PATIENT_POSTAL_ADDRESS_CITY]'
        },
        {
            title: i18n('patient_postal_address_state'),
            token: '[PATIENT_POSTAL_ADDRESS_STATE]'
        },
        {
            title: i18n('patient_tabacco'),
            token: '[PATIENT_TABACCO]'
        },
        {
            title: i18n('patient_alcohol'),
            token: '[PATIENT_ALCOHOL]'
        },
        {
            title: i18n('patient_drivers_license'),
            token: '[PATIENT_DRIVERS_LICENSE]'
        },
        {
            title: i18n('patient_employeer'),
            token: '[PATIENT_EMPLOYEER]'
        },
        {
            title: i18n('patient_first_emergency_contact'),
            token: '[PATIENT_FIRST_EMERGENCY_CONTACT]'
        },
        {
            title: i18n('patient_referral'),
            token: '[PATIENT_REFERRAL]'
        },
        {
            title: i18n('patient_date_referred'),
            token: '[PATIENT_REFERRAL_DATE]'
        },
        {
            title: i18n('patient_balance'),
            token: '[PATIENT_BALANCE]'
        },
        {
            title: i18n('patient_picture'),
            token: '[PATIENT_PICTURE]'
        },
        {
            title: i18n('patient_primary_plan'),
            token: '[PATIENT_PRIMARY_PLAN]'
        },
        {
            title: i18n('patient_primary_plan_insured_person'),
            token: '[PATIENT_PRIMARY_INSURED_PERSON]'
        },
        {
            title: i18n('patient_primary_plan_contract_number'),
            token: '[PATIENT_PRIMARY_CONTRACT_NUMBER]'
        },
        {
            title: i18n('patient_primary_plan_expiration_date'),
            token: '[PATIENT_PRIMARY_EXPIRATION_DATE]'
        },
        {
            title: i18n('patient_secondary_plan'),
            token: '[PATIENT_SECONDARY_PLAN]'
        },
        {
            title: i18n('patient_secondary_insured_person'),
            token: '[PATIENT_SECONDARY_INSURED_PERSON]'
        },
        {
            title: i18n('patient_secondary_plan_contract_number'),
            token: '[PATIENT_SECONDARY_CONTRACT_NUMBER]'
        },
        {
            title: i18n('patient_secondary_plan_expiration_date'),
            token: '[PATIENT_SECONDARY_EXPIRATION_DATE]'
        },
        {
            title: i18n('patient_referral_details'),
            token: '[PATIENT_REFERRAL_DETAILS]'
        },
        {
            title: i18n('patient_referral_reason'),
            token: '[PATIENT_REFERRAL_REASON]'
        },
        {
            title: i18n('patient_head_circumference'),
            token: '[PATIENT_HEAD_CIRCUMFERENCE]'
        },
        {
            title: i18n('patient_height'),
            token: '[PATIENT_HEIGHT]'
        },
        {
            title: i18n('patient_pulse'),
            token: '[PATIENT_PULSE]'
        },
        {
            title: i18n('patient_respiratory_rate'),
            token: '[PATIENT_RESPIRATORY_RATE]'
        },
        {
            title: i18n('patient_temperature'),
            token: '[PATIENT_TEMPERATURE]'
        },
        {
            title: i18n('patient_weight'),
            token: '[PATIENT_WEIGHT]'
        },
        {
            title: i18n('patient_pulse_oximeter'),
            token: '[PATIENT_PULSE_OXIMETER]'
        },
        {
            title: i18n('patient_blood_preasure'),
            token: '[PATIENT_BLOOD_PREASURE]'
        },
        {
            title: i18n('patient_body_mass_index'),
            token: '[PATIENT_BMI]'
        },
        {
            title: i18n('patient_active_allergies_list'),
            token: '[PATIENT_ACTIVE_ALLERGIES_LIST]'
        },
        {
            title: i18n('patient_inactive_allergies_list'),
            token: '[PATIENT_INACTIVE_ALLERGIES_LIST]'
        },
        {
            title: i18n('patient_active_medications_list'),
            token: '[PATIENT_ACTIVE_MEDICATIONS_LIST]'
        },
        {
            title: i18n('patient_inactive_medications_list'),
            token: '[PATIENT_INACTIVE_MEDICATIONS_LIST]'
        },
        {
            title: i18n('patient_active_problems_list'),
            token: '[PATIENT_ACTIVE_PROBLEMS_LIST]'
        },
        {
            title: i18n('patient_inactive_problems_list'),
            token: '[PATIENT_INACTIVE_PROBLEMS_LIST]'
        },
        {
            title: i18n('patient_active_immunizations_list'),
            token: '[PATIENT_ACTIVE_IMMUNIZATIONS_LIST]'
        },
        {
            title: i18n('patient_inactive_immunizations_list'),
            token: '[PATIENT_INACTIVE_IMMUNIZATIONS_LIST]'
        },
        {
            title: i18n('patient_active_dental_list'),
            token: '[PATIENT_ACTIVE_DENTAL_LIST]'
        },
        {
            title: i18n('patient_inactive_dental_list'),
            token: '[PATIENT_INACTIVE_DENTAL_LIST]'
        },
        {
            title: i18n('patient_active_surgery_list'),
            token: '[PATIENT_ACTIVE_SURGERY_LIST]'
        },
        {
            title: i18n('patient_inactive_surgery_list'),
            token: '[PATIENT_INACTIVE_SURGERY_LIST]'
        },
        {
            title: i18n('encounter_date'),
            token: '[ENCOUNTER_DATE]'
        },
        {
            title: i18n('encounter_subjective_part'),
            token: '[ENCOUNTER_SUBJECTIVE]'
        },
        {
            title: i18n('encounter_subjective_part'),
            token: '[ENCOUNTER_OBJECTIVE]'
        },
        {
            title: i18n('encounter_assessment'),
            token: '[ENCOUNTER_ASSESSMENT]'
        },
        {
            title: i18n('encounter_assessment_list'),
            token: '[ENCOUNTER_ASSESSMENT_LIST]'
        },
        {
            title: i18n('encounter_assessment_code_list'),
            token: '[ENCOUNTER_ASSESSMENT_CODE_LIST]'
        },
        {
            title: i18n('encounter_assessment_full_list'),
            token: '[ENCOUNTER_ASSESSMENT_FULL_LIST]'
        },
        {
            title: i18n('encounter_plan'),
            token: '[ENCOUNTER_PLAN]'
        },
        {
            title: i18n('encounter_medications'),
            token: '[ENCOUNTER_MEDICATIONS]'
        },
        {
            title: i18n('encounter_immunizations'),
            token: '[ENCOUNTER_IMMUNIZATIONS]'
        },
        {
            title: i18n('encounter_allergies'),
            token: '[ENCOUNTER_ALLERGIES]'
        },
        {
            title: i18n('encounter_active_problems'),
            token: '[ENCOUNTER_ACTIVE_PROBLEMS]'
        },
        {
            title: i18n('encounter_surgeries'),
            token: '[ENCOUNTER_SURGERIES]'
        },
        {
            title: i18n('encounter_dental'),
            token: '[ENCOUNTER_DENTAL]'
        },
        {
            title: i18n('encounter_laboratories'),
            token: '[ENCOUNTER_LABORATORIES]'
        },
        {
            title: i18n('encounter_procedures_terms'),
            token: '[ENCOUNTER_PROCEDURES_TERMS]'
        },
        {
            title: i18n('encounter_cpt_codes_list'),
            token: '[ENCOUNTER_CPT_CODES]'
        },
        {
            title: i18n('encounter_signature'),
            token: '[ENCOUNTER_SIGNATURE]'
        },
        {
            title: i18n('orders_laboratories'),
            token: '[ORDERS_LABORATORIES]'
        },
        {
            title: i18n('orders_x_rays'),
            token: '[ORDERS_XRAYS]'
        },
        {
            title: i18n('orders_referral'),
            token: '[ORDERS_REFERRAL]'
        },
        {
            title: i18n('orders_other'),
            token: '[ORDERS_OTHER]'
        },
        {
            title: i18n('current_date'),
            token: '[CURRENT_DATE]'
        },
        {
            title: i18n('current_time'),
            token: '[CURRENT_TIME]'
        },
        {
            title: i18n('current_user_name'),
            token: '[CURRENT_USER_NAME]'
        },
        {
            title: i18n('current_user_full_name'),
            token: '[CURRENT_USER_FULL_NAME]'
        },
        {
            title: i18n('current_user_license_number'),
            token: '[CURRENT_USER_LICENSE_NUMBER]'
        },
        {
            title: i18n('current_user_dea_license_number'),
            token: '[CURRENT_USER_DEA_LICENSE_NUMBER]'
        },
        {
            title: i18n('current_user_dm_license_number'),
            token: '[CURRENT_USER_DM_LICENSE_NUMBER]'
        },
        {
            title: i18n('current_user_npi_license_number'),
            token: '[CURRENT_USER_NPI_LICENSE_NUMBER]'
        },
        {
            title: i18n('referral_id'),
            token: '[REFERRAL_ID]'
        },
	    {
            title: i18n('referral_date'),
            token: '[REFERRAL_DATE]'
        },
	    {
            title: i18n('referral_reason'),
            token: '[REFERRAL_REASON]'
        },
	    {
            title: i18n('referral_diagnosis'),
            token: '[REFERRAL_DIAGNOSIS]'
        },
	    {
            title: i18n('referral_service_request'),
            token: '[REFERRAL_SERVICE]'
        },
	    {
            title: i18n('referral_risk_level'),
            token: '[REFERRAL_RISK_LEVEL]'
        },
	    {
            title: i18n('referral_by'),
            token: '[REFERRAL_BY_TEXT]'
        },
	    {
            title: i18n('referral_to'),
            token: '[REFERRAL_TO_TEXT]'
        }
    ]
});