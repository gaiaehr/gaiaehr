/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('App.store.patientfile.Surgery', {
	extend: 'Ext.data.Store',
	model     : 'App.model.patientfile.Surgery',
	remoteSort: true,
	autoLoad  : false
});