/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.QRCptCodes', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type:'int'},
        {name: 'eid', type:'int'},
        {name: 'code', type: 'string'},
        {name: 'code_text', type: 'string'},
        {name: 'code_text_medium', type: 'string'},
        {name: 'place_of_service', type: 'string'},
        {name: 'emergency', type: 'bool'},
        {name: 'charge', type: 'string'},
        {name: 'days_of_units', type: 'string'},
        {name: 'essdt_plan', type: 'string'},
        {name: 'modifiers', type: 'string'},
        {name: 'status', type: 'int', defaultValue: 0}
    ],
    proxy : {
        type  : 'direct',
        api   : {
            read: Services.getCptCodes
        },
        reader: {
            root         : 'rows',
            totalProperty: 'totals'
        }
    }
});