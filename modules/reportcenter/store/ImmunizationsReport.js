/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('Modules.reportcenter.store.ImmunizationsReport', {
	extend: 'Ext.data.Store',
	model     : 'Modules.reportcenter.model.ImmunizationsReport',
    remoteSort: false,
	autoLoad  : false
});