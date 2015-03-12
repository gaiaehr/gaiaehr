/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.override(Ext.data.proxy.Server, {
    // remoteGroup default to true
    remoteGroup: true,
    encodeFilters: function(filters) {
        var min = [],
            length = filters.length,
            i = 0;

        for (; i < length; i++) {
            min[i] = {
                property: filters[i].property,
                operator: filters[i].operator || '=',
                value   : filters[i].value
            };
        }
        return this.applyEncoding(min);
    },
    getParams: function(operation) {
        var me = this,
            params = {},
            isDef = Ext.isDefined,
            groupers = operation.groupers,
            sorters = operation.sorters,
            filters = operation.filters,
            page = operation.page,
            start = operation.start,
            limit = operation.limit,
            simpleSortMode = me.simpleSortMode,
            simpleGroupMode = me.simpleGroupMode,
            pageParam = me.pageParam,
            startParam = me.startParam,
            limitParam = me.limitParam,
            groupParam = me.groupParam,
            groupDirectionParam = me.groupDirectionParam,
            sortParam = me.sortParam,
            filterParam = me.filterParam,
            directionParam = me.directionParam,
            hasGroups, index;

        if (pageParam && isDef(page)) {
            params[pageParam] = page;
        }

        if (startParam && isDef(start)) {
            params[startParam] = start;
        }

        if (limitParam && isDef(limit)) {
            params[limitParam] = limit;
        }

        // me.remoteGroup added at the end to force remoteGroupe property
        hasGroups = groupParam && groupers && groupers.length > 0 && me.remoteGroup;
        if (hasGroups) {
            // Grouper is a subclass of sorter, so we can just use the sorter method
            if (simpleGroupMode) {
                params[groupParam] = groupers[0].property;
                params[groupDirectionParam] = groupers[0].direction || 'ASC';
            } else {
                params[groupParam] = me.encodeSorters(groupers);
            }
        }

        if (sortParam && sorters && sorters.length > 0) {
            if (simpleSortMode) {
                index = 0;
                // Group will be included in sorters, so grab the next one
                if (sorters.length > 1 && hasGroups) {
                    index = 1;
                }
                params[sortParam] = sorters[index].property;
                params[directionParam] = sorters[index].direction;
            } else {
                params[sortParam] = me.encodeSorters(sorters);
            }

        }

        if (filterParam && filters && filters.length > 0) {
            params[filterParam] = me.encodeFilters(filters);
        }

        return params;
    },

	buildRequest: function(operation) {
		var me = this,
			params = Ext.applyIf(operation.params || {}, me.extraParams || {}),
			pk = me.getModel().prototype.idProperty || 'id',
			request;

		params = Ext.applyIf(params, me.getParams(operation));
		if (operation.id !== undefined && params.id === undefined) {
			params[pk] = operation.id;
		}
		request = new Ext.data.Request({
			params : params,
			action : operation.action,
			records : operation.records,
			operation: operation,
			url : operation.url,
			proxy: me
		});
		request.url = me.buildUrl(request);
		operation.request = request;
		return request;
	}
});




Ext.override(Ext.data.reader.Reader, {
	/**
	 * Creates new Reader.
	 * @param {Object} config (optional) Config object.
	 */
	constructor: function(config) {
		var me = this;

		me.mixins.observable.constructor.call(me, config);
		me.fieldCount = 0;
		me.model = Ext.ModelManager.getModel(me.model);
		me.accessExpressionFn = Ext.Function.bind(me.createFieldAccessExpression, me);

		// Extractors can only be calculated if the fields MixedCollection has been set.
		// A Model may only complete its setup (set the prototype properties) after asynchronous loading
		// which would mean that there may be no "fields"
		// If this happens, the load callback will call proxy.setModel which calls reader.setModel which
		// triggers buildExtractors.
		if (me.model && me.model.prototype.fields) {
			me.buildExtractors();
		}

		this.addEvents(
			/**
			 * @event
			 * Fires when the reader receives improperly encoded data from the server
			 * @param {Ext.data.reader.Reader} reader A reference to this reader
			 * @param {XMLHttpRequest} response The XMLHttpRequest response object
			 * @param {Ext.data.ResultSet} error The error object
			 */
			'exception'
		);

		this.on('exception', function(r, e){
			app.alert(
				'<p><span style="font-weight:bold">'+ (e.where != 'undefined' ? e.message : e.message.replace(/\n/g,''))  +'</span></p><hr>' +
					'<p>'+ (typeof e.where != 'undefined' ? e.where.replace(/\n/g,'<br>') : e.data) +'</p>',
				'error'
			);
//			Ext.Msg.show({
//				title:'Exception!',
//				msg:'<p><span style="font-weight:bold">'+ response.message +'</span></p><hr>' +
//					'<p>'+ response.where.replace(/\n/g,'<br>') +'</p>',
//				styleHtmlContent:true,
//				buttons:Ext.Msg.OK,
//				icon: Ext.Msg.ERROR
//			});
		});
	}
});

