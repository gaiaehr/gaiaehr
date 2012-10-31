/*
 GaiaEHR (Electronic Health Records)
 Overrides.js
 UX
 Copyright (C) 2012 Ernesto Rodriguez

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
    inputValue: '1',
    uncheckedValue: '0'
});
Ext.override(Ext.form.field.Date, {
    format: 'Y-m-d'
});
Ext.override(Ext.grid.Panel, {
    emptyText: 'Nothing to Display'
});
Ext.override(Ext.grid.plugin.Editing, {
    cancelEdit: function(){
        var me = this;
        me.editing = false;
        me.fireEvent('canceledit', me, me.context);
        me.grid.store.rejectChanges();
    }
});
Ext.override(Ext.grid.RowEditor, {
    completeEdit: function(){
        var me = this, form = me.getForm();
        if(!form.isValid()){
            return false;
        }else{
            form.updateRecord(me.context.record);
            form._record.store.sync({
                callback: function(){
                    me.fireEvent('sync', me, me.context);
                }
            });
            me.hide();
            return true;
        }
    }
});
Ext.override(Ext.container.Container, {
    setAutoSyncFormEvent: function(field){
        if(field.xtype == 'textfield' || field.xtype == 'textareafield'){
            field.on('keyup', this.autoSyncForm, this);
        }else if(field.xtype == 'radiofield' || field.xtype == 'mitos.checkbox' || field.xtype == 'checkbox'){
            field.scope = this;
            field.handler = this.autoSyncForm;
        }else{
            //field.on('select', this.autoSyncForm, this);
        }
    },
    autoSyncForm: function(field){
        var me = this, panel = field.up('form'), form = panel.getForm(), record = form.getRecord(), store = record.store, hasChanged;
        if(typeof me.isLoading == 'undefined' || !me.isLoading){
            record.set(form.getValues());
            hasChanged = (Object.getOwnPropertyNames(record.getChanges()).length !== 0);
            if(hasChanged === true){
                me.setFieldDirty(field);
            }else{
                me.setFieldClean(field);
            }
            if(typeof me.bufferSyncFormFn == 'undefined'){
                me.bufferSyncFormFn = Ext.Function.createBuffered(function(){
                    if(hasChanged){
                        store.sync({
                            callback: function(){
                                panel.fireEvent('formstoresynced', store, record, record.getChanges());
                                me.setFormFieldsClean(form);
                                me.msg('Sweet!', 'Records synced with server');
                                delete me.bufferSyncFormFn;
                            }
                        });
                    }else{
                        me.setFormFieldsClean(form);
                        delete me.bufferSyncFormFn;
                    }
                }, 3000);
            }else{
                me.bufferSyncFormFn();
            }
        }
    },
    setFieldDirty: function(field){
        var duration = 2000, el;
        if(field.xtype == 'textfield' || field.xtype == 'textareafield'){
            el = field.inputEl;
        }else if(field.xtype == 'radiofield'){
            el = field.ownerCt.el;
        }else if(field.xtype == 'mitos.checkbox' || field.xtype == 'checkbox'){
            el = field.el;
        }else{
            el = field.el;
        }
        if(!field.hasChanged){
            field.hasChanged = true;
            Ext.create('Ext.fx.Animator', {
                target: el,
                duration: duration, // 10 seconds
                keyframes: {
                    0: {
                        backgroundColor: 'FFFFFF'
                    },
                    100: {
                        backgroundColor: 'ffdddd'
                    }
                },
                listeners: {
                    keyframe: function(fx, keyframe){
                        if(keyframe == 1){
                            el.setStyle({
                                'background-image': 'none'
                            });
                        }
                    }
                }
            });
        }
    },
    setFieldClean: function(field){
        var duration = 2000, el;
        if(field.xtype == 'textfield' || field.xtype == 'textareafield'){
            el = field.inputEl;
        }else if(field.xtype == 'radiofield'){
            el = field.ownerCt.el;
        }else if(field.xtype == 'mitos.checkbox' || field.xtype == 'checkbox'){
            el = field.el;
        }else{
            el = field.el;
        }
        field.hasChanged = false;
        Ext.create('Ext.fx.Animator', {
            target: el,
            duration: duration, // 10 seconds
            keyframes: {
                0: {
                    backgroundColor: 'ffdddd'
                },
                100: {
                    backgroundColor: 'FFFFFF'
                }
            },
            listeners: {
                keyframe: function(fx, keyframe){
                    if(keyframe == 1){
                        Ext.Function.defer(function(){
                            el.setStyle({
                                'background-image': null
                            });
                        }, duration - 400);
                    }
                }
            }
        });
    },
    /**
     * this will set all the fields that has change
     * @param form
     */
    setFormFieldsClean: function(form){
        var me = this, fields = form.getFields().items;
        for(var i = 0; i < fields.length; i++){
            if(fields[i].hasChanged){
                me.setFieldClean(fields[i]);
            }
        }
    },
    setReadOnly: function(readOnly){
        var forms = this.query('form');
        for(var j = 0; j < forms.length; j++){
            var form = forms[j], items;
            if(form.readOnly != readOnly){
                form.readOnly = readOnly;
                items = form.getForm().getFields().items;
                for(var k = 0; k < items.length; k++){
                    items[k].setReadOnly(readOnly);
                }
            }
        }
        return readOnly;
    },
    setButtonsDisabled: function(buttons, disabled){
        var disable = disabled || app.patient.readOnly;
        for(var i = 0; i < buttons.length; i++){
            var btn = buttons[i];
            if(btn.disabled != disable){
                btn.disabled = disable;
                btn.setDisabled(disable)
            }
        }
    },
    goBack: function(){
        app.goBack();
    },
    checkIfCurrPatient: function(){
        return app.getCurrPatient();
    },
    patientInfoAlert: function(){
        var patient = app.getCurrPatient();
        Ext.Msg.alert(i18n('status'), i18n('patient') + ': ' + patient.name + ' (' + patient.pid + ')');
    },
    currPatientError: function(msg){
        Ext.Msg.show({
            title: 'Oops! ' + i18n('no_patient_selected'),
            msg: Ext.isString(msg) ? msg : i18n('select_patient_patient_live_search'),
            scope: this,
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.ERROR,
            fn: function(){
                this.goBack();
            }
        });
    },
    getFormItems: function(formPanel, formToRender, callback){
        if(formPanel){
            formPanel.removeAll();
            FormLayoutEngine.getFields({
                formToRender: formToRender
            }, function(provider, response){
                var items = eval(response.result), form;
                form = formPanel.add(items);
                if(typeof callback == 'function'){
                    callback(formPanel, items, true);
                }
                return form;
            });
        }
    },
    boolRenderer: function(val){
        if(val == '1' || val == true || val == 'true'){
            return '<img style="padding-left: 13px" src="resources/images/icons/yes.gif" />';
        }else if(val == '0' || val == false || val == 'false'){
            return '<img style="padding-left: 13px" src="resources/images/icons/no.gif" />';
        }
        return val;
    },
    alertRenderer: function(val){
        if(val == '1' || val == true || val == 'true'){
            return '<img style="padding-left: 13px" src="resources/images/icons/no.gif" />';
        }else if(val == '0' || val == false || val == 'false'){
            return '<img style="padding-left: 13px" src="resources/images/icons/yes.gif" />';
        }
        return val;
    },
    warnRenderer: function(val, metaData, record){
        var toolTip = record.data.warningMsg ? record.data.warningMsg : '';
        if(val == '1' || val == true || val == 'true'){
            return '<img src="resources/images/icons/icoImportant.png" ' + toolTip + ' />';
        }
        return '';
    },
    onExpandRemoveMask: function(cmb){
        cmb.picker.loadMask.destroy()
    },
    strToLowerUnderscores: function(str){
        return str.toLowerCase().replace(/ /gi, "_");
    },
    getCurrPatient: function(){
        return app.getCurrPatient();
    },
    getApp: function(){
        return app.getApp();
    },
    msg: function(title, format, warning){
        app.msg(title, format, warning)
    },
    alert: function(msg, icon){
        app.alert(msg, icon)
    },
    passwordVerificationWin: function(callback){
        var msg = Ext.Msg.prompt(i18n('password_verification'), i18n('please_enter_your_password') + ':', function(btn, password){
            callback(btn, password);
        });
        var f = msg.textField.getInputId();
        document.getElementById(f).type = 'password';
        return msg;
    }
});
Ext.override(Ext.grid.ViewDropZone, {

    handleNodeDrop: function(data, record, position){
        var view = this.view, store = view.getStore(), index, records, i, len;
        /**
         * fixed to handle the patient button data
         */
        if(!data.patient){
            if(data.copy){
                records = data.records;
                data.records = [];
                for(i = 0, len = records.length; i < len; i++){
                    data.records.push(records[i].copy(records[i].getId()));
                }
            }else{
                data.view.store.remove(data.records, data.view === view);
            }
        }
        index = store.indexOf(record);
        // 'after', or undefined (meaning a drop at index -1 on an empty View)...
        if(position !== 'before'){
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
Ext.override(Ext.view.AbstractView, {
    onRender: function(){
        var me = this;
        me.callOverridden(arguments);
        if(me.loadMask && Ext.isObject(me.store)){
            me.setMaskBind(me.store);
        }
    }
});
//Ext.override(Ext.data.Field, {
//	useNull: true
//
//});
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