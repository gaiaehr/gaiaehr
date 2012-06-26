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
Ext.define('App.view.administration.PreventiveCare', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelPreventiveCare',
	pageTitle    : 'Preventive Care',
	uses         : [
		'App.classes.GridPanel',
		'App.classes.combo.CodesTypes',
		'App.classes.combo.Titles'
	],
	initComponent: function() {
		var me = this;

		me.active = 1;
		me.dataQuery = '';
		me.category_id = '3';

		me.store = Ext.create('App.store.administration.PreventiveCare');
        me.activeProblemsStore = Ext.create('App.store.administration.PreventiveCareActiveProblems');
		me.medicationsStore = Ext.create('App.store.administration.PreventiveCareMedications');

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

		me.servicesGrid = Ext.create('App.classes.GridPanel', {
			region : 'center',
			store  : me.store,
			columns: [
                {
                    xtype: 'actioncolumn',
                    width:30,
                    items: [
                        {
                            icon: 'ui_icons/delete.png',  // Use a URL in the icon config
                            tooltip: 'Remove',
                            handler: function(grid, rowIndex, colIndex) {
                                var rec = grid.getStore().getAt(rowIndex);
                                //alert("Edit " + rec.get('firstname'));
                                say(rec);
                            },
                            getClass:function(){
                                return 'x-grid-icon-padding';
                            }
                        }
                    ]
                },
				{ width: 100, header: 'Type', sortable: true, dataIndex: 'code_type', renderer: code_type },
				{ flex: 1, header: 'Description', sortable: true, dataIndex: 'description' },
				{ width: 100, header: 'Age start', sortable: true, dataIndex: 'age_start' },
				{ width: 100, header: 'Age End', sortable: true, dataIndex: 'age_end' },
				{ width: 100, header: 'Sex', sortable: true, dataIndex: 'sex' },
				{ width: 100, header: 'Frequency', sortable: true, dataIndex: 'frequency' }

			],
			plugins: Ext.create('App.classes.grid.RowFormEditing', {
				autoCancel  : false,
				errorSummary: false,
				clicksToEdit: 1,
                listeners:{
                    scope:me,
	                beforeedit:me.beforeServiceEdit,
                    edit:me.onServiceEdit,
                    canceledit:me.onServiceCancelEdit

                },
				formItems   : [
					{
						/**
						 * CVX Container
						 */
						xtype: 'tabpanel',
						action:'Immunizations',
						layout:'fit',
						plain:true,
						listeners: {
							scope:me,
							tabchange:me.onFormTapChange
						},
						items: [
							{
								title : 'general',
								xtype : 'container',
								padding:10,
								layout:'vbox',
								items : [
									{
										/**
										 * line One
										 */
										xtype   : 'fieldcontainer',
										layout:'hbox',
										defaults:{ margin:'0 10 5 0', action:'field' },
										items   : [
											{

												xtype     : 'textfield',
												fieldLabel: 'Description',
												name      : 'description',
												labelWidth:130,
												width:703
											},
											{
												xtype     : 'mitos.sexcombo',
												fieldLabel: 'Sex',
												name      : 'sex',
												width     : 100,
												labelWidth: 30

											},
											{
												fieldLabel: 'Active',
												xtype   : 'checkboxfield',
												labelWidth:75,
												name    : 'active'
											}



										]
									},
									{
										/**
										 * Line two
										 */
										xtype   : 'fieldcontainer',
										layout:'hbox',
										defaults:{ margin:'0 10 5 0', action:'field'  },
										items   : [
											{
												xtype     : 'mitos.codestypescombo',
												fieldLabel: 'Coding System',
												labelWidth:130,
												value     : 'CVX',
												name      : 'coding_system',
												readOnly:true

											},
											{
												xtype     : 'numberfield',
												fieldLabel: 'Frequency',
												margin:'0 0 5 0',
												value     : 0,
												minValue  : 0,
												width:150,
												name      : 'frequency'

											},
											{
												xtype: 'mitos.timecombo',
												name : 'frequency_time',
												width:100

											},
											{
                                                xtype     : 'numberfield',
                                                fieldLabel: 'Age Start',
                                                name: 'age_start',
                                                labelWidth: 75,
                                                width:140,
                                                value     : 0,
                                                minValue  : 0

											},
                                            {
                                                fieldLabel: 'Must be pregnant',
                                                xtype   : 'checkboxfield',
                                                labelWidth:105,
                                                name    : 'pregnant'


                                            }
										]

									},
									{
										/**
										 * Line three
										 */
										xtype   : 'fieldcontainer',
										layout:'hbox',
										defaults:{ margin:'0 10 5 0', action:'field'  },
										items   : [
											{
												xtype     : 'textfield',
												fieldLabel: 'Code',
												name      : 'code',
												labelWidth:130
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
                                                name: 'age_end',
                                                labelWidth: 75,
                                                width:140,
                                                value     : 0,
                                                minValue  : 0


											},
                                            {
                                                fieldLabel: 'perform only once',
                                                xtype   : 'checkboxfield',
                                                labelWidth:105,
                                                name    : 'only_once'
                                            }



										]

									}

								]
							},
							{
								title  : 'Active Problems',
								action:'problems',
								xtype  : 'grid',
								margin:5,
								store: me.activeProblemsStore,
								columns: [

									{
										xtype:'actioncolumn',
										width:20,
										items: [
											{
												icon: 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope:me,
												handler: me.onRemoveRelation
											}
										]
									},
									{
										header   : 'Code',
										width     : 100,
										dataIndex: 'code'
									},
									{
										header   : 'Description',
										flex     : 1,
										dataIndex: 'code_text'
									}

								],
								bbar:{
									xtype:'liveicdxsearch',
									margin:5,
									fieldLabel:'Add Problem',
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.addActiveProblem
									}
								}
							},
							{
								title  : 'Medications',
								action :'medications',
								xtype  : 'grid',
								width  : 300,
								store: me.medicationsStore,
								columns: [
									{
										xtype:'actioncolumn',
										width:20,
										items: [
											{
												icon: 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope:me,
												handler: me.onRemoveRelation
											}
										]
									},
									{
										header   : 'Code',
										width     : 100,
										dataIndex: 'code'
									},
									{
										header   : 'Description',
										flex     : 1,
										dataIndex: 'code_text'
									}

								],
								bbar:{
									xtype:'medicationlivetsearch',
									margin:5,
									fieldLabel:'Add Problem',
									hideLabel:false,
									listeners:{
										scope:me,
										select:me.addMedications
									}
								}
							},
							{
								title  : 'Labs',
								action:'labs',
								xtype  : 'grid',
								//store: me.ImmuRelationStore,
								width  : 300,
								columns: [
									{
										xtype:'actioncolumn',
										width:20,
										items: [
											{
												icon: 'ui_icons/delete.png',
												tooltip: 'Remove',
												scope:me,
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

					}

				]
			}),


			tbar: Ext.create('Ext.PagingToolbar', {
				store      : me.store,
				displayInfo: true,
				emptyMsg   : "No Office Notes to display",
				plugins    : Ext.create('Ext.ux.SlidingPager', {}),
				items      : [
                    '-',
                    {
                        xtype    : 'mitos.preventivecaretypescombo',
                        width    : 150,
                        listeners: {
                            scope : me,
                            select: me.onCodeTypeSelect
                        }
                    }
                ]
			})
		}); // END GRID


		me.pageBody = [ me.servicesGrid ];
		me.callParent(arguments);
	}, // end of initComponent


    onServiceEdit:function(context, e){

    },

    onServiceCancelEdit:function(context, e){

    },

    beforeServiceEdit:function(context, e){
		var editor = context.editor,
			grids = editor.query('grid');

        Ext.each(grids,function(grid){
            grid.store.load({params:{id: e.record.data.id}});
        });
    },

	onFormTapChange:function(panel, newCard, oldCard){
        //say(newCard);
		//this.ImmuRelationStore.proxy.extraParams = { code_type: newCard.action, selected_id:this.getSelectId() };
		//this.ImmuRelationStore.load();
	},

	onSearch: function(field) {
		var me = this,
			store = me.store;
		me.dataQuery = field.getValue();

		store.proxy.extraParams = {active: me.active, code_type: me.code_type, query: me.dataQuery};
		me.store.load();
	},

	onCodeTypeSelect: function(combo, record) {
		var me = this;
		me.category_id = record[0].data.option_value;
		if(me.category_id=='dismiss'){

		}else{
		me.store.load({params:{category_id: me.category_id}});
		}
	},

	onNew: function(form, model) {
//		form.getForm().reset();
//		var newModel = Ext.ModelManager.create({}, model);
//		form.getForm().loadRecord(newModel);
	},

	addActiveProblem:function(field, model){

		this.activeProblemsStore.add({
			code:model[0].data.code,
			code_text:model[0].data.code_text,
            guideline_id: this.getSelectId()
		});
		field.reset();
	},
	addMedications:function(field, model){
		this.medicationsStore.add({
			code:model[0].data.PRODUCTNDC,
			code_text:model[0].data.PROPRIETARYNAME,
            guideline_id: this.getSelectId()
		});
		field.reset();

	},

    onRemoveRelation:function(grid, rowIndex, colIndex){
		var me = this,
            store = grid.getStore(),
			record = store.getAt(rowIndex);
        store.remove(record);
	},


	getSelectId:function(){
		var row = this.servicesGrid.getSelectionModel().getLastSelected();
		return row.data.id;
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		this.servicesGrid.query('combobox')[0].setValue(this.category_id);
		this.store.load({params:{category_id: this.category_id}});
		callback(true);
	}
}); //ens servicesPage class