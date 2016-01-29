Ext.define('App.ux.grid.AreasViewDropZone', {
	extend: 'Ext.grid.ViewDropZone',

	handleNodeDrop: function(data, record, position){
		var view = this.view,
			store = view.getStore(),
			index, records, i, len;
		/**
		 * fixed to handle the patient button data
		 */
		if(!data.patient){

			if(data.copy){
				records = data.records;
				data.records = [];
				for(i = 0, len = records.length; i < len; i++){
					data.records.push(records[i].copy());
				}
			}else{
				data.view.store.remove(data.records, data.view === view);
			}
		}

		if (record && position) {
			index = store.indexOf(record);

			// 'after', or undefined (meaning a drop at index -1 on an empty View)...
			if (position !== 'before') {
				index++;
			}
			store.insert(index, data.records);
		}
		// No position specified - append.
		else {
			store.add(data.records);
		}

//		view.getSelectionModel().select(data.records);
	},

	notifyEnter : function(dd, e, data){
		Ext.get(data.ddel.id).update(_('drop_patient_to_new_area'));
		this.callParent(arguments);
	},

	// While over a target node, return the default drop allowed class which
	// places a "tick" icon into the drag proxy.
	notifyOut : function(dd, e, data){
		Ext.get(data.ddel.id).update(_('drag_patient_to_new_area'));
		this.callParent(arguments);
	}

});


Ext.define('App.ux.grid.AreasDragDrop', {
	extend: 'Ext.grid.plugin.DragDrop',
	alias: 'plugin.areasgridviewdragdrop',

	onViewRender : function(view) {
		var me = this,
			scrollEl;

		if (me.enableDrag) {
			if (me.containerScroll) {
				scrollEl = view.getEl();
			}

			me.dragZone = new Ext.view.DragZone({
				view: view,
				ddGroup: me.dragGroup || me.ddGroup,
				dragText: me.dragText,
				containerScroll: me.containerScroll,
				scrollEl: scrollEl
			});
		}

		if (me.enableDrop) {
			me.dropZone = new App.ux.grid.AreasViewDropZone({
				view: view,
				ddGroup: me.dropGroup || me.ddGroup
			});
		}
	}
});