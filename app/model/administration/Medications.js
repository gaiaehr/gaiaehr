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

Ext.define('App.model.administration.Medications', {
	extend: 'Ext.data.Model',
	table: {
		name:'medications',
		comment:'Medications'
	},
	fields: [
		{name: 'id', type: 'int', comment: 'Medications ID'},
		{name: 'PRODUCTNDC' },
		{name: 'PROPRIETARYNAME' },
		{name: 'NONPROPRIETARYNAME' },
		{name: 'DOSAGEFORMNAME' },
		{name: 'ROUTENAME' },
		{name: 'ACTIVE_NUMERATOR_STRENGTH' },
		{name: 'ACTIVE_INGRED_UNIT' }
	],
    proxy: {
    		type       : 'direct',
    		api        : {
    			read  : Medications.getMedications,
    			create: Medications.addMedications,
    			destroy: Medications.removeMedications,
			    update: Medications.updateMedications
    		},
    		reader     : {
    			totalProperty: 'totals',
    			root         : 'rows'
    		}

    	}


});