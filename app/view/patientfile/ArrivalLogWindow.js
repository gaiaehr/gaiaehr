/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 10:46 PM
 */
Ext.define('App.view.patientfile.ArrivalLogWindow', {
	extend: 'Ext.window.Window',
	title      : 'Patient Arrival Log',
	closeAction: 'hide',
    layout     : 'fit',
	modal      : true,
	width      : 900,
	height     : 600,
	maximizable: true,
    mixins: ['App.classes.RenderPanel'],
	initComponent: function() {
		var me = this;


        me.store = Ext.create('App.store.patientfile.PatientArrivalLog',{
            autoSync:true
        });

		me.tbar = [
            {
                xtype       : 'patienlivetsearch',
                fieldLabel  : 'Look for Patient',
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
                text:'Add New Patient',
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
                                icon: 'ui_icons/delete.png',  // Use a URL in the icon config
                                tooltip: 'Remove',
                                scope:me,
                                handler: me.onPatientRemove
                            }
                        ]
                    },
                    {
                        header:'Time',
                        dataIndex:'time'
                    },
                    {
                        header:'Record #',
                        dataIndex:'pid'
                    },
                    {
                        header:'Patient Name',
                        dataIndex:'name',
                        flex:1
                    },
                    {
                        header:'Insurance',
                        dataIndex:'insurance'
                    },
                    {
                        header:'Area',
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
            record = store.getAt(rowIndex);
        store.remove(record);
    },

    onPatientDlbClick:function(grid, record){
        var me = this,
            data = record.data;
        app.setCurrPatient(data.pid, data.name, function(){
            app.openPatientSummary();
        });
        me.close();
    },

    onGridReload:function(){
        this.store.load();
    },

	onWinShow:function(){
        this.onGridReload();
	}

});
