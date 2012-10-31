/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('Modules.reportcenter.store.Appointment', {
	extend: 'Ext.data.Store',
	model     : 'Modules.reportcenter.model.Appointment',
    remoteSort: false,
	autoLoad  : false
});