/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:11 PM
 */

Ext.define('App.store.account.Voucher', {
	extend: 'Ext.data.Store',
	model     : 'App.model.account.Voucher',
    remoteSort: false,
	autoLoad  : false
});