Ext.override(Ext.data.Store, {
	updateGroupsOnUpdate: function(record, modifiedFieldNames){
		var me = this,
			groupField = me.getGroupField(),
			groupName = me.getGroupString(record),
			groups = me.groups,
			len, i, items, group;

		if (modifiedFieldNames && Ext.Array.indexOf(modifiedFieldNames, groupField) !== -1) {


			if (me.buffered) {
				Ext.Error.raise({
					msg: 'Cannot move records between groups in a buffered store record'
				});
			}


			items = groups.items;
			for (i = 0, len = items.length; i < len; ++i) {
				group = items[i];
				if (group.contains(record)) {
					group.remove(record);
					break;
				}
			}
			group = groups.getByKey(groupName);
			if (!group) {
				group = groups.add(new Ext.data.Group({
					key: groupName,
					store: me
				}));
			}
			group.add(record);



			me.data.remove(record);
			me.data.insert(me.data.findInsertionIndex(record, me.generateComparator()), record);


			for (i = 0, len = this.getCount(); i < len; i++) {
				me.data.items[i].index = i;
			}

		} else {
			/**
			 * group null issue
			 */
			if(groupName){
				groups.getByKey(groupName).setDirty();
			}
		}
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
	},

	setColumnField: function(column, field) {
		var me = this,
			editor = me.getEditor();

		editor.removeColumnEditor(column);
		Ext.grid.plugin.RowEditing.superclass.setColumnField.apply(this, arguments);
		me.getEditor().addFieldsForColumn(column, true);
		me.getEditor().insertColumnEditor(column);
	},

	addFieldsForColumn: function(column, initial) {
		var me = this,
			i,
			length, field;

		if (Ext.isArray(column)) {
			for (i = 0, length = column.length; i < length; i++) {
				me.addFieldsForColumn(column[i], initial);
			}
			return;
		}

		if (column.getEditor) {

			// Get a default display field if necessary
			field = column.getEditor(null, {
				xtype: 'displayfield',
				// Override Field's implementation so that the default display fields will not return values. This is done because
				// the display field will pick up column renderers from the grid.
				getModelData: function() {
					return null;
				}
			});
			if (column.align === 'right') {
				field.fieldStyle = 'text-align:right';
			}

			if (column.xtype === 'actioncolumn') {
				field.fieldCls += ' ' + Ext.baseCSSPrefix + 'form-action-col-field'
			}

			if (me.isVisible() && me.context) {
				if (field.is('displayfield')) {
					me.renderColumnData(field, me.context.record, column);
				} else {
					field.suspendEvents();
					field.setValue(me.context.record.get(column.dataIndex));
					field.resumeEvents();
				}
			}
			if (column.hidden) {
				me.onColumnHide(column);
			} else if (column.rendered && !initial) {
				// Setting after initial render
				me.onColumnShow(column);
			}
		}
		me.mon(field, 'change', me.onFieldChange, me);
	}

});

Ext.override(Ext.grid.plugin.RowEditing, {
	errorSummary: false,
	setColumnField: function(column, field) {
		var me = this,
			editor = me.getEditor();

		editor.removeColumnEditor(column);
		Ext.grid.plugin.RowEditing.superclass.setColumnField.apply(this, arguments);
		me.getEditor().addFieldsForColumn(column, true);
		me.getEditor().insertColumnEditor(column);
	}
});

