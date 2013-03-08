/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

Ext.define('App.model.patient.Patient', {
	extend: 'Ext.data.Model',
	table: {
		name:'patient',
//        engine:'InnoDB',
//        autoIncrement:1,
//        charset:'utf8',
//        collate:'utf8_bin',
		comment:'Patients/Demographics'
	},
	fields: [
//        {name: 'id', type: 'int', dataType: 'bigint', len: 20, primaryKey : true, autoIncrement : true, allowNull : false, store: true, comment: 'Patient ID'},
        {name: 'pid',                               type: 'int',    comment:'patient ID'},
		{name: 'create_uid',                        type: 'int',    comment:'create user ID'},
		{name: 'write_uid',                         type: 'int',    comment:'update user ID'},
		{name: 'create_date',                       type: 'date',   comment:'create date', dateFormat:'Y-m-d H:i:s'},
		{name: 'update_date',                       type: 'date',   comment:'last update date', dateFormat:'Y-m-d H:i:s'},
        {name: 'title',                             type: 'string', comment:'Title Mr. Sr.'},
        {name: 'fname',                             type: 'string', comment:'first name'},
        {name: 'mname',                             type: 'string', comment:'middle name'},
        {name: 'lname',                             type: 'string', comment:'last name'},
        {name: 'sex',                               type: 'string', comment:'sex'},
        {name: 'DOB',                               type: 'date',   comment:'day of birth', dateFormat:'Y-m-d H:i:s', defaultValue:'0000-00-00 00:00:00'},
        {name: 'marital_status',                    type: 'string', comment:'marital status'},
        {name: 'SS',                                type: 'string', comment:'social security'},
        {name: 'pubpid',                            type: 'string', comment:'external/reference id'},
        {name: 'drivers_license',                   type: 'string', comment:'driver licence #'},
        {name: 'address',                           type: 'string', comment:'address'},
        {name: 'city',                              type: 'string', comment:'city'},
        {name: 'state',                             type: 'string', comment:'state'},
        {name: 'country',                           type: 'string', comment:'country'},
		{name: 'zipcode',                           type: 'string', comment:'postal code'},
		{name: 'home_phone',                        type: 'string', comment:'home phone #'},
        {name: 'mobile_phone',                      type: 'string', comment:'mobile phone #'},
        {name: 'work_phone',                        type: 'string', comment:'work phone #'},
        {name: 'email',                             type: 'string', comment:'email'},
        {name: 'mothers_name',                      type: 'string', comment:'mother name'},
        {name: 'guardians_name',                    type: 'string', comment:'guardians name'},
        {name: 'emer_contact',                      type: 'string', comment:'emergency contact'},
        {name: 'emer_phone',                        type: 'string', comment:'emergency phone #'},
        {name: 'provider',                          type: 'string', comment:'default provider'},
        {name: 'pharmacy',                          type: 'string', comment:'default pharmacy'},
        {name: 'hipaa_notice',                      type: 'string', comment:'HIPAA notice status'},
		{name: 'race',                              type: 'string', comment:'race'},
		{name: 'ethnicity',                         type: 'string', comment:'ethnicity'},
		{name: 'lenguage',                          type: 'string', comment:'language'},
        {name: 'allow_leave_msg',                   type: 'bool'},
        {name: 'allow_voice_msg',                   type: 'bool'},
        {name: 'allow_mail_msg',                    type: 'bool'},
        {name: 'allow_sms',                         type: 'bool'},
        {name: 'allow_email',                       type: 'bool'},
        {name: 'allow_immunization_registry',       type: 'bool'},
        {name: 'allow_immunization_info_sharing',   type: 'bool'},
        {name: 'allow_health_info_exchange',        type: 'bool'},
        {name: 'allow_patient_web_portal',          type: 'bool'},
        {name: 'occupation',                        type: 'string', comment:'patient occupation'},
        {name: 'employer_name',                     type: 'string', comment:'employer name'},
        {name: 'employer_address',                  type: 'string', comment:'employer address'},
        {name: 'employer_city',                     type: 'string', comment:'employer city'},
        {name: 'employer_state',                    type: 'string', comment:'employer state'},
        {name: 'employer_country',                  type: 'string', comment:'employer country'},
        {name: 'employer_postal_code',              type: 'string', comment:'employer postal code'},
        {name: 'rating',                            type: 'int',    comment:'patient occupation'}
	],
	idProperty:'pid',
	proxy : {
		type: 'direct',
		api : {
			read: Patient.getPatients,
			create: Patient.savePatient,
			update: Patient.savePatient
		}
	},
	hasMany: [
		{
			model: 'App.model.patient.Insurance',
			name: 'insurance',
			primaryKey: 'pid',
			foreignKey: 'pid'
		}
	]
});