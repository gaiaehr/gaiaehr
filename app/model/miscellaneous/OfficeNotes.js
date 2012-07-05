/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */

Ext.define('App.model.miscellaneous.OfficeNotes', {
	extend: 'Ext.data.Model',
    fields: [
        {name: 'id', type: 'int'},
        {name: 'date', type: 'date', dateFormat: 'c'},
        {name: 'body', type: 'string'},
        {name: 'user', type: 'string'},
        {name: 'facility_id', type: 'string'},
        {name: 'activity', type: 'string'}
    ],
    proxy : {
        type: 'direct',
        api : {
            read  : OfficeNotes.getOfficeNotes,
            create: OfficeNotes.addOfficeNotes,
            update: OfficeNotes.updateOfficeNotes
        }
    }
});