Ext.override(Ext.form.field.Checkbox, {
    inputValue: '1',
    uncheckedValue: '0'
});
Ext.override(Ext.form.field.Date, {
    format: 'Y-m-d'
});
Ext.override(Ext.grid.Panel, {
    emptyText: 'Nothing to Display',
	columnLines: true
});
Ext.override(Ext.grid.plugin.Editing, {
    cancelEdit: function(){
        var me = this;
        me.editing = false;
        me.fireEvent('canceledit', me, me.context);
	    if(me.grid.store.rejectChanges) me.grid.store.rejectChanges();
    }
});


Ext.override(Ext.container.Container, {

    setAutoSyncFormEvent: function(field){
        if(field.xtype == 'textfield' || field.xtype == 'textareafield'){
            field.on('keyup', this.autoSyncForm, this);
        }else if(field.xtype == 'radiofield' || field.xtype == 'checkbox'){
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
        }else if(field.xtype == 'checkbox'){
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
        }else if(field.xtype == 'checkbox'){
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
        app.nav.goBack();
    },

    checkIfCurrPatient: function(){
        return app.getCurrPatient();
    },

	patientInfoAlert: function(){
        var patient = app.getCurrPatient();
        Ext.Msg.alert(_('status'), _('patient') + ': ' + patient.name + ' (' + patient.pid + ')');
    },

	currPatientError: function(msg){
        Ext.Msg.show({
            title: 'Oops! ' + _('no_patient_selected'),
            msg: Ext.isString(msg) ? msg : _('select_patient_patient_live_search'),
            scope: this,
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.ERROR,
            fn: function(){
                this.goBack();
            }
        });
    },

    getFormItems: function(formPanel, formToRender, callback){
	    if(formPanel) formPanel.removeAll();
	    FormLayoutEngine.getFields({formToRender: formToRender}, function(provider, response) {
		    var items = Ext.JSON.decode(response.result.replace(/\\\\u/g, '\\u')), // UTF-8 \\u fixed
				form = formPanel ? formPanel.add(items) : false;

		    if(typeof callback == 'function') callback(formPanel, items, true);
		    return form;
	    });
    },

    boolRenderer: function(val){
        if(val == '1' || val == true || val == 'true'){
            return '<div style="margin-left:auto; margin-right:auto; width:16px; height:16px"><img src="resources/images/icons/yes.gif" /></div>';
        }else if(val == '0' || val == false || val == 'false'){
            return '<div style="margin-left:auto; margin-right:auto; width:16px; height:16px"><img src="resources/images/icons/no.gif" /></div>';
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
        var msg = Ext.Msg.prompt(_('password_verification'), _('please_enter_your_password') + ':', function(btn, password){
            callback(btn, password);
        });
        var f = msg.textField.getInputId();
        document.getElementById(f).type = 'password';
        return msg;
    }
});


//Ext.override(Ext.grid.ViewDropZone, {
//
//    handleNodeDrop: function(data, record, position){
//        var view = this.view,
//	        store = view.getStore(),
//	        index, records, i, len;
//        /**
//         * fixed to handle the patient button data
//         */
//        if(!data.patient){
//
//	        if(data.copy){
//                records = data.records;
//                data.records = [];
//                for(i = 0, len = records.length; i < len; i++){
//	                data.records.push(records[i].copy());
//                }
//            }else{
//		        data.view.store.remove(data.records, data.view === view);
//            }
//        }
//
//	    if (record && position) {
//		    index = store.indexOf(record);
//
//		    // 'after', or undefined (meaning a drop at index -1 on an empty View)...
//		    if (position !== 'before') {
//			    index++;
//		    }
//		    store.insert(index, data.records);
//	    }
//	    // No position specified - append.
//	    else {
//		    store.add(data.records);
//	    }
//
//
//
//
//        view.getSelectionModel().select(data.records);
//    }
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
//});
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