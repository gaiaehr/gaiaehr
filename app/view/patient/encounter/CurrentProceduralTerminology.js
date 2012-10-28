/**
 * Created by JetBrains PhpStorm.
 * User: GaiaEHR
 * Date: 3/23/12
 * Time: 2:06 AM
 * To change this template use File | Settings | File Templates.
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

        me.encounterCptStore = Ext.create('Ext.data.Store', {
            model:'App.model.patient.CptCodes',
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
            formItems:[
                {
                    fieldLabel: i18n('full_description'),
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
                                    fieldLabel: i18n('place_of_service'),
                                    name:'place_of_service',
                                    anchor:'100%'
                                },
                                {
                                    xtype:'checkbox',
                                    labelWidth:105,
                                    fieldLabel: i18n('emergency') + '?',
                                    name:'emergency'
                                },
                                {
                                    fieldLabel: i18n('charges'),
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
                                    fieldLabel: i18n('days_of_units'),
                                    name:'days_of_units'
                                },
                                {
                                    fieldLabel: i18n('essdt_fam_plan'),
                                    name:'essdt_plan'
                                },
                                {
                                    fieldLabel: i18n('modifiers'),
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
                    fieldLabel: i18n('diagnosis'),
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
                title: i18n('cpt_search'),
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
                        title: i18n('cpt_quick_reference_options'),
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
                                    { name: i18n('show_related_cpt_for_current_diagnostics'), value:0 },
                                    { name: i18n('show_cpt_history_for_this_patient'), value:1 },
                                    { name: i18n('show_cpt_commonly_used_by_clinic'), value:2 }
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
                                text: i18n('code'),
                                width:70,
                                sortable:true,
                                dataIndex:'code'
                            },
                            {
                                text: i18n('description'),
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
                title: i18n('encounter_cpts'),
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
                        title: i18n('cpt_live_sarch'),
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
                                text: i18n('code'),
                                width:70,
                                sortable:true,
                                dataIndex:'code'
                            },
                            {
                                text: i18n('description'),
                                flex:1,
                                sortable:true,
                                dataIndex:'code_text'
                            },
                            {
                                text: i18n('status'),
                                width:50,
                                sortable:true,
                                dataIndex:'status',
                                renderer:me.status
                            }
                        ],
                        tbar:[
                            {
                                text: i18n('quick_reference'),
                                action:'referenceCptBtn',
                                enableToggle:true,
                                scope:me,
                                toggleHandler:me.onQuickReferenceToggle
                            },
                            '->',
                            {
                                text: i18n('reload'),
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
        app.msg('Sweet!', i18n('cpt_removed_from_this_encounter'));
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
        app.msg('Sweet!', i18n('cpt_added_to_this_encounter'));
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
        this.encounterCptStore.proxy.extraParams = {eid:this.eid, filter:null};
        this.encounterCptStore.load({
            callback:function(){
                callback ? callback() : null;
            }
        });
    }


});