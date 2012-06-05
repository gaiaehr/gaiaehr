/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('App.store.patientfile.Dental', {
	extend: 'Ext.data.Store',
	model     : 'App.model.patientfile.Dental',
	remoteSort: true,
	autoLoad  : false
});