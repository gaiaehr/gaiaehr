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
Ext.define('App.view.patientfile.PreventiveCareWindow', {
	extend       : 'Ext.window.Window',
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

		me.patientPreventiveCare = Ext.create('App.store.patientfile.PreventiveCare', {
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
	                flex:1
                },
                {
	                header     : 'Reason',
	                dataIndex: 'reason',
	                flex:1,
	                editor:{
		                xtype:'textfield',
		                disabled:true,
		                action:'reason'
	                }

                },
                {

	                header     : 'Dismiss',
	                dataIndex: 'dismiss',
	                editor:{
		                xtype:'checkboxfield',
		                enableKeyEvents: true,
		                listeners:{
			                scope:me,
			                change:me.onChangeOption

		                }

	                }
                }


            ],
			plugins: Ext.create('Ext.grid.plugin.RowEditing', {
				autoCancel  : true,
				errorSummary: false,
				clicksToEdit: 1

			})


		});

		me.items = [ me.grid ];

		me.listeners = {
			scope: me,
			show: me.onPreventiveCareWindowShow
		};


		this.callParent(arguments);

	},
	onChangeOption: function(field,newValue){
		var me=this,
			fieldLabel=field.up('form').query('[action="reason"]')[0];

		if(newValue){
			fieldLabel.setDisabled(false);
		}else if(!newValue){
			fieldLabel.setDisabled(true);

		}else{
			fieldLabel.setDisabled(true);
		}

	},
	onPreventiveCareWindowShow: function() {
	    this.patientPreventiveCare.load({params: {pid: app.currPatient.pid }});

    }

});