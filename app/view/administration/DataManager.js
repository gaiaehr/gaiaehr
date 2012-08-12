/**
 * services.ejs.php
 * Services
 * v0.0.1
 *
 * Author: Ernest Rodriguez
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 *
 * @namespace Services.getServices
 * @namespace Services.addService
 * @namespace Services.updateService
 */
Ext.define('App.view.administration.DataManager', {
    extend       : 'App.classes.RenderPanel',
    id           : 'panelDataManager',
    pageTitle    : 'Data Manager',
    uses         : [
        'App.classes.GridPanel', 'App.classes.combo.CodesTypes', 'App.classes.combo.Titles'
    ],
    initComponent: function() {
        var me = this;

        me.active = 1;
        me.dataQuery = '';
        me.code_type = 'CPT4';

        me.store = Ext.create('App.store.administration.Services');

        me.activeProblemsStore = Ext.create('App.store.administration.ActiveProblems');
        me.medicationsStore = Ext.create('App.store.administration.Medications');
        me.ImmuRelationStore = Ext.create('App.store.administration.ImmunizationRelations');
        me.labObservationsStore = Ext.create('App.store.administration.LabObservations');

        function code_type(val) {
            if(val == '1') {
                return 'CPT4';
            } else if(val == '2') {
                return 'ICD9';
            } else if(val == '3') {
                return 'HCPCS';
            } else if(val == '100') {
                return 'CVX';
            }
            return val;
        }

        /**
         * CPT Container
         */
        me.cptContainer = Ext.create('Ext.container.Container', {
            layout: 'column',
            action: 'CPT4',
            //hidden: true,
            items : [

                {
                    xtype    : 'fieldcontainer',
                    msgTarget: 'under',
                    defaults : { action: 'field'  },
                    items    : [
                        {

                            fieldLabel: 'Type',
                            xtype     : 'mitos.codestypescombo',
                            name      : 'code_type'
                        },
                        {

                            fieldLabel: 'Code',
                            xtype     : 'textfield',
                            name      : 'code'
                        },
                        {

                            fieldLabel: 'Modifier',
                            xtype     : 'textfield',
                            name      : 'mod'
                        }

                    ]
                },
                {
                    xtype   : 'fieldcontainer',
                    margin  : '0 0 0 10',
                    defaults: { action: 'field' },
                    items   : [
                        {

                            fieldLabel: 'Description',
                            xtype     : 'textfield',
                            name      : 'code_text'
                        },
                        {
                            fieldLabel: 'Category',
                            xtype     : 'mitos.titlescombo',
                            name      : 'title'
                        }
                    ]
                },
                {
                    xtype   : 'fieldcontainer',
                    margin  : '0 0 0 20',
                    defaults: { action: 'field' },
                    items   : [

                        {

                            boxLabel: 'Reportable?',
                            xtype   : 'checkboxfield',
                            name    : 'reportable'
                        },
                        {
                            boxLabel  : 'Active?',
                            labelWidth: 75,
                            xtype     : 'checkboxfield',
                            name      : 'active'
                        }
                    ]
                }
            ]
        });

        /**
         * ICD9 Container
         */
        me.icd9Container = Ext.create('Ext.container.Container', {
            layout: 'column',
            action: 'ICD9',
            //hidden: true,
            items : [

                {
                    xtype    : 'fieldcontainer',
                    msgTarget: 'under',
                    defaults : { action: 'field' },
                    items    : [
                        {

                            fieldLabel: 'Type',
                            xtype     : 'mitos.codestypescombo',
                            name      : 'code_type',
	                        readOnly: true
                        },
                        {

                            fieldLabel: 'Code',
                            xtype     : 'textfield',
                            name      : 'code',
	                        readOnly: true
                        }

                    ]
                },
                {
                    xtype   : 'fieldcontainer',
                    margin  : '0 0 0 10',
                    defaults: { action: 'field'  },
                    items   : [
                        {

                            fieldLabel: 'Description',
	                        width          : 570,
                            xtype     : 'textfield',
                            name      : 'code_text'
                        }
                    ]
                },
                {
                    xtype   : 'fieldcontainer',
                    margin  : '0 0 0 20',
                    defaults: { action: 'field'  },
                    items   : [

                        {

                            boxLabel  : 'Active?',
                            labelWidth: 75,
                            xtype     : 'checkboxfield',
                            name      : 'active'


                        }
                    ]
                }

            ]

        });

        /**
         * HCPSC Container
         */
        me.hpccsContainer = Ext.create('Ext.container.Container', {
            layout: 'column',
            action: 'HCPCS',
            //hidden: true,
            items : [

                {
                    xtype    : 'fieldcontainer',
                    msgTarget: 'under',
                    defaults : { action: 'field'  },
                    items    : [
                        {

                            fieldLabel: 'Type',
                            xtype     : 'mitos.codestypescombo',
                            name      : 'code_type'
                        },
                        {

                            fieldLabel: 'Code',
                            xtype     : 'textfield',
                            name      : 'code'
                        },
                        {

                            fieldLabel: 'Modifier',
                            xtype     : 'textfield',
                            name      : 'mod'
                        }

                    ]
                },
                {
                    xtype   : 'fieldcontainer',
                    margin  : '0 0 0 10',
                    defaults: { action: 'field' },
                    items   : [
                        {

                            fieldLabel: 'Description',
                            xtype     : 'textfield',
                            name      : 'code_text'
                        },
                        {
                            fieldLabel: 'Category',
                            xtype     : 'mitos.titlescombo',
                            name      : 'title'
                        }
                    ]
                },
                {
                    xtype   : 'fieldcontainer',
                    margin  : '0 0 0 20',
                    defaults: { action: 'field' },
                    items   : [

                        {

                            boxLabel: 'Reportable?',
                            xtype   : 'checkboxfield',
                            name    : 'reportable'

                        }
                        ,
                        {

                            boxLabel  : 'Active?',
                            labelWidth: 75,
                            xtype     : 'checkboxfield',
                            name      : 'active'


                        }
                    ]
                }

            ]

        });

        /**
         * CVX Container
         */
        me.cvxCintainer = Ext.create('Ext.tab.Panel', {
            //hidden   : true,
            action   : 'Immunizations',
            layout   : 'fit',
            plain    : true,
            listeners: {
                scope    : me,
                tabchange: me.onFormTapChange
            },
            items    : [
                {
                    title  : 'general',
                    xtype  : 'container',
                    padding: 10,
                    layout : 'vbox',
                    items  : [
                        {
                            /**
                             * line One
                             */
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0', action: 'field' },
                            items   : [
                                {

                                    xtype     : 'textfield',
                                    fieldLabel: 'Immunization Name',
                                    name      : 'code_text',
                                    labelWidth: 130,
                                    width     : 703
                                },
                                {
                                    xtype     : 'mitos.sexcombo',
                                    fieldLabel: 'Sex',
                                    name      : 'sex',
                                    width     : 100,
                                    labelWidth: 30

                                }

                            ]
                        },
                        {
                            /**
                             * Line two
                             */
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0', action: 'field'  },
                            items   : [
                                {
                                    xtype     : 'mitos.codestypescombo',
                                    fieldLabel: 'Coding System',
                                    labelWidth: 130,
                                    value     : 'CVX',
                                    name      : 'code_type',
                                    readOnly  : true

                                },
                                {
                                    xtype     : 'numberfield',
                                    fieldLabel: 'Frequency',
                                    margin    : '0 0 5 0',
                                    value     : 0,
                                    minValue  : 0,
                                    width     : 150,
                                    name      : 'frequency_number'

                                },
                                {
                                    xtype: 'mitos.timecombo',
                                    name : 'frequency_time',
                                    width: 100

                                },
                                {
                                    xtype     : 'numberfield',
                                    fieldLabel: 'Age Start',
                                    name      : 'age_start',
                                    labelWidth: 75,
                                    width     : 140,
                                    value     : 0,
                                    minValue  : 0

                                },
                                {
                                    fieldLabel: 'Must be pregnant',
                                    xtype     : 'checkboxfield',
                                    labelWidth: 105,
                                    name      : 'pregnant'


                                }
                            ]

                        },
                        {
                            /**
                             * Line three
                             */
                            xtype   : 'fieldcontainer',
                            layout  : 'hbox',
                            defaults: { margin: '0 10 5 0', action: 'field'  },
                            items   : [
                                {
                                    xtype     : 'textfield',
                                    fieldLabel: 'Code',
                                    name      : 'code',
                                    labelWidth: 130

                                },
                                {
                                    xtype     : 'numberfield',
                                    fieldLabel: 'Times to Perform',
                                    name      : 'times_to_perform',
                                    width     : 250,
                                    value     : 0,
                                    minValue  : 0,
                                    tooltip   : 'Please enter a number greater than 1 or just check "Perform once"'

                                },
                                {

                                    xtype     : 'numberfield',
                                    fieldLabel: 'Age End',
                                    name      : 'age_end',
                                    labelWidth: 75,
                                    width     : 140,
                                    value     : 0,
                                    minValue  : 0


                                },

                                {
                                    fieldLabel: 'perform only once',
                                    xtype     : 'checkboxfield',
                                    labelWidth: 105,
                                    //margin  : '5 0 0 10',
                                    name      : 'only_once'
                                }

                            ]

                        }

                    ]
                },
                {
                    title  : 'Active Problems',
                    action : 'problems',
                    xtype  : 'grid',
                    margin : 5,
                    store  : me.ImmuRelationStore,
                    columns: [

                        {
                            xtype: 'actioncolumn',
                            width: 20,
                            items: [
                                {
                                    icon   : 'ui_icons/delete.png',
                                    tooltip: 'Remove',
                                    scope  : me,
                                    handler: me.onRemoveRelation
                                }
                            ]
                        },
                        {
                            header   : 'Code',
                            width    : 100,
                            dataIndex: 'code'
                        },
                        {
                            header   : 'Description',
                            flex     : 1,
                            dataIndex: 'code_text'
                        }

                    ],
                    bbar   : {
                        xtype     : 'liveicdxsearch',
                        margin    : 5,
                        fieldLabel: 'Add Problem',
                        hideLabel : false,
                        disable   : true,
                        listeners : {
                            scope : me,
                            select: me.addActiveProblem
                        }
                    }
                },
                {
                    title  : 'Medications',
                    action : 'medications',
                    xtype  : 'grid',
                    width  : 300,
                    store  : me.ImmuRelationStore,
                    columns: [
                        {
                            xtype: 'actioncolumn',
                            width: 20,
                            items: [
                                {
                                    icon   : 'ui_icons/delete.png',
                                    tooltip: 'Remove',
                                    scope  : me,
                                    handler: me.onRemoveRelation
                                }
                            ]
                        },
                        {
                            header   : 'Code',
                            width    : 100,
                            dataIndex: 'code'
                        },
                        {
                            header   : 'Description',
                            flex     : 1,
                            dataIndex: 'code_text'
                        }

                    ],
                    bbar   : {
                        xtype     : 'medicationlivetsearch',
                        margin    : 5,
                        fieldLabel: 'Add Problem',
                        hideLabel : false,
                        disable   : true,
                        listeners : {
                            scope : me,
                            select: me.addMedications
                        }
                    }
                },
                {
                    title  : 'Labs',
                    action : 'labs',
                    xtype  : 'grid',
                    store  : me.ImmuRelationStore,
                    width  : 300,
                    columns: [
                        {
                            xtype: 'actioncolumn',
                            width: 20,
                            items: [
                                {
                                    icon   : 'ui_icons/delete.png',
                                    tooltip: 'Remove',
                                    scope  : me,
                                    handler: me.onRemoveRelation
                                }
                            ]
                        },
                        {
                            header   : 'Value Name',
                            flex     : 1,
                            dataIndex: 'value_name'
                        },
                        {
                            header   : 'Less Than',
                            flex     : 1,
                            dataIndex: 'less_than'
                        },
                        {
                            header   : 'Greater Than',
                            flex     : 1,
                            dataIndex: 'greater_than'
                        },
                        {
                            header   : 'Equal To',
                            flex     : 1,
                            dataIndex: 'equal_to'
                        }

                    ]
                }

            ]

        });

        /**
         * Labs Container
         */
        me.labContainer = Ext.create('Ext.container.Container', {
            action: 'Laboratories',
            layout: {
                type:'vbox',
                align:'stretch'
            },
            items : [
                {
                    /**
                     * line One
                     */
                    xtype   : 'fieldcontainer',
                    layout  : 'hbox',
                    defaults: { margin: '0 10 5 0', action: 'field' },
                    items   : [
                        {
                            xtype     : 'textfield',
                            fieldLabel: 'Short Name (alias)',
                            name      : 'code_text_short',
                            labelWidth: 130,
                            width     : 500
                        },
                        {
                            xtype     : 'mitos.checkbox',
                            fieldLabel: 'Active?',
                            name      : 'active',
                            anchor     : '100%',
                            labelWidth: 50

                        }
                    ]
                },
                {
                    xtype  : 'grid',
                    frame:true,
                    store:me.labObservationsStore,
                    plugins: Ext.create('Ext.grid.plugin.CellEditing', {
                        clicksToEdit: 2
                    }),
                    columns: [
                        {
                            header: 'Label (alias)',
                            dataIndex: 'code_text_short',
                            width:100,
                            editor:{
                                xtype:'textfield'
                            }
                        },
                        {
                            header: 'Loinc Name',
                            dataIndex: 'loinc_name',
                            width:200
                        },
                        {
                            header: 'Loinc Number',
                            dataIndex: 'loinc_number',
                            width:100
                        },
                        {
                            header: 'Default Unit',
                            dataIndex: 'default_unit',
                            width:100,
                            editor:{
                                xtype:'mitos.unitscombo'
                            }
                        },
                        {
                            header: 'Req / Opt',
                            dataIndex: 'required_in_panel',
                            width:75
                        },
                        {
                            header: 'Range Start',
                            dataIndex: 'range_start',
                            width:100,
                            editor:{
                                xtype:'numberfield'
                            }
                        },
                        {
                            header: 'Range End',
                            dataIndex: 'range_end',
                            width:100,
                            editor:{
                                xtype:'numberfield'
                            }
                        },
                        {
                            header: 'Description',
                            dataIndex: 'description',
                            flex:1,
                            editor:{
                                xtype:'textfield'
                            }
                        }
                    ]
//                    tbar:[
//                        {
//                            xtype:'labobservationscombo',
//                            fieldLabel:'Add Observation',
//                            width:300,
//                            listeners: {
//                                scope : me,
//                                select: me.onObservationSelect
//                            }
//                        },
//                        {
//                            text:'Add Observation',
//                            iconCls:'icoAddRecord',
//                            scope:me,
//                            handler:me.addLabObservation
//                        }
//                    ]
                }
            ]
        });

        me.dataManagerGrid = Ext.create('App.classes.GridPanel', {
            region : 'center',
            store  : me.store,
            viewConfig:{
                loadMask:true
            },
            columns: [
                {
                    width: 100,
                    header: 'Code Type',
                    sortable: true,
                    dataIndex: 'code_type',
                    renderer: code_type
                },
                {
                    width: 100,
                    header: 'Code',
                    sortable: true,
                    dataIndex: 'code'
                },
                {
                    header: 'Short Name',
                    dataIndex: 'code_text_short',
                    width:100,
                    flex: 1
                },
                {
                    header: 'Long Name',
                    sortable: true,
                    dataIndex: 'code_text',
                    flex: 2
                },
                {
                    width: 60,
                    header: 'Active?',
                    sortable: true,
                    dataIndex: 'active',
                    renderer: me.boolRenderer
                }
            ],
            plugins: Ext.create('App.classes.grid.RowFormEditing', {
                autoCancel  : false,
                errorSummary: false,
                clicksToEdit: 1,
                listeners   : {
                    scope     : me,
                    beforeedit: me.beforeServiceEdit
                }
            }),
            tbar   : Ext.create('Ext.PagingToolbar', {
                store      : me.store,
                displayInfo: true,
                emptyMsg   : "No Office Notes to display",
                plugins    : Ext.create('Ext.ux.SlidingPager', {}),
                items      : ['-', {
                    xtype    : 'mitos.codestypescombo',
                    width    : 150,
                    listeners: {
                        scope : me,
                        select: me.onCodeTypeSelect
                    }
                }, '-', {
                    text   : 'Add',
                    iconCls: 'icoAddRecord',
                    scope  : me,
                    handler: me.onAddData
                }, '-', {
                    xtype          : 'textfield',
                    emptyText      : 'Search',
                    width          : 200,
                    enableKeyEvents: true,
                    listeners      : {
                        scope : me,
                        keyup : me.onSearch,
                        buffer: 500
                    }
                }, '-', {
                    xtype       : 'button',
                    text        : 'Show Inactive Codes Only',
                    enableToggle: true,
                    listeners   : {
                        scope : me,
                        toggle: me.onActivePressed
                    }
                }]
            })
        }); // END GRID

        me.pageBody = [ me.dataManagerGrid ];
        me.callParent(arguments);
    },

    onAddData: function() {
        var me = this;
        if(me.code_type == 'Laboratories'){
            Ext.Msg.alert('Opps!',
                'Sorry, Unable to add laboratories. Laboratory data are pre-loaded using<br>' +
                'Logical Observation Identifiers Names and Codes (LOINC)<br>' +
                'visit <a href="http://loinc.org/" target="_blank">loinc.org</a> for more info.');
        }else{
            me.dataManagerGrid.plugins[0].cancelEdit();
            me.store.add({code_type:me.code_type});
            me.dataManagerGrid.plugins[0].startEdit(0,0);
        }
    },

    beforeServiceEdit: function(context, e) {
        var me = this,
            editor = context.editor,
            code_type = e.record.data.code_type,
            grids, thisForm;

        if(code_type == 'CPT4'){
            thisForm = me.cptContainer;
        }else if(code_type == 'ICD9'){
            thisForm = me.icd9Container;
        }else if(code_type == 'HCPCS'){
            thisForm = me.hpccsContainer;
        }else if(code_type == 'Immunizations'){
            thisForm = me.cvxCintainer;
        }else if(code_type == 'Laboratories'){
            thisForm = me.labContainer;
        }

        if(!editor.items.length){
            editor.add(thisForm);
            editor.setFields();
        }else if(this.currForm != thisForm){
            editor.remove(0, false);
            editor.add(thisForm);
            editor.setFields();
        }

        /**
         * find grids inside the form and load the its store with the row ID
         * @type {*}
         */
        grids = thisForm.query('grid');
        Ext.each(grids, function(grid){
            grid.getStore().load({params:{selectedId:me.getSelectId()}});
        });

        this.currForm = thisForm;

    },

    onSearch: function(field) {
        var me = this, store = me.store;
        me.dataQuery = field.getValue();
        store.proxy.extraParams = {active: me.active, code_type: me.code_type, query: me.dataQuery};
        me.store.load();
    },

    onCodeTypeSelect: function(combo, record) {
        var me = this, store = me.store;
        me.code_type = record[0].data.option_value;
        store.proxy.extraParams = {active: me.active, code_type: me.code_type, query: me.dataQuery};
        me.store.load();
    },

    onObservationSelect:function(combo, record){
        say(record[0].data);
        this.labObservationsStore.add({
            lab_id: this.getSelectId(),
            observation_element_id:record[0].data.id
        });
        combo.reset();
    },

    onActivePressed: function(btn, pressed) {
        var me = this, store = me.store;
        me.active = pressed ? 0 : 1;
        store.proxy.extraParams = {active: me.active, code_type: me.code_type, query: me.dataQuery};
        me.store.load();
    },

    onFormTapChange: function(panel, newCard, oldCard) {
        this.ImmuRelationStore.proxy.extraParams = { code_type: newCard.action, selectedId: this.getSelectId() };
        this.ImmuRelationStore.load();
    },

    addActiveProblem: function(field, model) {
        this.ImmuRelationStore.add({
            code           : model[0].data.code,
            code_text      : model[0].data.code_text,
            code_type      : 'problems',
            foreign_id     : model[0].data.id,
            immunization_id: this.getSelectId()
        });
        field.reset();
    },

    addMedications: function(field, model) {
        this.ImmuRelationStore.add({
            code           : model[0].data.PRODUCTNDC,
            code_text      : model[0].data.PROPRIETARYNAME,
            code_type      : 'medications',
            foreign_id     : model[0].data.id,
            immunization_id: this.getSelectId()
        });
        field.reset();

    },

    addLabObservation:function(){
        this.labObservationsStore.add({
            lab_id: this.getSelectId(),
            label:'',
            name:'',
            //unit:'M/uL (H)',
            range_start:'-99999',
            range_end:'99999'

        });
    },

    onRemoveRelation: function(grid, rowIndex) {
        var me = this,
            store = grid.getStore(),
            record = store.getAt(rowIndex);
        store.remove(record);
    },

    getSelectId:function(){
   		var row = this.dataManagerGrid.getSelectionModel().getLastSelected();
   		return row.data.id;
   	},

    /**
     * This function is called from MitosAPP.js when
     * this panel is selected in the navigation panel.
     * place inside this function all the functions you want
     * to call every this panel becomes active
     */
    onActive: function(callback) {
        this.dataManagerGrid.query('combobox')[0].setValue("CPT4");
        this.store.proxy.extraParams = {active: this.active, code_type: this.code_type, query: this.dataQuery};
        this.store.load();
        callback(true);
    }
}); //ens servicesPage class