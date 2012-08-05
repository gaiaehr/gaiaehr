/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */
Ext.define('App.store.patient.EncounterCPTsICDs', {
	extend: 'Ext.data.Store',
	model     : 'App.model.patient.EncounterCPTsICDs',
	remoteSort: false,
	autoLoad  : false
});