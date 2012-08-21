/*!
 * Extensible 1.6.0-b1
 * Copyright(c) 2010-2012 Extensible, LLC
 * licensing@ext.ensible.com
 * http://ext.ensible.com
 */
Ext.define('Extensible.calendar.data.RecurrenceModel', {
    extend: 'Extensible.data.Model',

    requires: [
        'Extensible.calendar.data.RecurrenceMappings'
    ],
    
    mappingClass: 'Extensible.calendar.data.RecurrenceMappings',
    
    mappingIdProperty: 'RecurrenceId'
    
},
function() {
    this.reconfigure();
});