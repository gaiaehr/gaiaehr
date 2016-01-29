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

Ext.define('App.controller.patient.Insurance', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'PatientInsuranceFormSubscribeRelationshipCmb',
			selector: 'PatientInsuranceFormSubscribeRelationshipCmb'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'#PatientInsuranceFormSubscribeRelationshipCmb':{
				change: me.onPatientInsuranceFormSubscribeRelationshipCmbChange
			}
		});
	},

	onPatientInsuranceFormSubscribeRelationshipCmbChange: function(cmb, value){

		var me = this,
			subscriberFields = cmb.up('fieldset').query('[isFormField]'),
			disable = value == '01';

		for(var i = 0; i < subscriberFields.length; i++){
			if(subscriberFields[i].name == 'subscriber_relationship') continue;

			if(disable){
				subscriberFields[i].setDisabled(true);
				subscriberFields[i].reset();
				subscriberFields[i].allowBlank = true;
			}else{
				subscriberFields[i].setDisabled(false);

				if(
					subscriberFields[i].name == 'subscriber_given_name' ||
					subscriberFields[i].name == 'subscriber_surname' ||
					subscriberFields[i].name == 'subscriber_dob' ||
					subscriberFields[i].name == 'subscriber_sex' ||
					subscriberFields[i].name == 'subscriber_street' ||
					subscriberFields[i].name == 'subscriber_city' ||
					subscriberFields[i].name == 'subscriber_state' ||
					subscriberFields[i].name == 'subscriber_country' ||
					subscriberFields[i].name == 'subscriber_postal_code' ||
					subscriberFields[i].name == 'subscriber_employer'
				){
					subscriberFields[i].allowBlank = false;
				}

			}
		}

	}

});