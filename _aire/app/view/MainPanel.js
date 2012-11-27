Ext.define('App.view.MainPanel', {
    extend: 'Ext.Container',
    xtype: 'mainpanel',
    requires: [
        'Ext.navigation.Bar',
        'App.view.HomePanel',
        'App.view.PatientSummaryPanel',
        'App.view.PatientRecordPanel',
        'App.view.PatientDemographicsPanel',
        'App.view.PatientProgressNotesPanel',
        'App.view.PatientChartsPanel',
        'App.view.PatientImagesPanel',
        'App.view.PatientDocumentsPanel',
        'App.view.PatientLabResultsPanel',
        'App.view.PatientClinicalOrdersPanel'
    ],
    config: {
        scrollable: false,
        action:'mainPanel',
        cls:'mainPanel',
        layout: {
            type: 'card',
            animation: {
                type: 'slide',
                direction: 'left',
                duration: 250
            }
        },
        defaults:{ padding:10 },
        items:[
            {
                title:'Home',
                xtype:'homepanel'
            },
            {
                title:'Patient Summary Panel',
                xtype:'patientsummarypanel'
            },
            {
                title:'Patient Record Panel',
                xtype:'patientrecordpanel'
            },
            {
                title:'Patient Demographics Panel',
                xtype:'patientdemographicspanel'
            },
            {
                title:'Patient Images Panel',
                xtype:'patientprogressnotespanel'
            },
            {
                title:'Patient Charts Panel',
                xtype:'patientchartspanel'
            },
            {
                title:'Patient Name',
                xtype:'patientimagespanel'
            },
            {
                title:'Patient Documents Panel',
                xtype:'patientdocumentspanel'
            },
            {
                title:'Patient Lab Results',
                xtype:'patientlabresultspanel'
            },
            {
                title:'Patient Clinical Orders Panel',
                xtype:'patientclinicalorderspanel'
            }

        ]
    }
});