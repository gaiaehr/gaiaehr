/**
 * This file is part of GaiaEHR.
 *
 * GaiaEHR is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Foobar is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Author: Ernesto J. Rodriguez (Certun, LLC.)
 * Date: 10/23/12
 * Time: 5:31 PM
 */
Ext.define('App.ux.form.AdvanceForm', {
    extend: 'Ext.AbstractPlugin',
    alias: 'plugin.advanceform',
    /**
     * @cfg {Boolean} syncAcl
     * Sync access control, true to allow the store to sync.
     */
    syncAcl: true,
    /**
     * @cfg {Boolean} autoSync
     * True to autosave the form every time values is change, Default to false.
     */
    autoSync: false,
    /**
     * True to add a tool component to the form panel. Default to true.
     * @cfg {Boolean} autoSyncTool
     */
    autoSyncTool:true,
    /**
     * @cfg {int} syncDelay
     * Autosave de delay to sync the form store. Default to 3000.
     */
    syncDelay: 3000,
    /**
     * @cfg {int} transition
     * Time of Fx background color transition. Default to 2000.
     */
    transition: 2000,
    /**
     * Init function
     * @param form
     */
    init: function(form){
        this.callParent(arguments);
        form.pugin = this;
        this.formPanel = form;
        this.formPanel.autoSync = this.autoSync;
        this.formPanel.on('beforerender', this.setFieldEvent, this);
        this.form = this.formPanel.getForm();
        this.form.loadRecord = this.loadRecord;
        if(this.autoSyncTool) this.addTool();
    },
    /**
     * Overrides the form basic loadRecord()
     * @param record
     * @return {*|Ext.form.Basic|Ext.form.Basic|Ext.form.Basic|Ext.form.Basic|Ext.form.Basic|Ext.form.Basic}
     */
    loadRecord: function(record){
        var form = this, formPanel = form.owner, plugin = this.owner.pugin, rec;
        form.isLoading = true;
        form._record = record;
        plugin.setFormFieldsClean(false);
        record.store.on('write', plugin.onStoreWrite, plugin);
        record.store.on('beforesync', function(store, operation){
            formPanel.fireEvent('beforesync', store, operation);
        }, plugin);
        record.store.on('update', function(store, operation){
            formPanel.fireEvent('update', store, operation);
        }, plugin);
        form.setValues(record.data);
        formPanel.fireEvent('recordloaded', form, record);
        form.isLoading = false;
        return form;
    },
    /**
     * After store write clean form fields and fire write event on form
     * @param store
     * @param operation
     */
    onStoreWrite: function(store, operation){
        this.setFormFieldsClean(this.transition);
        this.formPanel.fireEvent('write', store, operation);
        app.msg('Sweet!', 'Record Saved');
        delete this.bufferSyncFormFn;
    },
    /**
     * Set on keyup or handler based on xtype
     * @param form
     */
    setFieldEvent: function(form){
        var fields = form.getForm().getFields().items;
        for(var i = 0; i < fields.length; i++){
            if(fields[i].xtype == 'textfield' || fields[i].xtype == 'textareafield'){
                fields[i].enableKeyEvents = true;
                fields[i].on('keyup', this.setFieldCondition, this);
            }else if(fields[i].xtype == 'radiofield' || fields[i].xtype == 'mitos.checkbox' || fields[i].xtype == 'checkbox'){
                fields[i].scope = this;
                fields[i].handler = this.setFieldCondition;
            }else if(fields[i].xtype == 'datefield'){
                fields[i].on('select', this.setFieldCondition, this);
            }else{
                fields[i].on('select', this.setFieldCondition, this);
            }
        }
    },
    /**
     * Set field condition dirty or clean based on field getSubmitValue()
     * @param field
     */
    setFieldCondition: function(field){
        var me = this, record = me.form.getRecord(), store = record.store, obj = {}, valueChanged, el = me.getFieldEl(field);
        if((!me.form.isLoading && field.xtype != 'radiofield') || (!me.form.isLoading && field.xtype == 'radiofield' && field.checked)){
            obj[field.name] = field.getSubmitValue();
            record.set(obj);
            valueChanged = (Object.getOwnPropertyNames(record.getChanges()).length !== 0);
            if(valueChanged === true){
                me.setFieldDirty(field, el, true, me.transition);
            }else{
                me.setFieldDirty(field, el, false, me.transition);
            }
            if(this.formPanel.autoSync && this.syncAcl){
                if(typeof me.bufferSyncFormFn == 'undefined'){
                    me.bufferSyncFormFn = Ext.Function.createBuffered(function(){
                        store.sync();
                    }, me.syncDelay);
                    me.bufferSyncFormFn();
                }else{
                    if(valueChanged === true){
                        me.bufferSyncFormFn();
                    }else{
                        me.setFormFieldsClean(me.transition);
                        delete me.bufferSyncFormFn;
                    }
                }
            }
        }
    },
    /**
     * Set field background if dirty == true
     * @param field
     * @param el
     * @param dirty
     * @param transition
     */
    setFieldDirty: function(field, el, dirty, transition){
        transition = Ext.isNumber(transition) ? transition : 0;
        if((field.el.hasChanged && !dirty) || (!field.el.hasChanged && dirty)){
            field.el.hasChanged = dirty;
            Ext.create('Ext.fx.Animator', {
                target: el,
                duration: transition, // 10 seconds
                keyframes: {
                    0: {
                        backgroundColor: dirty ? 'FFFFFF' : 'FFDDDD'
                    },
                    100: {
                        backgroundColor: dirty ? 'FFDDDD' : 'FFFFFF'
                    }
                },
                listeners: {
                    keyframe: function(fx, keyframe){
                        if(keyframe == 1){
                            if(dirty){
                                el.setStyle({'background-image': 'none'});
                            }else{
                                Ext.Function.defer(function(){
                                    el.setStyle({'background-image': null});
                                }, transition - 400);
                            }
                        }
                    }
                }
            });
        }
    },
    /**
     * Get the field main element to change background.
     * Some fields are managed different.
     * @param field
     * @return {*}
     */
    getFieldEl: function(field){
        if(field.xtype == 'textfield' || field.xtype == 'textareafield'){
            return field.inputEl;
        }else if(field.xtype == 'radiofield'){
            return field.ownerCt.el;
        }else if(field.xtype == 'mitos.checkbox' || field.xtype == 'checkbox'){
            return field.el;
        }else{
            return field.el; // leave this for now
        }
    },
    /**
     * This will set all the fields that has change
     */
    setFormFieldsClean: function(transition){
        var me = this, fields = me.form.getFields().items, el;
        for(var i = 0; i < fields.length; i++){
            el = me.getFieldEl(fields[i]);
            if(typeof fields[i].el != 'undefined' && fields[i].el.hasChanged){
                me.setFieldDirty(fields[i], el, false, transition);
            }
        }
    },

    addTool:function(){
        var me = this,
            bar = me.formPanel.getDockedItems()[0],
            cls = me.formPanel.autoSync ? 'autosave' : '';

        if(bar && me.autoSyncTool){
            bar.insert(0, Ext.create('Ext.panel.Tool',{
                type:'save',
                cls:cls,
                tooltip: 'Autosave',
                handler: function(event, toolEl, panel, tool){
                    me.formPanel.autoSync = !me.formPanel.autoSync;
                    if(me.formPanel.autoSync){
                        tool.addCls('autosave');
                    }else{
                        tool.removeCls('autosave');
                    }
                    app.msg('Sweet!','AutoSave is ' + (me.formPanel.autoSync ? 'On' : 'Off'));
                }
            }));
        }
    }
});