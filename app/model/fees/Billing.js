/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.fees.Billing', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'eid', type: 'int '},
        {name: 'pid', type: 'int'},
        {name: 'patientName', type: 'string'},
        {name: 'primaryProvider', type: 'string'},
        {name: 'encounterProvider', type: 'string'},
        {name: 'supervisorProvider', type: 'string'},
        {name: 'facility', type: 'string'},
        {name: 'billing_facility', type: 'string'},
        {name: 'start_date', type: 'string'},
        {name: 'close_date', type: 'string'},
        {name: 'billing_stage', type: 'int'},
        {name: 'icdxCodes', type: 'auto'}
    ],
    proxy : {
        type: 'direct',
        api : {
            read  : Fees.getFilterEncountersBillingData
        },
        reader     : {
            type: 'json',
            root: 'encounters',
            totalProperty: 'totals'
        }
    }

});