/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.view.administration.FloorPlans', {
    extend: 'App.ux.RenderPanel',
    id: 'panelFloorPlans',
    pageTitle: _('floor_plan_editor'),
    pageLayout: 'border',
    floorPlanId: null,
    activeZone: null,
    initComponent: function(){
        var me = this;
        me.floorPlansStore = Ext.create('App.store.administration.FloorPlans');
        me.floorZonesStore = Ext.create('App.store.administration.FloorPlanZones');
        me.floorPlans = Ext.create('Ext.grid.Panel', {
            title: _('floor_plans'),
            region: 'west',
            width: 200,
            split: true,
            hideHeaders: true,
            store: me.floorPlansStore,
            plugins: [
                me.floorPlanEditing = Ext.create('Ext.grid.plugin.RowEditing', {
                    clicksToEdit: 2
                })
            ],
            columns: [
                {
                    dataIndex: 'title',
                    sortable: false,
                    hideable: false,
                    flex: 1,
                    editor: {
                        xtype: 'textfield',
                        emptyText:_('new_floor')
                    }
                }
            ],
            tbar: [
                {
                    text: _('add_floor'),
                    action: 'newFloorPlan',
                    iconCls:'icoAdd',
                    scope: me,
                    handler: me.onNewFloorPlan
                },
                '-',
                {
                    text: _('remove_floor'),
                    action: 'newFloorPlan',
                    iconCls:'icoDelete',
                    scope: me,
                    handler: me.onRemoveFloorPlan
                }
            ],
            listeners: {
                scope: me,
                select: me.onFloorPlanSelected
            }
        });
        me.floorPlanZones = Ext.create('Ext.panel.Panel', {
            title: _('floor_plan'),
            region: 'center',
            bodyCls: 'floorPlan',
            layout: 'absolute',
            tbar: [
                {
                    text: _('add_zone'),
                    action: 'newZone',
                    iconCls:'icoAdd',
                    scope: me,
                    handler: me.onNewZone
                }
            ]
        });
        me.floorPlanZoneEditor = Ext.create('Ext.window.Window', {
            title:_('zone_editor'),
            closeAction:'hide',
            closable:false,
            resizable:false,
            items:[
                {
                    xtype:'form',
                    border:false,
                    bodyPadding: '10',
                    defaults:{
                        labelWidth: 130,
                        anchor:'100%'
                    },
                    items:[
                        {
                            xtype: 'textfield',
                            fieldLabel: _('zone_name'),
                            name: 'title'
                        },
                        {
                            xtype:'colorcombo',
                            fieldLabel: _('bg_color'),
                            name:'bg_color'
                        },
                        {
                            xtype:'colorcombo',
                            fieldLabel: _('border_color'),
                            name:'border_color'
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: _('width'),
                            minValue: 30,
                            maxValue: 300,
                            name: 'width'
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: _('height'),
                            minValue: 30,
                            maxValue: 300,
                            name: 'height'
                        },
                        {
                            xtype: 'checkbox',
                            fieldLabel: _('show_priority_color'),
                            name: 'show_priority_color'
                        },
                        {
                            xtype: 'checkbox',
                            fieldLabel: _('show_patient_preview'),
                            name: 'show_patient_preview'
                        },
                        {
                            xtype: 'checkbox',
                            fieldLabel: _('active'),
                            name: 'active'
                        }
                    ]
                }
            ],
            buttons:[
                {
                    text:_('remove'),
                    xtype:'button',
                    scope:me,
                    handler:me.onZoneRemove
                },
                '->',
                {
                    text:_('cancel'),
                    xtype:'button',
                    scope:me,
                    handler:me.onZoneCancel
                },
                '-',
                {
                    text:_('save'),
                    xtype:'button',
                    scope:me,
                    handler:me.onZoneSave
                }
            ],
            listeners:{
                scope:me,
                afterrender:function(win){
                   win.alignTo(this.floorPlanZones.getEl(), 'tr-tr', [-130, 70]);
                }
            }
        });
        me.listeners = {
            show: function(){
                me.nav = Ext.create('Ext.util.KeyNav', Ext.getDoc(),{
                    scope: me,
                    left: function(){
                        me.moveZone('left');
                    },
                    up: function(){
                        me.moveZone('up');
                    },
                    right: function(){
                        me.moveZone('right');
                    },
                    down: function(){
                        me.moveZone('down');
                    }
                });
            },
            hide: function(){
                if(me.nav) Ext.destroy(me.nav);
                me.setEditMode(false);
            }
        };
        me.pageBody = [me.floorPlans, me.floorPlanZones ];
        me.callParent(arguments);
    },
    setEditMode:function(show, zone){
        var me = this,
	        el = me.activeZone ? me.activeZone.getEl() : null;

        if(el){
                me.setEditor(show, zone);
        }else{
            me.setEditor(show, zone);
        }
    },
    setEditor:function(show, zone){
        var me = this;
        if(show){
            me.activeZone = zone;
            me.getEditor().zone = zone;
            me.floorPlanZones.focus();
            me.getEditor().getForm().loadRecord(zone.record);
	        if(me.floorPlanZoneEditor.hidden){
		        me.floorPlanZoneEditor.show(zone.getEl());
	        }

        }else{
            me.floorPlanZoneEditor.hide();
            me.getEditor().getForm().reset();
            me.activeZone = null;
        }
    },
    getEditor:function(){
        return this.floorPlanZoneEditor.down('form');
    },
    onZoneCancel:function(btn){
        var me = this,
            zone = me.activeZone,
            record = zone.record,
            config;
        record.reject();
        config = {
            text: record.data.title,
            scale: record.data.scale,
            style:{
                'border-color':record.data.border_color,
                'background-color':record.data.bg_color
            },
            width:record.data.width,
            height:record.data.height
        };
        me.activeZone.setPosition(record.data.x, record.data.y);
        me.applyZoneConfig(zone, config);
        me.setEditMode(false);
    },
    onZoneSave:function(){
        var me = this,
            editor = me.getEditor(),
            form = editor.getForm(),
            values = form.getValues(),
            record = form.getRecord(),
            config;
        record.set(values);
        config = {
            text: record.data.title,
            scale: record.data.scale,
            style:{
                'border-color':record.data.border_color,
                'background-color':record.data.bg_color
            },
            width:record.data.width,
            height:record.data.height
        };
        record.store.sync();
        me.applyZoneConfig(editor.zone, config);
        me.setEditMode(false);
    },
    onZoneHandler:function(zone){
        var me = this;
        me.setEditMode(true, zone);
    },
    onZoneRemove:function(){
        var me = this,
            editor = this.getEditor(),
            form = editor.getForm(),
            record = form.getRecord(),
            zone = editor.zone;
        Ext.Msg.show({
            title:'Wait!',
            msg: _('remove_final_notice') + ' <span style="font-weight: bold">"'+record.data.title+'"</span>?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.WARNING,
            fn:function(btn){
                if(btn == 'yes'){
                    me.floorZonesStore.remove(record);
                    me.floorZonesStore.sync({
                        success:function(){
                            me.floorPlanZones.remove(zone, true);
                            editor.up('window').hide();
                        }
                    });
                }
            }
        });
    },
    onRemoveFloorPlan:function(btn){
        var me = this,
            grid = btn.up('grid'),
            store = grid.store,
            sm = grid.getSelectionModel(),
            record = sm.getLastSelected();
        Ext.Msg.show({
            title:'Wait!',
            msg: _('remove_final_notice') + ' <span style="font-weight: bold">"'+record.data.title+'"</span>?',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.WARNING,
            fn:function(btn){
                if(btn == 'yes'){
                    store.remove(record);
                    store.sync({
                        callback:function(){
                            sm.deselectAll();
                            me.floorPlanZones.removeAll();
                            me.msg('Sweet!',_('record_removed'))
                        }
                    });

                }
            }
        });

    },
    onNewZone: function(){
        var me = this;
        me.floorZonesStore.add({
            floor_plan_id: me.floorPlanId,
            title: _('new_zone'),
            x: 5,
            y: 5,
            show_priority_color: 1,
            show_patient_preview: 1,
            active: 0
        });
        me.floorZonesStore.sync({
            callback: function(batch){
                me.createZone(batch.operations[0].records[0]);
            }
        });
    },
    createZone: function(record){
        var me = this, zone;
        zone = me.floorPlanZones.add(
            Ext.create('Ext.button.Split', {
                text: record.data.title,
                draggable: {
                    listeners: {
                        scope: me,
                        dragend: me.zoneDragged
                    }
                },
                scale: record.data.scale,
                style:{
                    'border-color':record.data.border_color,
                    'background-color':record.data.bg_color
                },
                x: record ? record.data.x : 5,
                y: record ? record.data.y : 5,
                width:record.data.width,
                height:record.data.height,
                scope:me,
                handler: me.onZoneHandler
            })
        );
        zone.record = record;
    },
    applyZoneConfig:function(zone, config){
        zone.setText(config.text);
        zone.getEl().applyStyles(config.style);
        zone.setScale(config.scale);
        zone.setSize(config.width, config.height);
    },
    moveZone: function(direction){
        if(app.currCardCmp == this && this.activeZone != null){
            var x = this.activeZone.x, y = this.activeZone.y;
            if(direction == 'left'){
                x = x - 1;
            }else if(direction == 'right'){
                x = x + 1;
            }else if(direction == 'up'){
                y = y - 1;
            }else if(direction == 'down'){
                y = y + 1;
            }
            this.activeZone.setPosition(x, y);
            this.activeZone.record.set({x:x,y:y});
        }
    },
    zoneDragged: function(drag){
        var zone = drag.comp;
        zone.record.set({
            x: zone.x,
            y: zone.y
        });
    },
    onNewFloorPlan: function(){
        this.floorPlansStore.add({});
    },
    onFloorPlanSelected: function(model, record){
        this.setEditMode(false);
        this.floorPlanId = record.data.id;
        this.reloadFloorPlanZones();
    },
    reloadFloorPlanZones: function(){
        var me = this;
        me.floorPlanZones.removeAll();
        me.floorZonesStore.load({
            params:{ floor_plan_id: this.floorPlanId },
            scope: me,
            callback: function(records, operation, success){
                me.setEditMode(false);
                for(var i = 0; i < records.length; i++) me.createZone(records[i]);
            }
        });
    },
    /**
     * This function is called from Viewport.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback){
        var me = this;
        me.floorPlansStore.load({
            callback:function(){
                me.floorPlans.getSelectionModel().select(0);
            }
        });
        callback(true);
    }
});
