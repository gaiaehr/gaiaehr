/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/15/12
 * Time: 4:30 PM
 *
 * @namespace Immunization.getImmunizationsList
 * @namespace Immunization.getPatientImmunizations
 * @namespace Immunization.addPatientImmunization
 */
Ext.define('App.view.patient.windows.PreventiveCare', {
	extend       : 'App.classes.window.Window',
	title        : 'Preventive Care Window',
	closeAction  : 'hide',
	height       : 350,
	width        : 700,
	bodyStyle    : 'background-color:#fff',
	modal        : true,
    layout:'fit',
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

		me.grid  = Ext.create('App.classes.GridPanel', {
			title      : 'Suggestions',
            store      : me.patientPreventiveCare,
			features: Ext.create('Ext.grid.feature.Grouping', {
					groupHeaderTpl   : 'Type: {name} ({rows.length} Item{[values.rows.length > 1 ? "s" : ""]})',
					hideGroupedHeader: true,
				    startCollapsed: true
			}),
            columns    : [
	            {
		            header     : 'type',
		            dataIndex: 'type',
		            width:200
	            },
                {
	                header     : 'Description',
                    dataIndex: 'description',
	                width: 200
                },
                {
	                header     : 'Reason',
	                dataIndex: 'reason',
	                flex:1

                }


            ],
			plugins: Ext.create('App.classes.grid.RowFormEditing', {
				autoCancel  : false,
				errorSummary: false,
				clicksToEdit: 1,

				formItems: [
					{
						title  : 'general',
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
										fieldLabel:'Reason',
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
										fieldLabel: 'Observation',
										name      : 'observation',
										width     : 250,
										labelWidth: 70,
										disabled:true,
										action:'observation'
									},
									{
										fieldLabel: 'Date',
										xtype:'datefield',
										disabled:true,
										action:'date',
										width     : 200,
										labelWidth: 40,
										format    : 'Y-m-d',
										name      : 'date'

									},
									{
										xtype:'checkboxfield',
										name : 'dismiss',
										fieldLabel : 'Dismiss Alert?',
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
		eid.setValue(app.currEncounterId);
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
                pid: app.currPatient.pid
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
//	    this.patientPreventiveCare.load({params: {pid: app.currPatient.pid }});
//
//    }

});