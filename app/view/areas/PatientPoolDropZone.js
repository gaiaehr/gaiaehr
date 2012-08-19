/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 3/16/12
 * Time: 9:09 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.view.areas.PatientPoolDropZone', {
	id       : 'panelPoolArea',
	extend   : 'App.classes.RenderPanel',
	pageTitle: 'Patient Pool Areas',
	initComponent: function() {
		var me = this;
		me.pageBody = Ext.create('Ext.container.Container', {
			defaults: {
				flex  : 1,
				margin: 5,
				frame : false
			},
			layout  : {
				type : 'hbox',
				align: 'stretch'
			}
		});
		me.listeners = {
			beforerender: me.getPoolAreas
		};
		me.callParent(arguments);
	},

	onPatientDrop: function(node, data, overModel, dropPosition, eOpts) {
		var name = (data.records[0].data) ? data.records[0].data.name : data.records[0].name,
			pid = (data.records[0].data) ? data.records[0].data.pid : data.records[0].pid,
			params;
		app.msg('Sweet!', name + ' sent to ' + this.panel.title);
		params = {
			pid   : pid,
			sendTo: this.panel.action
		};

		PoolArea.sendPatientToPoolArea(params, function() {
			app.patientUnset();
			Ext.getCmp('panelPoolArea').reloadStores();
		});


	},

	getPoolAreas: function() {
		var me = this,
			panel = me.getPageBody().down('container'),
            areas;
		me.stores = [];
		PoolArea.getActivePoolAreas(function(provider, response) {
            areas = response.result;
            for(var i=0; i < areas.length; i++ ){
				var store = Ext.create('Ext.data.Store', {
					model: 'App.model.areas.PoolDropAreas',
					proxy: {
						type       : 'direct',
						api        : {
							read: PoolArea.getPoolAreaPatients
						},
						extraParams: {
							area_id: areas[i].id
						}
					}
				});
				me.stores.push(store);
				panel.add({
					xtype      : 'grid',
					title      : areas[i].title,
					action     : areas[i].id,
					store      : store,
					floorPlanId: areas[i].floor_plan_id,
					columns    : [
						{
							header   : 'Record #',
							width    : 100,
							dataIndex: 'pid'
						},
						{
							header   : 'Patien Name',
							flex     : 1,
							dataIndex: 'name'
						}
					],
					viewConfig: {
						loadMask : false,
						plugins  : {
							ptype    : 'gridviewdragdrop',
							dragGroup: 'patientPoolAreas',
							dropGroup: 'patientPoolAreas'
						},
						listeners: {
							//scope:me,
							drop: me.onPatientDrop
						}
					},
					listeners : {
						scope       : me,
						itemdblclick: me.onPatientDblClick
					}
				})

			}
		});
	},

	onPatientDblClick: function(store, record) {
		var data = record.data;
		// TODO: set priority
		app.setCurrPatient(data.pid, data.name, '', function() {
			app.openPatientSummary();
		});
	},

	reloadStores: function() {
        var stores = this.stores;
        for(var i=0; i < stores.length; i++ ){
            stores[i].load();
        }
	},

	onActive: function(callback) {
		this.reloadStores();
		callback(true);
	}

});