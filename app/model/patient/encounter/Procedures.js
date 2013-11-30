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

Ext.define('App.model.patient.encounter.Procedures', {
    extend: 'Ext.data.Model',
    table: {
        name:'encounter_procedures',
        comment:'Patient Encounter Procedures'
    },
    fields:[
	    {
		    name: 'id',
		    type: 'int',
		    comment: 'Procedure ID'
	    },
	    {
		    name: 'pid',
		    type: 'int',
		    comment: 'patient ID'
	    },
	    {
		    name: 'eid',
		    type: 'int',
		    comment: 'Encounter ID'
	    },
	    {
		    name: 'create_uid',
		    type: 'int',
		    comment: 'create user ID'
	    },
	    {
		    name: 'update_uid',
		    type: 'int',
		    comment: 'update user ID'
	    },
	    {
		    name: 'create_date',
		    type: 'date',
		    comment: 'create date',
		    dateFormat: 'Y-m-d H:i:s'
	    },
	    {
		    name: 'update_date',
		    type: 'date',
		    comment: 'last update date',
		    dateFormat: 'Y-m-d H:i:s'
	    },
	    {
		    name: 'code',
		    type: 'string',
		    comment: 'procedure code'
	    },
	    {
		    name: 'code_text',
		    type: 'string',
		    comment: 'procedure description'
	    },
	    {
		    name: 'code_type',
		    type: 'string',
		    comment: 'CPT/ICD-10-PCS/ICD-9-CM/SNOMED/CDT'
	    },
	    {
		    name: 'observation',
		    type: 'string',
		    comment: 'observation found'
	    }
    ],
    proxy : {
        type: 'direct',
        api : {
            read: Procedures.loadProcedures,
            create: Procedures.saveProcedure,
            update: Procedures.saveProcedure,
            destroy: Procedures.destroyProcedure
        }
    }
});