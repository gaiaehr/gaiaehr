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
            title: _('patient_id'),
            token: '[PATIENT_ID]'
        },
        {
            title: _('patient_name'),
            token: '[PATIENT_NAME]'
        },
        {
            title: _('patient_full_name'),
            token: '[PATIENT_FULL_NAME]'
        },
        {
            title: _('patient_mothers_maiden_name'),
            token: '[PATIENT_MAIDEN_NAME]'
        },
        {
            title: _('patient_last_name'),
            token: '[PATIENT_LAST_NAME]'
        },
        {
            title: _('patient_birthdate'),
            token: '[PATIENT_BIRTHDATE]'
        },
        {
            title: _('patient_marital_status'),
            token: '[PATIENT_MARITAL_STATUS]'
        },
        {
            title: _('patient_home_phone'),
            token: '[PATIENT_HOME_PHONE]'
        },
        {
            title: _('patient_mobile_phone'),
            token: '[PATIENT_MOBILE_PHONE]'
        },
        {
            title: _('patient_work_phone'),
            token: '[PATIENT_WORK_PHONE]'
        },
        {
            title: _('patient_email'),
            token: '[PATIENT_EMAIL]'
        },
        {
            title: _('patient_social_security'),
            token: '[PATIENT_SOCIAL_SECURITY]'
        },
        {
            title: _('patient_sex'),
            token: '[PATIENT_SEX]'
        },
        {
            title: _('patient_age'),
            token: '[PATIENT_AGE]'
        },
        {
            title: _('patient_city'),
            token: '[PATIENT_CITY]'
        },
        {
            title: _('patient_state'),
            token: '[PATIENT_STATE]'
        },
        {
            title: _('patient_home_address_line_1'),
            token: '[PATIENT_HOME_ADDRESS_LINE_ONE]'
        },
        {
            title: _('patient_home_address_line_1'),
            token: '[PATIENT_HOME_ADDRESS_LINE_TWO]'
        },
        {
            title: _('patient_home_address_zip_code'),
            token: '[PATIENT_HOME_ADDRESS_ZIP_CODE]'
        },
        {
            title: _('patient_home_address_city'),
            token: '[PATIENT_HOME_ADDRESS_CITY]'
        },
        {
            title: _('patient_home_address_state'),
            token: '[PATIENT_HOME_ADDRESS_STATE]'
        },
        {
            title: _('patient_postal_address_line_1'),
            token: '[PATIENT_POSTAL_ADDRESS_LINE_ONE]'
        },
        {
            title: _('patient_postal_address_line_2'),
            token: '[PATIENT_POSTAL_ADDRESS_LINE_TWO]'
        },
        {
            title: _('patient_postal_address_zip_code'),
            token: '[PATIENT_POSTAL_ADDRESS_ZIP_CODE]'
        },
        {
            title: _('patient_postal_address_city'),
            token: '[PATIENT_POSTAL_ADDRESS_CITY]'
        },
        {
            title: _('patient_postal_address_state'),
            token: '[PATIENT_POSTAL_ADDRESS_STATE]'
        },
        {
            title: _('patient_tabacco'),
            token: '[PATIENT_TABACCO]'
        },
        {
            title: _('patient_alcohol'),
            token: '[PATIENT_ALCOHOL]'
        },
        {
            title: _('patient_drivers_license'),
            token: '[PATIENT_DRIVERS_LICENSE]'
        },
        {
            title: _('patient_employeer'),
            token: '[PATIENT_EMPLOYEER]'
        },
        {
            title: _('patient_first_emergency_contact'),
            token: '[PATIENT_FIRST_EMERGENCY_CONTACT]'
        },
        {
            title: _('patient_referral'),
            token: '[PATIENT_REFERRAL]'
        },
        {
            title: _('patient_date_referred'),
            token: '[PATIENT_REFERRAL_DATE]'
        },
        {
            title: _('patient_balance'),
            token: '[PATIENT_BALANCE]'
        },
        {
            title: _('patient_picture'),
            token: '[PATIENT_PICTURE]'
        },
        {
            title: _('patient_primary_plan'),
            token: '[PATIENT_PRIMARY_PLAN]'
        },
        {
            title: _('patient_primary_plan_insured_person'),
            token: '[PATIENT_PRIMARY_INSURED_PERSON]'
        },
        {
            title: _('patient_primary_plan_contract_number'),
            token: '[PATIENT_PRIMARY_CONTRACT_NUMBER]'
        },
        {
            title: _('patient_primary_plan_expiration_date'),
            token: '[PATIENT_PRIMARY_EXPIRATION_DATE]'
        },
        {
            title: _('patient_secondary_plan'),
            token: '[PATIENT_SECONDARY_PLAN]'
        },
        {
            title: _('patient_secondary_insured_person'),
            token: '[PATIENT_SECONDARY_INSURED_PERSON]'
        },
        {
            title: _('patient_secondary_plan_contract_number'),
            token: '[PATIENT_SECONDARY_CONTRACT_NUMBER]'
        },
        {
            title: _('patient_secondary_plan_expiration_date'),
            token: '[PATIENT_SECONDARY_EXPIRATION_DATE]'
        },
        {
            title: _('patient_referral_details'),
            token: '[PATIENT_REFERRAL_DETAILS]'
        },
        {
            title: _('patient_referral_reason'),
            token: '[PATIENT_REFERRAL_REASON]'
        },
        {
            title: _('patient_head_circumference'),
            token: '[PATIENT_HEAD_CIRCUMFERENCE]'
        },
        {
            title: _('patient_height'),
            token: '[PATIENT_HEIGHT]'
        },
        {
            title: _('patient_pulse'),
            token: '[PATIENT_PULSE]'
        },
        {
            title: _('patient_respiratory_rate'),
            token: '[PATIENT_RESPIRATORY_RATE]'
        },
        {
            title: _('patient_temperature'),
            token: '[PATIENT_TEMPERATURE]'
        },
        {
            title: _('patient_weight'),
            token: '[PATIENT_WEIGHT]'
        },
        {
            title: _('patient_pulse_oximeter'),
            token: '[PATIENT_PULSE_OXIMETER]'
        },
        {
            title: _('patient_blood_preasure'),
            token: '[PATIENT_BLOOD_PREASURE]'
        },
        {
            title: _('patient_body_mass_index'),
            token: '[PATIENT_BMI]'
        },
        {
            title: _('patient_active_allergies_list'),
            token: '[PATIENT_ACTIVE_ALLERGIES_LIST]'
        },
        {
            title: _('patient_inactive_allergies_list'),
            token: '[PATIENT_INACTIVE_ALLERGIES_LIST]'
        },
        {
            title: _('patient_active_medications_list'),
            token: '[PATIENT_ACTIVE_MEDICATIONS_LIST]'
        },
        {
            title: _('patient_inactive_medications_list'),
            token: '[PATIENT_INACTIVE_MEDICATIONS_LIST]'
        },
        {
            title: _('patient_active_problems_list'),
            token: '[PATIENT_ACTIVE_PROBLEMS_LIST]'
        },
        {
            title: _('patient_inactive_problems_list'),
            token: '[PATIENT_INACTIVE_PROBLEMS_LIST]'
        },
        {
            title: _('patient_active_immunizations_list'),
            token: '[PATIENT_ACTIVE_IMMUNIZATIONS_LIST]'
        },
        {
            title: _('patient_inactive_immunizations_list'),
            token: '[PATIENT_INACTIVE_IMMUNIZATIONS_LIST]'
        },
        {
            title: _('patient_active_dental_list'),
            token: '[PATIENT_ACTIVE_DENTAL_LIST]'
        },
        {
            title: _('patient_inactive_dental_list'),
            token: '[PATIENT_INACTIVE_DENTAL_LIST]'
        },
        {
            title: _('patient_active_surgery_list'),
            token: '[PATIENT_ACTIVE_SURGERY_LIST]'
        },
        {
            title: _('patient_inactive_surgery_list'),
            token: '[PATIENT_INACTIVE_SURGERY_LIST]'
        },
        {
            title: _('encounter_date'),
            token: '[ENCOUNTER_DATE]'
        },
        {
            title: _('encounter_subjective_part'),
            token: '[ENCOUNTER_SUBJECTIVE]'
        },
        {
            title: _('encounter_subjective_part'),
            token: '[ENCOUNTER_OBJECTIVE]'
        },
        {
            title: _('encounter_assessment'),
            token: '[ENCOUNTER_ASSESSMENT]'
        },
        {
            title: _('encounter_assessment_list'),
            token: '[ENCOUNTER_ASSESSMENT_LIST]'
        },
        {
            title: _('encounter_assessment_code_list'),
            token: '[ENCOUNTER_ASSESSMENT_CODE_LIST]'
        },
        {
            title: _('encounter_assessment_full_list'),
            token: '[ENCOUNTER_ASSESSMENT_FULL_LIST]'
        },
        {
            title: _('encounter_plan'),
            token: '[ENCOUNTER_PLAN]'
        },
        {
            title: _('encounter_medications'),
            token: '[ENCOUNTER_MEDICATIONS]'
        },
        {
            title: _('encounter_immunizations'),
            token: '[ENCOUNTER_IMMUNIZATIONS]'
        },
        {
            title: _('encounter_allergies'),
            token: '[ENCOUNTER_ALLERGIES]'
        },
        {
            title: _('encounter_active_problems'),
            token: '[ENCOUNTER_ACTIVE_PROBLEMS]'
        },
        {
            title: _('encounter_surgeries'),
            token: '[ENCOUNTER_SURGERIES]'
        },
        {
            title: _('encounter_dental'),
            token: '[ENCOUNTER_DENTAL]'
        },
        {
            title: _('encounter_laboratories'),
            token: '[ENCOUNTER_LABORATORIES]'
        },
        {
            title: _('encounter_procedures_terms'),
            token: '[ENCOUNTER_PROCEDURES_TERMS]'
        },
        {
            title: _('encounter_cpt_codes_list'),
            token: '[ENCOUNTER_CPT_CODES]'
        },
        {
            title: _('encounter_signature'),
            token: '[ENCOUNTER_SIGNATURE]'
        },
        {
            title: _('orders_laboratories'),
            token: '[ORDERS_LABORATORIES]'
        },
        {
            title: _('orders_x_rays'),
            token: '[ORDERS_XRAYS]'
        },
        {
            title: _('orders_referral'),
            token: '[ORDERS_REFERRAL]'
        },
        {
            title: _('orders_other'),
            token: '[ORDERS_OTHER]'
        },
        {
            title: _('current_date'),
            token: '[CURRENT_DATE]'
        },
        {
            title: _('current_time'),
            token: '[CURRENT_TIME]'
        },
        {
            title: _('current_user_name'),
            token: '[CURRENT_USER_NAME]'
        },
        {
            title: _('current_user_full_name'),
            token: '[CURRENT_USER_FULL_NAME]'
        },
        {
            title: _('current_user_license_number'),
            token: '[CURRENT_USER_LICENSE_NUMBER]'
        },
        {
            title: _('current_user_dea_license_number'),
            token: '[CURRENT_USER_DEA_LICENSE_NUMBER]'
        },
        {
            title: _('current_user_dm_license_number'),
            token: '[CURRENT_USER_DM_LICENSE_NUMBER]'
        },
        {
            title: _('current_user_npi_license_number'),
            token: '[CURRENT_USER_NPI_LICENSE_NUMBER]'
        },
        {
            title: _('referral_id'),
            token: '[REFERRAL_ID]'
        },
	    {
            title: _('referral_date'),
            token: '[REFERRAL_DATE]'
        },
	    {
            title: _('referral_reason'),
            token: '[REFERRAL_REASON]'
        },
	    {
            title: _('referral_diagnosis'),
            token: '[REFERRAL_DIAGNOSIS]'
        },
	    {
            title: _('referral_service_request'),
            token: '[REFERRAL_SERVICE]'
        },
	    {
            title: _('referral_risk_level'),
            token: '[REFERRAL_RISK_LEVEL]'
        },
	    {
            title: _('referral_by'),
            token: '[REFERRAL_BY_TEXT]'
        },
	    {
            title: _('referral_to'),
            token: '[REFERRAL_TO_TEXT]'
        }
    ]
});