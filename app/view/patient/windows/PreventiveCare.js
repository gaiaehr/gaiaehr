/*
 GaiaEHR (Electronic Health Records)
 PreventiveCare.js
 Copyright (C) 2012 Omar U. Rodriguez

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
Ext.define('App.view.patient.windows.PreventiveCare', {
	extend       : 'App.ux.window.Window',
	title        : i18n('preventive_care_window'),
	closeAction  : 'hide',
    height       : 550,
   	width        : 1000,
	bodyStyle    : 'background-color:#fff',
	modal        : true,
    layout       : 'fit',
	defaults     : {
		margin: 5
	},
	initComponent: function() {
		var me = this;

		me.patientPreventiveCare = Ext.create('App.store.patient.PreventiveCare', {
			groupField: 'type',
			sorters   : ['type'],
			autoSync  : true
		});

		me.grid  = Ext.create('App.ux.GridPanel', {
            store      : me.patientPreventiveCare,
			features: Ext.create('Ext.grid.feature.Grouping', {
					groupHeaderTpl   : i18n('type') + ': {name} ({rows.length} ' + i18n('item') + '{[values.rows.length > 1 ? "s" : ""]})',
					hideGroupedHeader: true,
				    startCollapsed: true
			}),
            columns    : [
	            {
		            header     : i18n('type'),
		            dataIndex: 'type',
		            width:200
	            },
                {
	                header     : i18n('description'),
                    dataIndex: 'description',
	                width: 200
                },
                {
	                header     : i18n('reason'),
	                dataIndex: 'reason',
	                flex:1

                }


            ],
			plugins: Ext.create('App.ux.grid.RowFormEditing', {
				autoCancel  : false,
				errorSummary: false,
				clicksToEdit: 1,

				formItems: [
					{
						title  : i18n('general'),
						xtype  : 'container',
						padding: 10,
						layout : 'vbox',
						items  : [
							{
								/**
								 * Line one
								 */
								xtype   : 'fieldcontainer',
								layout  : 'hbox',
								defaults: { margin: '0 10 5 0' },
								items   : [
									{
										xtype:'textfield',
										name:'reason',
										fieldLabel: i18n('reason'),
										width:585,
										labelWidth: 70,
										disabled:true,
										allowBlank:false,
										action:'reason'
									}

								]

							},
							{
								/**
								 * Line two
								 */
								xtype   : 'fieldcontainer',
								layout  : 'hbox',
								defaults: { margin: '0 10 5 0' },
								items   : [

									{
										xtype:'textfield',
										fieldLabel: i18n('observation'),
										name      : 'observation',
										width     : 250,
										labelWidth: 70,
										disabled:true,
										action:'observation'
									},
									{
										fieldLabel: i18n('date'),
										xtype:'datefield',
										disabled:true,
										action:'date',
										width     : 200,
										labelWidth: 40,
										format: globals['date_display_format'],
										name      : 'date'

									},
									{
										xtype:'checkboxfield',
										name : 'dismiss',
										fieldLabel : i18n('dismiss_alert'),
										enableKeyEvents: true,
										listeners:{
											scope:me,
											change:me.onChangeOption

										}
									},
									{
                                        xtype:'textfield',
                                        hidden:true,
                                        name:'eid',
                                        action:'eid'
                                    }

								]

							}
						]
					}

				]
			})


		});

		me.items = [ me.grid ];

//		me.listeners = {
//			scope: me,
//			show: me.onPreventiveCareWindowShow
//		};


		this.callParent(arguments);

	},
	onChangeOption: function(field,newValue){
		var me=this,
			reason=field.up('form').query('[action="reason"]')[0],
			date=field.up('form').query('[action="date"]')[0],
			eid=field.up('form').query('[action="eid"]')[0],
			observation=field.up('form').query('[action="observation"]')[0];
		eid.setValue(app.patient.eid);
		if(newValue){
			reason.setDisabled(false);
			date.setDisabled(false);
			observation.setDisabled(false);
		}else if(!newValue){
			reason.setDisabled(true);
			date.setDisabled(true);
			observation.setDisabled(true);

		}else{
			reason.setDisabled(true);
			date.setDisabled(true);
			observation.setDisabled(true);
		}



	},

    loadPatientPreventiveCare:function(){
        var me = this;
        this.patientPreventiveCare.load({
            scope:me,
            params: {
                pid: app.patient.pid
            },
            callback:function(records, operation, success){
                if(records.length > 0){
                    me.show();
                    return true;
                }else{
                    return false;
                }
            }
        });
    }

//	onPreventiveCareWindowShow: function() {
//	    this.patientPreventiveCare.load({params: {pid: app.patient.pid }});
//
//    }

});