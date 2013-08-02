/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.view.administration.Log',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelLog',
	uses : ['App.ux.GridPanel'],
	pageTitle : i18n('event_history_log'),
	initComponent : function()
	{
		var me = this;
        me.fullMode = window.innerWidth >= me.minWidthToFullMode;

        // *************************************************************************************
        // Log Data Store
        // *************************************************************************************
		me.logStore = Ext.create('App.store.administration.AuditLog');


		// *************************************************************************************
		// Create the GridPanel
		// *************************************************************************************
		me.logGrid = Ext.create('App.ux.GridPanel',
		{
			store : me.logStore,
			columns : [
			{
				text : 'id',
				sortable : false,
				dataIndex : 'id',
				hidden : true
			},
			{
				width : 120,
				text : i18n('date_created'),
				sortable : true,
				dataIndex : 'date',
                renderer: Ext.util.Format.dateRenderer('Y-m-d g:i:s a')
			},
			{
				width : 180,
				text : i18n('user'),
				sortable : true,
				dataIndex : 'user'
			},
            {
                width : 200,
                text : i18n('patient_record_id'),
                sortable : true,
                dataIndex : 'patient_id'
            },
			{
				flex: 1,
				text : i18n('event'),
				sortable : true,
				dataIndex : 'event'
			}],
			listeners :
			{
				scope : this,
				itemclick : me.onItemclick,
				itemdblclick : me.onItemdblclick
			},
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.logStore,
				displayInfo : true,
				emptyMsg : i18n('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : [
				{
					xtype : 'button',
					text : i18n('view_log_event_details'),
					iconCls : 'edit',
					itemId : 'detail',
					disabled : true,
					handler : function()
					{
						me.winLog.show();
					}
				},
                {
                    xtype: 'datefield',
                    labelWidth : 30,
                    width: 150,
                    fieldLabel: i18n('from'),
                    name: 'from_date',
                    format: 'Y-m-d'
                },
                {
                    xtype: 'datefield',
                    labelWidth : 30,
                    width: 150,
                    fieldLabel: i18n('to'),
                    name: 'to_date',
                    format: 'Y-m-d',
                    value: new Date()  // defaults to today
                },
                {
                    xtype: 'patienlivetsearch',
                    emptyText: i18n('patient_live_search') + '...',
                    fieldStyle: me.fullMode ? 'width:300' : 'width:250',
                    listeners: {
                        scope: me,
                        select: me.liveSearchSelect,
                        blur: function(combo){
                            combo.reset();
                        }
                    }
                },
                {
                    xtype: 'button',
                    text : i18n('filter')
                }]
			})
		});

		// *************************************************************************************
		// Event Detail Window
		// *************************************************************************************
		me.winLog = Ext.create('Ext.window.Window',
		{
			title : i18n('log_event_details'),
			width : 500,
			closeAction : 'hide',
			items : [
			{
				xtype : 'form',
				bodyStyle : 'padding: 10px;',
				autoWidth : true,
				border : false,
				hideLabels : true,
				defaults :
				{
					labelWidth : 89,
					anchor : '100%',
					layout :
					{
						type : 'hbox',
						defaultMargins :
						{
							top : 0,
							right : 5,
							bottom : 0,
							left : 0
						}
					}
				},
				items : [
				{
					xtype : 'textfield',
					hidden : true,
					name : 'id'
				},
				{
					fieldLabel : i18n('date'),
					xtype : 'displayfield',
					name : 'date'
				},
				{
					fieldLabel : i18n('event'),
					xtype : 'displayfield',
					name : 'event'
				},
				{
					fieldLabel : i18n('user'),
					xtype : 'displayfield',
					name : 'user'
				},
				{
					fieldLabel : i18n('facility'),
					xtype : 'displayfield',
					name : 'facility'
				},
				{
					fieldLabel : i18n('patient_record_id'),
					xtype : 'displayfield',
					name : 'patient_id'
				}]
			}],
			buttons : [
			{
				text : i18n('close'),
				handler : function()
				{
					this.up('window').hide();
				}
			}]
		});
		me.pageBody = [me.logGrid];
		me.callParent(arguments);
	}, // end of initComponent

	onItemclick : function(view, record)
	{
		var form = this.winLog.down('form'), editBtn = this.logGrid.down('toolbar').getComponent('detail');
		form.getForm().loadRecord(record);
		editBtn.enable();
	},
	onItemdblclick : function(view, record)
	{
		var form = this.winLog.down('form');
		form.getForm().loadRecord(record);
		this.winLog.show();
	},
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.logStore.load();
		callback(true);
	},

    liveSearchSelect: function(combo, selection){
        var me = this, post = selection[0];
        if(post){
            me.setPatient(post.get('pid'), null, function(){
                combo.reset();
                me.openPatientSummary();
            });
        }
    },

    openPatientSummary: function(){
        var me = this;

        me.navigateTo('App.view.patient.Summary', function(success){
            if(success){
                Ext.Function.defer(function() {
                    me.getPanelByCls('App.view.patient.Summary').onActive();
                }, 100);

            }
        });

    },

    setPatient: function(pid, eid, callback){
        var me = this;
        me.unsetPatient(function(){
            Patient.getPatientSetDataByPid(pid, function(provider, response){
                var data = response.result, msg1, msg2;
                if(data.readOnly){
                    msg1 = data.user + ' ' + i18n('is_currently_working_with') + ' "' + data.patient.name + '" ' + i18n('in') + ' "' + data.area + '" ' + i18n('area') + '.<br>' + i18n('override_read_mode_will_remove_the_patient_from_previous_user') + '.<br>' + i18n('do_you_would_like_to_override_read_mode');
                    msg2 = data.user + ' ' + i18n('is_currently_working_with') + ' "' + data.patient.name + '" ' + i18n('in') + ' "' + data.area + '" ' + i18n('area') + '.<br>';
                    Ext.Msg.show({
                        title: i18n('wait') + '!!!',
                        msg: data.overrideReadOnly ? msg1 : msg2,
                        buttons: data.overrideReadOnly ? Ext.Msg.YESNO : Ext.Msg.OK,
                        icon: Ext.MessageBox.WARNING,
                        fn: function(btn){
                            continueSettingPatient(btn != 'yes');
                        }
                    });
                }else{
                    continueSettingPatient(false);
                }
                function continueSettingPatient(readOnly){
                    me.patient = {
                        pid: data.patient.pid,
                        name: data.patient.name,
                        pic: data.patient.pic,
                        sex: data.patient.sex,
                        dob: data.patient.dob,
                        age: data.patient.age,
                        eid: eid,
                        priority: data.patient.priority,
                        readOnly: readOnly,
                        rating: data.patient.rating
                    };
                    var panels = me.MainPanel.items.items;
                    for(var i=0; i<panels.length; i++) if(panels[i].pageRankingDiv) panels[i].pageRankingDiv.setValue(me.patient.rating);
                    me.patientButtonSet(me.patient);
                    if(me.patientSummaryBtn) me.patientSummaryBtn.enable();
                    if(me.patientOpenVisitsBtn) me.patientOpenVisitsBtn.enable();
                    if(me.patientCreateEncounterBtn) me.patientCreateEncounterBtn.enable();
                    if(me.patientCloseCurrEncounterBtn) me.patientCloseCurrEncounterBtn.enable();
                    if(me.patientChargeBtn) me.patientChargeBtn.enable();
                    if(me.patientCheckOutBtn) me.patientCheckOutBtn.enable();
                    if(typeof callback == 'function') callback(me.patient);
                }
            },true);
        });
    },

    unsetPatient: function(callback, sendRequest){
        var me = this;
        if(sendRequest) Patient.unsetPatient(me.patient.pid);
        me.currEncounterId = null;
        me.patient = {
            pid: null,
            name: null,
            pic: null,
            sex: null,
            dob: null,
            age: null,
            eid: null,
            priority: null,
            readOnly: false,
            rating: null
        };
        me.patientButtonRemoveCls();
        if(typeof callback == 'function'){
            callback(true);
        }else{
            var panels = me.MainPanel.items.items;
            for(var i=0; i<panels.length; i++) if(panels[i].pageRankingDiv) panels[i].pageRankingDiv.setValue(0);
            if(me.patientCreateEncounterBtn) me.patientCreateEncounterBtn.disable();
            if(me.patientSummaryBtn) me.patientSummaryBtn.disable();
            if(me.patientOpenVisitsBtn) me.patientOpenVisitsBtn.disable();
            if(me.patientCloseCurrEncounterBtn) me.patientCloseCurrEncounterBtn.disable();
            if(me.patientChargeBtn) me.patientChargeBtn.disable();
            if(me.patientCheckOutBtn) me.patientCheckOutBtn.disable();
            me.patientButtonSet();
        }
    }
}); 