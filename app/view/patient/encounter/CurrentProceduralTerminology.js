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

Ext.define('App.view.patient.encounter.CurrentProceduralTerminology', {
    extend:'Ext.panel.Panel',
    alias:'widget.currentproceduralterminology',
    autoScroll:true,
    border:false,
    bodyBorder:false,
    bodyPadding:5,
    bodyStyle: 'background-color:#fff',
    layout:'border',
    pid:null,
    eid:null,
    initComponent:function () {
        var me = this;


        me.referenceCptStore = Ext.create('App.store.patient.QRCptCodes');

        me.encounterCptStore = Ext.create('App.store.patient.EncounterServices', {
            autoSync:true,
            listeners:{
                scope:me,
                beforesync:me.beforesync
            }
        });


        me.cptFormEdit = Ext.create('App.ux.grid.RowFormEditing', {
            autoCancel:false,
            errorSummary:false,
            clicksToEdit:1,
            enableRemove:true,
            items:[
                {
                    fieldLabel: _('full_description'),
                    xtype:'displayfield',
                    name:'code_text',
                    anchor:'100%'
                },
                {
                    xtype:'container',
                    layout:'column',
                    items:[
                        {
                            xtype:'fieldcontainer',
                            layout:'anchor',
                            columnWidth:.5,
                            margin:'0 3 0 0',
                            defaults:{ xtype:'textfield' },
                            items:[
                                {
                                    fieldLabel: _('place_of_service'),
                                    name:'place_of_service',
                                    anchor:'100%'
                                },
                                {
                                    xtype:'checkbox',
                                    labelWidth:105,
                                    fieldLabel: _('emergency') + '?',
                                    name:'emergency'
                                },
                                {
                                    fieldLabel: _('charges'),
                                    name:'charge',
                                    anchor:'100%'
                                }
                            ]
                        },
                        {
                            xtype:'fieldcontainer',
                            layout:'anchor',
                            columnWidth:.5,
                            margin:'0 0 0 3',
                            defaults:{ xtype:'textfield', anchor:'100%', labelWidth:110 },
                            items:[
                                {
                                    fieldLabel: _('days_of_units'),
                                    name:'days_of_units'
                                },
                                {
                                    fieldLabel: _('essdt_fam_plan'),
                                    name:'essdt_plan'
                                },
                                {
                                    fieldLabel: _('modifiers'),
                                    xtype:'livecptsearch',
                                    hideLabel:false,
                                    name:'modifiers'
                                }

                            ]
                        }

                    ]
                },
                {
                    xtype:'liveicdxsearch',
                    fieldLabel: _('diagnosis'),
                    hideLabel:false,
                    name:'diagnosis'
                }
            ],
            listeners:{
                scope:me,
                afterremove:me.onCompleteRemove
            }
        });

        me.items = [
            {
                xtype:'panel',
                title: _('cpt_search'),
                itemId:'leftCol',
                region:'west',
                width:450,
                hidden:true,
                titleCollapse:true,
                margin:'0 5 0 0',
                bodyStyle:'background-color:#fff',
                layout:{
                    type:'vbox',
                    align:'stretch',
                    padding:5
                },
                items:[
                    {
                        xtype:'fieldset',
                        title: _('cpt_quick_reference_options'),
                        padding:'10 15',
                        margin:'0 0 3 0',
                        layout:'anchor',
                        items:{
                            xtype:'combobox',
                            anchor:'100%',
                            editable:false,
                            queryMode:'local',
                            valueField:'value',
                            displayField:'name',
                            store:Ext.create('Ext.data.Store', {
                                fields:['name', 'value'],
                                data:[
                                    { name: _('show_related_cpt_for_current_diagnostics'), value:0 },
                                    { name: _('show_cpt_history_for_this_patient'), value:1 },
                                    { name: _('show_cpt_commonly_used_by_clinic'), value:2 }
                                ]
                            }),
                            listeners:{
                                scope:me,
                                change:me.onQuickReferenceOption
                            }
                        }
                    },
                    Ext.create('Ext.ux.LiveSearchGridPanel', {
                        margins:0,
                        flex:1,
                        store:me.referenceCptStore,
                        viewConfig:{
                            copy:true,
                            stripRows:true,
                            loadMask:true,
                            plugins:[
                                {
                                    ptype:'gridviewdragdrop',
                                    dragGroup:'CPTGridDDGroup'
                                }
                            ]
                        },
                        columns:[
                            {
                                text: _('code'),
                                width:70,
                                sortable:true,
                                dataIndex:'code'
                            },
                            {
                                text: _('description'),
                                flex:1,
                                sortable:true,
                                dataIndex:'code_text_medium'
                            }
                        ]
                    })
                ],
                listeners:{
                    scope:me,
                    collapse:me.onQuickReferenceCollapsed
                }
            },
            {
                xtype:'panel',
                title: _('encounter_cpts'),
                region:'center',
                itemId:'rightCol',
                bodyStyle:'background-color:#fff',
                layout:{
                    type:'vbox',
                    align:'stretch',
                    padding:5
                },
                items:[
                    {
                        xtype:'fieldset',
                        title: _('cpt_live_sarch'),
                        padding:'10 15',
                        margin:'0 0 3 0',
                        layout:'anchor',
                        items:{
                            xtype:'livecptsearch',
                            listeners:{
                                scope:me,
                                select:me.onLiveCptSelect
                            }
                        }

                    },
                    {
                        xtype:'grid',
                        flex:1,
                        margins:0,
                        store:me.encounterCptStore,
                        columns:[
                            {
                                text: _('code'),
                                width:70,
                                sortable:true,
                                dataIndex:'code'
                            },
                            {
                                text: _('description'),
                                flex:1,
                                sortable:true,
                                dataIndex:'code_text'
                            },
                            {
                                text: _('status'),
                                width:50,
                                sortable:true,
                                dataIndex:'status',
                                renderer:me.status
                            }
                        ],
                        tbar:[
                            {
                                text: _('quick_reference'),
                                action:'referenceCptBtn',
                                enableToggle:true,
                                scope:me,
                                toggleHandler:me.onQuickReferenceToggle
                            },
                            '->',
                            {
                                text: _('reload'),
                                handler: function(){
                                    me.encounterCptStoreLoad(null);
                                }
                            }
                        ],
                        viewConfig:{
                            itemId:'view',
                            plugins: {
                                ptype:'gridviewdragdrop',
                                dropGroup:'CPTGridDDGroup'

                            },
                            listeners:{
                                scope:me,
                                drop:me.onCptDropped
                            }
                        },
                        plugins:me.cptFormEdit

                    }
                ]

            }
        ];


        me.callParent(arguments);

    },


    status:function(val){
        if(val == '0') {
            return '<img style="padding-left: 10px" src="resources/images/icons/no.gif" />';
        } else if(val == '1') {
            return '<img style="padding-left: 10px" src="resources/images/icons/yes.gif" />';
        } else if(val == '2') {
            return '<img style="padding-left: 10px" src="resources/images/icons/icohelp.png" />';
        }
        return val;
    },

    onQuickReferenceCollapsed:function () {
        var btn = this.query('button[action="referenceCptBtn"]');
        if (btn[0].pressed) {
            btn[0].toggle(false);
        }
    },

    onQuickReferenceToggle:function (btn, pressed) {
        if (pressed) {
            this.getComponent('leftCol').show();
        } else {
            this.getComponent('leftCol').hide();
        }

    },

    onQuickReferenceOption:function (combo, value) {
        this.loadCptQuickReferenceGrid(value);
    },


    onCompleteRemove:function () {
        app.msg('Sweet!', _('cpt_removed_from_this_encounter'));
    },

    onLiveCptSelect:function (btn, record) {
        var me = this;
        btn.reset();
	    delete record[0].data.id;
	    record[0].data.eid = me.eid;
        me.encounterCptStore.add(record[0].data);

    },

    loadCptQuickReferenceGrid:function (filter) {
        this.referenceCptStore.load({params:{pid:this.pid, eid:this.eid, filter:filter}});
    },

    beforesync:function(options){
        if(options.create){
            options.create[0].data.eid = this.eid;
        }
    },

    onCptDropped:function(node, data, dropRecord, dropPosition, dropFunction){
        app.msg('Sweet!', _('cpt_added_to_this_encounter'));
        this.cptFormEdit.cancelEdit();
        var store = dropRecord.store,
            dropIndex = store.indexOf(dropRecord),
            index = dropPosition == 'before' ? dropIndex - 1  : dropIndex + 1;


        this.cptFormEdit.startEdit(index, 0)
    },

    setDefaultQRCptCodes:function(){
        var combo = this.down('combobox');
        if (combo.getValue() != 1) {
            combo.setValue(1);
        } else {
            this.loadCptQuickReferenceGrid(1);
        }
    },

    encounterCptStoreLoad:function(pid, eid, callback){
        this.pid = pid ? pid : this.pid;
        this.eid = eid ? eid : this.eid;
        this.encounterCptStore.load({
            filters:[
                {
                    property:'eid',
                    value: this.eid
                }
            ],
            callback:function(){
                callback ? callback() : null;
            }
        });
    }


});