/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.Medications', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
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