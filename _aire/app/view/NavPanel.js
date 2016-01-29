/**
 * Created with JetBrains PhpStorm.
 * User: ernesto
 * Date: 11/11/12
 * Time: 1:45 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.NavPanel', {
    extend: 'Ext.Container',
    xtype:'navpanel',
    requires:[
        'Ext.navigation.Bar',
        'App.view.PatientListNav',
        'App.view.MedicalRecordNav'
    ],
    config: {
        cls:'leftNav',
        width:350,
        collapsed:false,
        layout: {
            type: 'card',
            animation: {
                type: 'slide',
                direction: 'left',
                duration: 250
            }
        },
        items:[
            {
                xtype : 'patientlistnav',
                flex:1
            },
            {
                xtype : 'medicalrecordnav',
                flex:1
            },
            {
                action: 'leftNavTitleBar',
                xtype : 'titlebar',
                docked: 'top',
                title : 'Patients',
                items:[
                    {
                        align: 'left',
                        cls:'collapseMenuBtn',
                        action:'leftNavCollapseBtn',
                        minWidth:85
                    }
                ]
            }
        ]
    }
});