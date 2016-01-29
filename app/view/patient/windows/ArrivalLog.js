/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.patient.windows.ArrivalLog', {
	extend: 'App.ux.window.Window',
	title      : _('patient_arrival_log'),
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
                fieldLabel  : _('look_for_patient'),
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
                text: _('add_new_patient'),
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
                                tooltip: _('remove'),
                                scope:me,
                                handler: me.onPatientRemove
                            }
                        ]
                    },
                    {
                        header: _('time'),
                        dataIndex:'time',
	                    width:130
                    },
                    {
                        header: _('record') + ' #',
                        dataIndex:'pid'
                    },
                    {
                        header: _('patient_name'),
                        dataIndex:'name',
                        flex:1
                    },
                    {
                        header: _('insurance'),
                        dataIndex:'insurance'
                    },
                    {
                        header: _('area'),
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
			    me.msg('Oops!', _('patient_have_a_opened_encounter'));
		    } else {
			    me.msg('Sweet!', _('patient_have_been_removed'));
			    store.remove(record);
		    }
	    });




    },

    onPatientDlbClick:function(grid, record){
        var me = this,
            data = record.data;
	    // TODO: pass priority!
        app.setPatient(data.pid, data.name, null, function(){
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
