//Ext.override(Ext.grid.RowEditor, {
//	loadRecord: function(record) {
//		var me = this,
//			form = me.getForm(),
//			valid = form.isValid();
//
//		form.loadRecord(record);
//		if(me.errorSummary) {
//			me[valid ? 'hideToolTip' : 'showToolTip']();
//		}
//
//		Ext.Array.forEach(me.query('>displayfield'), function(field) {
//			me.renderColumnData(field, record);
//		}, me);
//	}
//});
Ext.override(Ext.form.field.Checkbox, {
	inputValue    : '1',
	uncheckedValue: '0'
});

Ext.override(Ext.form.field.Date, {
	format: 'Y-m-d'
});

Ext.override(Ext.grid.Panel, {
    emptyText: 'Nothing to Display'
});

Ext.override(Ext.grid.ViewDropZone, {

	handleNodeDrop: function(data, record, position) {
		var view = this.view,
			store = view.getStore(),
			index, records, i, len;
		/**
		 * fixed to handle the patient button data
		 */
		if(!data.patient) {
			if(data.copy) {
				records = data.records;
				data.records = [];
				for(i = 0, len = records.length; i < len; i++) {
					data.records.push(records[i].copy(records[i].getId()));
				}
			} else {
				data.view.store.remove(data.records, data.view === view);
			}
		}

		index = store.indexOf(record);

		// 'after', or undefined (meaning a drop at index -1 on an empty View)...
		if(position !== 'before') {
			index++;
		}
		store.insert(index, data.records);
		view.getSelectionModel().select(data.records);
	}

//	notifyEnter: function(dd, e, data) {
//		var me = this;
//		me.goToFloorPlanFn = new Ext.util.DelayedTask(function(){
//			if(me.view.panel.floorPlanId){
//				app.navigateTo('panelAreaFloorPlan', function(){
//					app.currCardCmp.setFloorPlan(me.view.panel.floorPlanId);
//					me.notifyOut();
//					return me.dropNotAllowed
//				});
//			}
//		});
//		me.goToFloorPlanFn.delay(2000);
//		return me.dropAllowed;
//	},
//
//	// Moved out of the DropZone without dropping.
//	// Remove drop position indicator
//	notifyOut  : function(node, dragZone, e, data) {
//		var me = this;
//		me.goToFloorPlanFn.cancel();
//		me.callParent(arguments);
//		delete me.overRecord;
//		delete me.currentPosition;
//		if(me.indicator) {
//			me.indicator.hide();
//		}
//	},
//
//	notifyDrop: function(dd, e, data) {
//		var me = this;
//		me.goToFloorPlanFn.cancel();
//		if(me.lastOverNode) {
//			me.onNodeOut(this.lastOverNode, dd, e, data);
//			me.lastOverNode = null;
//		}
//		var n = me.getTargetFromEvent(e);
//		return n ? me.onNodeDrop(n, dd, e, data) : me.onContainerDrop(dd, e, data);
//	}



});
//
//Ext.override(Ext.layout.ContextItem, {
//
//	setHeight: function(height, dirty /*, private {Boolean} force */) {
//		var me = this,
//			comp = me.target,
//			frameBody, frameInfo, padding;
//
//		if(isNaN(height)) {
//			return;
//		}
//
//		if(height < 0) {
//			height = 0;
//		}
//		if(!me.wrapsComponent) {
//			if(!me.setProp('height', height, dirty)) {
//				return NaN;
//			}
//		} else {
//			height = Ext.Number.constrain(height, comp.minHeight || 0, comp.maxHeight);
//			if(!me.setProp('height', height, dirty)) {
//				return NaN;
//			}
//
//			frameBody = me.frameBodyContext;
//			if(frameBody) {
//				frameInfo = me.getFrameInfo();
//				frameBody.setHeight(height - frameInfo.height, dirty);
//			}
//		}
//
//		return height;
//	},
//
//	setWidth: function(width, dirty /*, private {Boolean} force */) {
//		var me = this,
//			comp = me.target,
//			frameBody, frameInfo, padding;
//
//		if(isNaN(width)) {
//			return;
//		}
//
//		if(width < 0) {
//			width = 0;
//		}
//		if(!me.wrapsComponent) {
//			if(!me.setProp('width', width, dirty)) {
//				return NaN;
//			}
//		} else {
//			width = Ext.Number.constrain(width, comp.minWidth || 0, comp.maxWidth);
//			if(!me.setProp('width', width, dirty)) {
//				return NaN;
//			}
//
//			//if ((frameBody = me.target.frameBody) && (frameBody = me.getEl(frameBody))){
//			frameBody = me.frameBodyContext;
//			if(frameBody) {
//				frameInfo = me.getFrameInfo();
//				frameBody.setWidth(width - frameInfo.width, dirty);
//			}
//
//			/*if (owner.frameMC) {
//			 frameContext = ownerContext.frameContext ||
//			 (ownerContext.frameContext = ownerContext.getEl('frameMC'));
//			 width += (frameContext.paddingInfo || frameContext.getPaddingInfo()).width;
//			 }*/
//		}
//
//		return width;
//	}
//
//});

Ext.override(Ext.view.AbstractView, {
	onRender: function() {
		var me = this;
		me.callOverridden(arguments);
		if(me.loadMask && Ext.isObject(me.store)) {
			me.setMaskBind(me.store);
		}
	}
});

Ext.override(Ext.data.Field, {
	useNull: true

});
//Ext.override(Ext.view.DropZone, {
//	onContainerOver : function(dd, e, data) {
//     var me = this,
//         view = me.view,
//         count = view.store.getCount();
//
//     // There are records, so position after the last one
//     if (count) {
//         me.positionIndicator(view.getNode(count - 1), data, e);
//     }
//
//     // No records, position the indicator at the top
//     else {
//         delete me.overRecord;
//         delete me.currentPosition;
//         me.getIndicator().setWidth(Ext.fly(view.el).getWidth()).showAt(0, 0);
//         me.valid = true;
//     }
//
//		var task = new Ext.util.DelayedTask(function(){
//		    app.navigateTo('panelAreaFloorPlan');
//		    if (me.indicator) {
//		        me.indicator.hide();
//		    }
//		}).delay(3000);
//
//     return me.dropAllowed;
// }
//
//});