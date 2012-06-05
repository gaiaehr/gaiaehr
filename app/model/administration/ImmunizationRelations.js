/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.ImmunizationRelations', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'immunization_id', type: 'int'},
		{name: 'foreign_id', type: 'int'},
		{name: 'code' },
		{name: 'code_text', type: 'string' },
		{name: 'code_type' }

	],
    proxy: {
    		type       : 'direct',
    		api        : {
    			read  : PreventiveCare.getRelations,
    			create: PreventiveCare.addRelations,
    			destroy: PreventiveCare.removeRelations
    		}


    	}


});