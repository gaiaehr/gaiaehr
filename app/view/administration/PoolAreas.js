/**
 * Users.ejs.php
 * Description: Users Screen
 * v0.0.4
 *
 * Author: Ernesto J Rodriguez (Certun)
 * Modified: n/a
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace User.getUsers
 * @namespace User.addUser
 * @namespace User.updateUser
 * @namespace User.chechPasswordHistory
 */
Ext.define('App.view.administration.PoolAreas', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelAdminPoolAreas',
	pageTitle    : 'Floor Plan Editor',
	initComponent: function() {

		var me = this;

		me.mainContainer = Ext.create('Ext.panel.Panel',{
			bodyCls:'floorPlan',
			tbar:[
				{
					text:'Add Zone',
					action:'newZone',
					scope:me,
					handler:me.newStretcher
				}
			]
		});

		me.pageBody = [ me.mainContainer ];
		me.callParent(arguments);
	},

	newStretcher:function(btn){
		var me = this;

		btn.up('panel').add(
			Ext.create('Ext.Button', {
			    text: 'New Zone',
				draggable:true,
				scale:'medium',
				iconCls:'icoStretcher',
			    handler: function() {
			        //alert('You clicked the button!');
			    }
			})
		)
	},

	initializePoolAreaDragZone: function(btn) {
		btn.dragZone = Ext.create('Ext.dd.DragZone', btn.getEl(), {
			ddGroup    : 'ZoneAreas',
			// On receipt of a mousedown event, see if it is within a draggable element.
			// Return a drag data object if so. The data object can contain arbitrary application
			// data, but it should also contain a DOM element in the ddel property to provide
			// a proxy to drag.
			getDragData: function() {
				var sourceEl = btn.el.dom, d;
				if(sourceEl) {
					d = sourceEl.cloneNode(true);
					d.id = Ext.id();
					return btn.dragData = {
						copy    : true,
						sourceEl: sourceEl,
						repairXY: Ext.fly(sourceEl).getXY(),
						ddel    : d,
						records : [ btn.data ],
						patient : true
					};
				}
			},
			// Provide coordinates for the proxy to slide back to on failed drag.
			// This is the original XY coordinates of the draggable element.
			getRepairXY: function() {
				app.goBack();
				return this.dragData.repairXY;
			}
		});
	},

	initializePoolAreaDropZone:function(panel){
		panel.dropZone = Ext.create('Ext.dd.DropZone', panel.getEl(), {
			ddGroup   : 'ZoneAreas',
			notifyOver: function() {
				return Ext.dd.DropZone.prototype.dropAllowed;
			},
			notifyDrop: function(dd, e, data) {
				say(dd);
				say(e);
				say(data);
			}
		});
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		callback(true);
	}
});