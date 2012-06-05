/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patientfile.Immunization', {
	extend: 'Ext.data.Store',
	model     : 'App.model.patientfile.Immunization',
	remoteSort: false,
	autoLoad  : true
});

