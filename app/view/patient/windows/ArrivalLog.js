/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 10:46 PM
 */
Ext.define('App.view.patient.windows.ArrivalLog', {
	extend: 'App.ux.window.Window',
	title      : i18n('patient_arrival_log'),
	closeAction: 'hide',
    layout     : 'fit',
	modal      : true,
	width      : 900,
	height     : 600,
	maximizable: true,
	initComponent: function() {
		var me = this;


        me.store = Ext.create('App.store.patient.PatientArrivalLog',{
            autoSync:true
        });

		me.tbar = [
            {
                xtype       : 'patienlivetsearch',
                fieldLabel  : i18n('look_for_patient'),
                width       : 400,
                hideLabel:false,
                enableKeyEvents:true,
                listeners:{
                    scope:me,
                    select:me.onPatientSearchSelect,
                    keyup:me.onPatientSearchKeyUp

                }
		    },
            '-',
            {
                text: i18n('add_new_patient'),
                iconCls:'icoAddRecord',
                action:'newPatientBtn',
                disabled:true,
                scope:me,
                handler:me.onNewPatient
		    },
            '->',
            {
                xtype:'tool',
                type: 'refresh',
                scope:me,
                handler:me.onGridReload
            }
        ];

		me.items = [
            me.ckGrid = Ext.create('Ext.grid.Panel',{
                store:me.store,
                margin:5,
                columns:[
                    {
                        xtype:'actioncolumn',
                        width:25,
                        items: [
                            {
                                icon: 'resources/images/icons/delete.png',  // Use a URL in the icon config
                                tooltip: i18n('remove'),
                                scope:me,
                                handler: me.onPatientRemove
                            }
                        ]
                    },
                    {
                        header: i18n('time'),
                        dataIndex:'time',
	                    width:130
                    },
                    {
                        header: i18n('record') + ' #',
                        dataIndex:'pid'
                    },
                    {
                        header: i18n('patient_name'),
                        dataIndex:'name',
                        flex:1
                    },
                    {
                        header: i18n('insurance'),
                        dataIndex:'insurance'
                    },
                    {
                        header: i18n('area'),
                        dataIndex:'area'
                    },
                    {
                        width:25,
                        dataIndex:'warning',
                        renderer:me.warnRenderer
                    }
                ],
                listeners:{
                    scope:me,
                    itemdblclick:me.onPatientDlbClick
                }

            })
		];

		me.listeners = {
			scope:me,
			show:me.onWinShow
		};

		me.callParent(arguments);
	},

    onPatientSearchSelect:function(field, record){
        var me = this,
            store = me.query('grid')[0].getStore(),
            btn = me.query('button[action="newPatientBtn"]')[0];
        store.add({
            pid:record[0].data.pid,
            name:record[0].data.fullname,
            time: Ext.Date.format(new Date(), 'Y-m-d H:i:s'),
            isNew:false
        });
        field.reset();
        btn.setDisabled(true);
    },

    onPatientSearchKeyUp:function(field){
        this.query('button[action="newPatientBtn"]')[0].setDisabled(field.getValue() == null);
    },

    onNewPatient:function(btn){
        var me = this,
            field = me.query('patienlivetsearch')[0],
            name = field.getValue(),
            store = me.query('grid')[0].getStore();
        field.reset();
        btn.disable();
        store.add({
            name:name,
            time: Ext.Date.format(new Date(), 'Y-m-d H:i:s'),
            isNew:true
        });
    },

    onPatientRemove:function(grid, rowIndex){
        var store = grid.getStore(),
	        me = this,
            record = store.getAt(rowIndex);
	    Encounter.checkForAnOpenedEncounterByPid({pid:record.data.pid,date:Ext.Date.format(new Date(), 'Y-m-d H:i:s')}, function(provider, response){
		    if(response.result) {
			    me.msg('Oops!', i18n('patient_have_a_opened_encounter'));
		    } else {
			    me.msg('Sweet!', i18n('patient_have_been_removed'));
			    store.remove(record);
		    }
	    });




    },

    onPatientDlbClick:function(grid, record){
        var me = this,
            data = record.data;
	    // TODO: pass priority!
        app.setPatient(data.pid, data.name, function(){
            app.openPatientSummary();
        });
        me.close();
    },

    onGridReload:function(){
        this.store.load();
    },

	onWinShow:function(){
        var me = this;
        me.onGridReload();
        new Ext.util.DelayedTask(function(){
            me.query('patienlivetsearch')[0].focus();
        }).delay(1000);

	}

});
