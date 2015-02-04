/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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

Ext.define('App.view.administration.DataManager', {
	extend: 'App.ux.RenderPanel',
	pageTitle: 'Data Manager',
	requires: [
		'App.view.administration.CPT',
		'App.ux.combo.CodesTypes',
		'App.ux.combo.Titles'
	],
	initComponent: function(){
		var me = this;
		me.active = 1;
		me.dataQuery = '';
		me.code_type = 'LOINC';
		me.store = Ext.create('App.store.administration.Services');
		me.activeProblemsStore = Ext.create('App.store.administration.ActiveProblems');
		me.medicationsStore = Ext.create('App.store.administration.Medications');
		me.ImmuRelationStore = Ext.create('App.store.administration.ImmunizationRelations');
		me.labObservationsStore = Ext.create('App.store.administration.LabObservations');


		function code_type(val){
			if(val == '1'){
				return 'CPT4';
			}
			else if(val == '3'){
				return 'HCPCS';
			}
			else if(val == '100'){
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
			items: [
				{
					xtype: 'fieldcontainer',
					msgTarget: 'under',
					defaults: {
						action: 'field'
					},
					items: [
						{
							fieldLabel: 'Type',
							xtype: 'mitos.codestypescombo',
							name: 'code_type'
						},
						{
							fieldLabel: 'Code',
							xtype: 'textfield',
							name: 'code'
						}
					]
				},
				{
					xtype: 'fieldcontainer',
					margin: '0 0 0 10',
					defaults: {
						action: 'field'
					},
					items: [
						{
							fieldLabel: _('description'),
							xtype: 'textfield',
							name: 'code_text',
							width: 500
						}
					]
				},
				{
					xtype: 'fieldcontainer',
					margin: '0 0 0 20',
					defaults: {
						action: 'field'
					},
					items: [
						{
							boxLabel: _('radiology'),
							xtype: 'checkboxfield',
							name: 'isRadiology'
						},
						{
							boxLabel: _('active'),
							labelWidth: 75,
							xtype: 'checkboxfield',
							name: 'active'
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
			items: [
				{
					xtype: 'fieldcontainer',
					msgTarget: 'under',
					defaults: {
						action: 'field'
					},
					items: [
						{
							fieldLabel: _('type'),
							xtype: 'mitos.codestypescombo',
							name: 'code_type'
						},
						{
							fieldLabel: _('code'),
							xtype: 'textfield',
							name: 'code'
						},
						{
							fieldLabel: _('modifier'),
							xtype: 'textfield',
							name: 'mod'
						}
					]
				},
				{
					xtype: 'fieldcontainer',
					margin: '0 0 0 10',
					defaults: {
						action: 'field'
					},
					items: [
						{
							fieldLabel: _('description'),
							xtype: 'textfield',
							name: 'code_text'
						},
						{
							fieldLabel: _('category'),
							xtype: 'mitos.titlescombo',
							name: 'title'
						}
					]
				},
				{
					xtype: 'fieldcontainer',
					margin: '0 0 0 20',
					defaults: {
						action: 'field'
					},
					items: [
						{
							boxLabel: _('reportable'),
							xtype: 'checkboxfield',
							name: 'reportable'
						},
						{
							boxLabel: _('active'),
							labelWidth: 75,
							xtype: 'checkboxfield',
							name: 'active'
						}
					]
				}
			]
		});
		/**
		 * CVX Container
		 */
		me.cvxCintainer = Ext.create('Ext.container.Container', {
			action: _('immunizations'),
			layout: 'fit',
			items: [
				{

				}
			]

		});
		/**
		 * Labs Container
		 */
		me.labContainer = Ext.create('Ext.container.Container', {
			action: _('laboratories'),
			layout: {
				type: 'vbox',
				align: 'stretch'
			},
			items: [
				{
					/**
					 * line One
					 */
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaults: {
						margin: '0 10 5 0',
						action: 'field'
					},
					items: [
						{
							xtype: 'textfield',
							fieldLabel: _('short_name_alias'),
							name: 'code_text_short',
							labelWidth: 130,
							width: 500
						},
						{
							xtype: 'checkbox',
							fieldLabel: _('active'),
							name: 'active',
							anchor: '100%',
							labelWidth: 50

						}
					]
				},
				{
					xtype: 'grid',
					frame: true,
					title: _('children'),
					store: me.labObservationsStore,
					plugins: Ext.create('Ext.grid.plugin.CellEditing', {
						clicksToEdit: 2
					}),
					columns: [
						{
							header: _('label_alias'),
							dataIndex: 'code_text_short',
							width: 150,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							header: _('loinc_name'),
							dataIndex: 'loinc_name',
							flex: 1
						},
						{
							header: _('loinc_number'),
							dataIndex: 'loinc_number',
							width: 100
						},
						{
							header: _('default_unit'),
							dataIndex: 'default_unit',
							width: 100,
							editor: {
								xtype: 'mitos.unitscombo'
							}
						},
						{
							header: _('req_opt'),
							dataIndex: 'required_in_panel',
							width: 75
						},
						{
							header: _('range_start'),
							dataIndex: 'range_start',
							width: 100,
							editor: {
								xtype: 'numberfield'
							}
						},
						{
							header: _('range_end'),
							dataIndex: 'range_end',
							width: 100,
							editor: {
								xtype: 'numberfield'
							}
						},
						{
							header: _('description'),
							dataIndex: 'description',
							flex: 1,
							editor: {
								xtype: 'textfield'
							}
						},
						{
							width: 60,
							header: _('active'),
							dataIndex: 'active',
							renderer: me.boolRenderer,
							editor: {
								xtype: 'checkbox'
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

		me.dataManagerGrid = Ext.create('Ext.grid.Panel', {
			title: 'Codes',
			region: 'center',
			store: me.store,
			viewConfig: {
				loadMask: true
			},
			columns: [
				{
					width: 50,
//					header: _('code_type'),
					sortable: false,
					dataIndex: 'code_type',
					renderer: code_type
				},
				{
					width: 60,
					header: _('code'),
					sortable: true,
					dataIndex: 'code'
				},
				{
					header: _('short_name'),
					dataIndex: 'code_text_short',
					width: 100,
					flex: 1
				},
				{
					header: _('long_name'),
					sortable: true,
					dataIndex: 'code_text',
					flex: 2
				},
				{
					width: 60,
					header: _('active'),
					sortable: true,
					dataIndex: 'active',
					renderer: me.boolRenderer
				}
			],
			plugins: Ext.create('App.ux.grid.RowFormEditing', {
				autoCancel: false,
				errorSummary: false,
				clicksToEdit: 1,
				listeners: {
					scope: me,
					beforeedit: me.beforeServiceEdit
				}
			}),
			tbar: this.bar = Ext.create('Ext.PagingToolbar', {
				store: me.store,
				displayInfo: true,
				emptyMsg: _('no_office_notes_to_display'),
				plugins: Ext.create('Ext.ux.SlidingPager'),
				items: [
					'-',
					{
						xtype: 'mitos.codestypescombo',
						width: 150,
						listeners: {
							scope: me,
							select: me.onCodeTypeSelect
						}
					}, '-',
					{
						text: _('add'),
						iconCls: 'icoAddRecord',
						scope: me,
						handler: me.onAddData
					}, '-',
					{
						xtype: 'textfield',
						emptyText: _('search'),
						width: 200,
						enableKeyEvents: true,
						listeners: {
							scope: me,
							keyup: me.onSearch,
							buffer: 500
						}
					}, '-',
					{
						xtype: 'button',
						text: _('show_inactive_codes_only'),
						enableToggle: true,
						listeners: {
							scope: me,
							toggle: me.onActivePressed
						}
					}
				]
			})
		});
		// END GRID

		me.tabPanel = Ext.widget('tabpanel',{
			items:[
				{
					xtype: 'cptadmingrid'
				},
				me.dataManagerGrid
			]
		});

		me.pageBody = [ me.tabPanel ];




		me.callParent();
	},
	onAddData: function(){
		var me = this;
		if(me.code_type == 'Laboratories'){
			Ext.Msg.alert('Opps!', _('ops_laboratories'));
		} else{
			me.dataManagerGrid.plugins[0].cancelEdit();
			me.store.add({
				code_type: me.code_type
			});
			me.dataManagerGrid.plugins[0].startEdit(0, 0);
		}
	},
	beforeServiceEdit: function(context, e){

		var me = this,
			editor = context.editor,
			code_type = e.record.data.code_type,
			grids,
			thisForm;

		if(code_type == 'CPT4'){
			thisForm = me.cptContainer;
		}else if(code_type == 'HCPCS'){
			thisForm = me.hpccsContainer;
		}else if(code_type == 'CVX'){
			thisForm = me.cvxCintainer;
		}else if(code_type == 'LOINC'){

			me.labContainer.down('grid').setTitle(
				e.record.data.has_children ? _('observations'):_('observation')
			);

			me.labContainer.down('grid').setVisible(e.record.data.class != 'RAD');
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
		if(thisForm){
			grids = thisForm.query('grid');
			for(var i = 0; i < grids.length; i++){
				grids[i].getStore().load({
					params: {
						selectedId: me.getSelectId()
					}
				});
			}
			this.currForm = thisForm;
		}
	},

	onSearch: function(field){
		var me = this,
			store = me.store;

		me.dataQuery = field.getValue();
		store.proxy.extraParams = {
			active: me.active,
			code_type: me.code_type,
			query: me.dataQuery
		};
		me.store.loadPage(1);
	},

	onCodeTypeSelect: function(combo, record){
		var me = this,
			store = me.store;

		me.code_type = record[0].data.option_value;
		store.proxy.extraParams = {
			active: me.active,
			code_type: me.code_type,
			query: me.dataQuery
		};
		me.store.loadPage(1);
	},
	//        onObservationSelect:function(combo, record){
	//            say(record[0].data);
	//            this.labObservationsStore.add({
	//                    lab_id:this.getSelectId(),
	//                    observation_element_id:record[0].data.id
	//                });
	//            combo.reset();
	//        },

	onActivePressed: function(btn, pressed){
		var me = this,
			store = me.store;

		me.active = !pressed;
		store.proxy.extraParams = {
			active: me.active,
			code_type: me.code_type,
			query: me.dataQuery
		};
		me.store.load();
	},

//	onFormTapChange: function(panel, newCard, oldCard){
//		this.ImmuRelationStore.proxy.extraParams = {
//			code_type: newCard.action,
//			selectedId: this.getSelectId()
//		};
//		this.ImmuRelationStore.load();
//	},

//	addActiveProblem: function(field, model){
//		this.ImmuRelationStore.add({
//			code: model[0].data.code,
//			code_text: model[0].data.code_text,
//			code_type: 'problems',
//			foreign_id: model[0].data.id,
//			immunization_id: this.getSelectId()
//		});
//		field.reset();
//	},

//	addMedications: function(field, model){
//		this.ImmuRelationStore.add({
//			code: model[0].data.PRODUCTNDC,
//			code_text: model[0].data.PROPRIETARYNAME,
//			code_type: 'medications',
//			foreign_id: model[0].data.id,
//			immunization_id: this.getSelectId()
//		});
//		field.reset();
//	},

//	addLabObservation: function(){
//		this.labObservationsStore.add({
//			lab_id: this.getSelectId(),
//			label: '',
//			name: '',
//			//unit:'M/uL (H)',
//			range_start: '-99999',
//			range_end: '99999'
//
//		});
//	},

//	onRemoveRelation: function(grid, rowIndex){
//		var me = this,
//			store = grid.getStore(),
//			record = store.getAt(rowIndex);
//		store.remove(record);
//	},

	getSelectId: function(){
		var row = this.dataManagerGrid.getSelectionModel().getLastSelected();
		return row.data.id;
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		this.bar.query('combobox')[0].setValue("CPT4");
		this.store.proxy.extraParams = {
			active: this.active,
			code_type: this.code_type,
			query: this.dataQuery
		};
		this.store.load();
		callback(true);
	}
});
//ens servicesPage class