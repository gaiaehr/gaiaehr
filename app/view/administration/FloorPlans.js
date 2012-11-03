/*
 GaiaEHR (Electronic Health Records)
 FloorPlans.js
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
Ext.define('App.view.administration.FloorPlans', {
    extend: 'App.ux.RenderPanel',
    id: 'panelFloorPlans',
    pageTitle: i18n('floor_plan_editor'),
    pageLayout: 'border',
    floorPlanId: null,
    activeZone: null,
    initComponent: function(){
        var me = this;
        me.floorPlansStore = Ext.create('App.store.administration.FloorPlans');
        me.floorZonesStore = Ext.create('App.store.administration.FloorPlanZones');
        me.floorPlans = Ext.create('Ext.grid.Panel', {
            title: i18n('floor_plans'),
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
                        emptyText:i18n('new_floor_plan')
                    }
                }
            ],
            tbar: ['->',
                {
                    text: i18n('add_floor_plan'),
                    action: 'newFloorPlan',
                    scope: me,
                    handler: me.onNewFloorPlan
                }
            ],
            listeners: {
                scope: me,
                select: me.onFloorPlanSelected
            }
        });
        me.floorPlanZones = Ext.create('Ext.panel.Panel', {
            title: i18n('floor_plan'),
            region: 'center',
            bodyCls: 'floorPlan',
            layout: 'absolute',
            tbar: [
                {
                    text: i18n('add_zone'),
                    action: 'newZone',
                    iconCls:'icoAdd',
                    scope: me,
                    handler: me.onNewZone
                }
            ]
        });
        me.floorPlanZoneEditor = Ext.create('Ext.window.Window', {
            title:i18n('zone_editor'),
            closeAction:'hide',
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
                            fieldLabel: i18n('zone_name'),
                            name: 'title'
                        },
                        {
                            xtype:'colorcombo',
                            fieldLabel: i18n('bg_color'),
                            name:'bg_color'
                        },
                        {
                            xtype:'colorcombo',
                            fieldLabel: i18n('border_color'),
                            name:'border_color'
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: i18n('width'),
                            minValue: 30,
                            maxValue: 200,
                            name: 'width'
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: i18n('height'),
                            minValue: 30,
                            maxValue: 200,
                            name: 'height'
                        },
                        {
                            xtype: 'checkbox',
                            fieldLabel: i18n('show_priority_color'),
                            name: 'show_priority_color'
                        },
                        {
                            xtype: 'checkbox',
                            fieldLabel: i18n('show_patient_preview'),
                            name: 'show_patient_preview'
                        },
                        {
                            xtype: 'checkbox',
                            fieldLabel: i18n('active'),
                            name: 'active'
                        }
                    ]

                }
            ],
            buttons:[
                {
                    text:i18n('remove'),
                    xtype:'button',
                    scope:me,
                    handler:me.onZoneRemove
                },
                '-',
                {
                    text:i18n('cancel'),
                    xtype:'button',
                    scope:me,
                    handler:me.onZoneCancel
                },
                '-',
                {
                    text:i18n('save'),
                    xtype:'button',
                    scope:me,
                    handler:me.onZoneSave
                }
            ]
        });
        me.listeners = {
            show: function(){
                me.nav = Ext.create('Ext.util.KeyNav', Ext.getDoc(), {
                        scope: me,
                        left: function(){
                            me.moveZone('left')
                        },
                        up: function(){
                            me.moveZone('up')
                        },
                        right: function(){
                            me.moveZone('right')
                        },
                        down: function(){
                            me.moveZone('down')
                        }
                    });
            },
            hide: function(){
                if(me.nav){
                    Ext.destroy(me.nav);
                }
            }
        };
        me.pageBody = [me.floorPlans, me.floorPlanZones];
        me.callParent(arguments);
    },
    setEditMode:function(bool){
        this.floorPlanZoneEditor.setVisible(bool);
    },
    getEditor:function(){
        return this.floorPlanZoneEditor.down('form');
    },
    onZoneCancel:function(btn){
        btn.up('window').hide();
    },
    onZoneSave:function(btn){
        var editor = this.getEditor(),
            form = editor.getForm(),
            values = form.getValues(),
            record = form.getRecord();
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
        this.applyZoneConfig(editor.zone, config);
    },
    onZoneToggled:function(zone, pressed){
        var me = this, config;
        me.setEditMode(pressed);
        if(pressed){
            me.activeZone = zone;
            me.getEditor().zone = zone;
            me.floorPlanZones.focus();
            me.getEditor().getForm().loadRecord(zone.record);
        }else{
            me.activeZone = null;
            zone.record.set({
                x: zone.x,
                y: zone.y
            });
        }
    },
    onZoneRemove:function(btn){
        var me = this,
            editor = this.getEditor(),
            form = editor.getForm(),
            record = form.getRecord(),
            zone = editor.zone;
        Ext.Msg.show({
            title:'Wait!',
            msg: 'This action is final. Are you sure you want to remove <span style="font-weight: bold">"'+record.data.title+'"</span>?',
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
    onNewZone: function(){
        this.createZone(null);
    },
    createZone: function(record){
        var me = this, zone, form;
        zone = Ext.create('Ext.button.Split', {
            text: record ? record.data.title : i18n('new_zone'),
            toggleGroup: 'zones',
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
            enableToggle: true,
            scope:me,
            toggleHandler: me.onZoneToggled
        });
        me.floorPlanZones.add(zone);
        if(record != null){
            zone.record = record;
        }else{
            me.floorZonesStore.add({
                floor_plan_id: me.floorPlanId,
                title: i18n('new_zone'),
                x: 5,
                y: 5,
                show_priority_color: 1,
                show_patient_preview: 1,
                active: 0
            });
            me.floorZonesStore.sync({
                callback: function(batch, options){
                    zone.record = batch.operations[0].records[0];
                }
            })
        }
    },
    applyZoneConfig:function(zone, config){
        zone.setText(config.text);
        zone.getEl().applyStyles(config.style);
        zone.setScale(config.scale);
        zone.setSize(config.width, config.height);
    },
//    afterMenuShow: function(btn){
//        btn.toggle(true);
//    },
//    afterMenuHide: function(btn){
//        var form = btn.menu.items.items[0].getForm(),
//            record = form.getRecord();
//        form.loadRecord(record);
//            values = form.getValues(),
//            record = form.getRecord();
//        btn.setText(values.title);
//        record.set(values);
//    },
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
        }
    },
    zoneDragged: function(drag){
        drag.comp.toggle(true);
    },
    onNewFloorPlan: function(){
        this.floorPlansStore.add({});
    },
    onFloorPlanSelected: function(model, record){
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
                this.activeZone = null;
                for(var i = 0; i < records.length; i++){
                    me.createZone(records[i]);
                }
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
