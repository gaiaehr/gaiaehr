/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/19/12
 * Time: 10:31 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.PatientSummaryPanel', {
    extend: 'Ext.Panel',
    xtype: 'patientsummarypanel',
    config: {
        layout: 'vbox',
        scrollable: false,
        nav: 'patientlistnav',
        tier: 2,
        items: [
            {
                xtype: 'container',
                action: 'patientSummaryHeader',
                tpl: Ext.create('Ext.XTemplate', '' +
                    '<div class="patientHeader">',
                    '   <div class="left">',
                    '       <img src="http://localhost/gaiaehr/sites/default/patients/{pid}/patientPhotoId.jpg{photoSrc}" width="132" height="132"/>',
                    '       <p>Joe P. Smith{name} | {pid}</p>',
                    '       <p>Encounter Brief Description Area Exmaple{brief_description}</p>',
                    '       <p>Srrvice: 07/25/12{service_date} | Onset: 07/24/12{onset_date} </p>',
                    '   </div>',
                    '   <div class="right">',
                    '       <p>07/15/1948{DOB}</p>',
                    '       <p>52{age} | M{sex}</p>',
                    '       <p class="priority {priority}">{priority}</p>',
                    '   </div>',
                    '</div>'
                )
            },
            {
                xtype: 'list',
                flex: 1,
                margin: '10 0 0 0',
                itemTpl: '<div class="contact">{firstName} <strong>{lastName}</strong></div>',
                store: Ext.create('App.store.Patients'),
                grouped: true
            }
        ]
    }
});