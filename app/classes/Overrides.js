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
    format : 'Y-m-d'
});

Ext.override(Ext.grid.ViewDropZone, {

    handleNodeDrop : function(data, record, position) {
        var view = this.view,
            store = view.getStore(),
            index, records, i, len;
        /**
         * fixed to handle the patient button data
         */
        if(!data.patient){
            if (data.copy) {
                records = data.records;
                data.records = [];
                for (i = 0, len = records.length; i < len; i++) {
                    data.records.push(records[i].copy(records[i].getId()));
                }
            } else {
                data.view.store.remove(data.records, data.view === view);
            }
        }

        index = store.indexOf(record);

        // 'after', or undefined (meaning a drop at index -1 on an empty View)...
        if (position !== 'before') {
            index++;
        }
        store.insert(index, data.records);
        view.getSelectionModel().select(data.records);
    }
});

Ext.override(Ext.layout.ContextItem, {

    setHeight: function (height, dirty /*, private {Boolean} force */) {
        var me = this,
            comp = me.target,
            frameBody, frameInfo, padding;

        if (isNaN(height)) {
            return;
        }

        if (height < 0) {
            height = 0;
        }
        if (!me.wrapsComponent) {
            if (!me.setProp('height', height, dirty)) {
                return NaN;
            }
        } else {
            height = Ext.Number.constrain(height, comp.minHeight || 0, comp.maxHeight);
            if (!me.setProp('height', height, dirty)) {
                return NaN;
            }

            frameBody = me.frameBodyContext;
            if (frameBody){
                frameInfo = me.getFrameInfo();
                frameBody.setHeight(height - frameInfo.height, dirty);
            }
        }

        return height;
    },

    setWidth: function (width, dirty /*, private {Boolean} force */) {
        var me = this,
            comp = me.target,
            frameBody, frameInfo, padding;

        if (isNaN(width)) {
            return;
        }

        if (width < 0) {
            width = 0;
        }
        if (!me.wrapsComponent) {
            if (!me.setProp('width', width, dirty)) {
                return NaN;
            }
        } else {
            width = Ext.Number.constrain(width, comp.minWidth || 0, comp.maxWidth);
            if (!me.setProp('width', width, dirty)) {
                return NaN;
            }

            //if ((frameBody = me.target.frameBody) && (frameBody = me.getEl(frameBody))){
            frameBody = me.frameBodyContext;
            if (frameBody) {
                frameInfo = me.getFrameInfo();
                frameBody.setWidth(width - frameInfo.width, dirty);
            }

            /*if (owner.frameMC) {
                frameContext = ownerContext.frameContext ||
                        (ownerContext.frameContext = ownerContext.getEl('frameMC'));
                width += (frameContext.paddingInfo || frameContext.getPaddingInfo()).width;
            }*/
        }

        return width;
    }

});

Ext.override(Ext.view.AbstractView, {
    onRender: function() {
        var me = this;
        me.callOverridden(arguments);
        if (me.loadMask && Ext.isObject(me.store)) {
            me.setMaskBind(me.store);
        }
    }
});

Ext.override(Ext.data.Field, {
	useNull: true

});