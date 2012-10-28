/*
 GaiaEHR (Electronic Health Records)
 DataManager.js
 Data Manager View
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
Ext.define('App.view.administration.DataManager',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelDataManager',
	pageTitle : 'Data Manager',
	uses : ['App.ux.GridPanel', 'App.ux.combo.CodesTypes', 'App.ux.combo.Titles'],
	initComponent : function()
	{
		var me = this;
		me.active = 1;
		me.dataQuery = '';
		me.code_type = 'CPT4';
		me.store = Ext.create('App.store.administration.Services');
		me.activeProblemsStore = Ext.create('App.store.administration.ActiveProblems');
		me.medicationsStore = Ext.create('App.store.administration.Medications');
		me.ImmuRelationStore = Ext.create('App.store.administration.ImmunizationRelations');
		me.labObservationsStore = Ext.create('App.store.administration.LabObservations');
		function code_type(val)
		{
			if (val == '1')
			{
				return 'CPT4';
			}
			else
			if (val == '3')
			{
				return 'HCPCS';
			}
			else
			if (val == '100')
			{
				return 'CVX';
			}
			return val;
		}

		/**
		 * CPT Container
		 */
		me.cptContainer = Ext.create('Ext.container.Container',
		{
			layout : 'column',
			action : 'CPT4',
			//hidden: true,
			items : [
			{
				xtype : 'fieldcontainer',
				msgTarget : 'under',
				defaults :
				{
					action : 'field'
				},
				items : [
				{
					fieldLabel : 'Type',
					xtype : 'mitos.codestypescombo',
					name : 'code_type'
				},
				{
					fieldLabel : 'Code',
					xtype : 'textfield',
					name : 'code'
				}]
			},
			{
				xtype : 'fieldcontainer',
				margin : '0 0 0 10',
				defaults :
				{
					action : 'field'
				},
				items : [
				{
					fieldLabel : i18n('description'),
					xtype : 'textfield',
					name : 'code_text',
					width : 500
				}]
			},
			{
				xtype : 'fieldcontainer',
				margin : '0 0 0 20',
				defaults :
				{
					action : 'field'
				},
				items : [
				{
					boxLabel : i18n('reportable'),
					xtype : 'checkboxfield',
					name : 'reportable'
				},
				{
					boxLabel : i18n('active'),
					labelWidth : 75,
					xtype : 'checkboxfield',
					name : 'active'
				}]
			}]
		});
		/**
		 * HCPSC Container
		 */
		me.hpccsContainer = Ext.create('Ext.container.Container',
		{
			layout : 'column',
			action : 'HCPCS',
			//hidden: true,
			items : [
			{
				xtype : 'fieldcontainer',
				msgTarget : 'under',
				defaults :
				{
					action : 'field'
				},
				items : [
				{
					fieldLabel : i18n('type'),
					xtype : 'mitos.codestypescombo',
					name : 'code_type'
				},
				{
					fieldLabel : i18n('code'),
					xtype : 'textfield',
					name : 'code'
				},
				{
					fieldLabel : i18n('modifier'),
					xtype : 'textfield',
					name : 'mod'
				}]
			},
			{
				xtype : 'fieldcontainer',
				margin : '0 0 0 10',
				defaults :
				{
					action : 'field'
				},
				items : [
				{
					fieldLabel : i18n('description'),
					xtype : 'textfield',
					name : 'code_text'
				},
				{
					fieldLabel : i18n('category'),
					xtype : 'mitos.titlescombo',
					name : 'title'
				}]
			},
			{
				xtype : 'fieldcontainer',
				margin : '0 0 0 20',
				defaults :
				{
					action : 'field'
				},
				items : [
				{
					boxLabel : i18n('reportable'),
					xtype : 'checkboxfield',
					name : 'reportable'
				},
				{
					boxLabel : i18n('active'),
					labelWidth : 75,
					xtype : 'checkboxfield',
					name : 'active'
				}]
			}]
		});
		/**
		 * CVX Container
		 */
		me.cvxCintainer = Ext.create('Ext.tab.Panel',
		{
			//hidden   : true,
			action : i18n('immunizations'),
			layout : 'fit',
			plain : true,
			listeners :
			{
				scope : me,
				tabchange : me.onFormTapChange
			},
			items : [
			{
				title : i18n('general'),
				xtype : 'container',
				padding : 10,
				layout : 'vbox',
				items : [
				{
					/**
					 * line One
					 */
					xtype : 'fieldcontainer',
					layout : 'hbox',
					defaults :
					{
						margin : '0 10 5 0',
						action : 'field'
					},
					items : [
					{
						xtype : 'textfield',
						fieldLabel : i18n('immunization_name'),
						name : 'code_text',
						labelWidth : 130,
						width : 703
					},
					{
						xtype : 'mitos.sexcombo',
						fieldLabel : i18n('sex'),
						name : 'sex',
						width : 100,
						labelWidth : 30
					}]
				},
				{
					/**
					 * Line two
					 */
					xtype : 'fieldcontainer',
					layout : 'hbox',
					defaults :
					{
						margin : '0 10 5 0',
						action : 'field'
					},
					items : [
					{
						xtype : 'mitos.codestypescombo',
						fieldLabel : i18n('coding_system'),
						labelWidth : 130,
						value : 'CVX',
						name : 'code_type',
						readOnly : true
					},
					{
						xtype : 'numberfield',
						fieldLabel : i18n('frequency'),
						margin : '0 0 5 0',
						value : 0,
						minValue : 0,
						width : 150,
						name : 'frequency_number'
					},
					{
						xtype : 'mitos.timecombo',
						name : 'frequency_time',
						width : 100

					},
					{
						xtype : 'numberfield',
						fieldLabel : i18n('age_start'),
						name : 'age_start',
						labelWidth : 75,
						width : 140,
						value : 0,
						minValue : 0

					},
					{
						fieldLabel : i18n('must_be_pregnant'),
						xtype : 'checkboxfield',
						labelWidth : 105,
						name : 'pregnant'

					}]

				},
				{
					/**
					 * Line three
					 */
					xtype : 'fieldcontainer',
					layout : 'hbox',
					defaults :
					{
						margin : '0 10 5 0',
						action : 'field'
					},
					items : [
					{
						xtype : 'textfield',
						fieldLabel : i18n('code'),
						name : 'code',
						labelWidth : 130
					},
					{
						xtype : 'numberfield',
						fieldLabel : i18n('times_to_perform'),
						name : 'times_to_perform',
						width : 250,
						value : 0,
						minValue : 0,
						tooltip : i18n('greater_than_1_or_just_check_perform_once')
					},
					{
						xtype : 'numberfield',
						fieldLabel : i18n('age_end'),
						name : 'age_end',
						labelWidth : 75,
						width : 140,
						value : 0,
						minValue : 0
					},
					{
						fieldLabel : i18n('perform_only_once'),
						xtype : 'checkboxfield',
						labelWidth : 105,
						//margin  : '5 0 0 10',
						name : 'only_once'
					}]
				}]
			},
			{
				title : i18n('active_problems'),
				action : 'problems',
				xtype : 'grid',
				margin : 5,
				store : me.ImmuRelationStore,
				columns : [
				{
					xtype : 'actioncolumn',
					width : 20,
					items : [
					{
						icon : 'resources/images/icons/delete.png',
						tooltip : i18n('remove'),
						scope : me,
						handler : me.onRemoveRelation
					}]
				},
				{
					header : i18n('code'),
					width : 100,
					dataIndex : 'code'
				},
				{
					header : i18n('description'),
					flex : 1,
					dataIndex : 'code_text'
				}],
				bbar :
				{
					xtype : 'liveicdxsearch',
					margin : 5,
					fieldLabel : i18n('add_problem'),
					hideLabel : false,
					disable : true,
					listeners :
					{
						scope : me,
						select : me.addActiveProblem
					}
				}
			},
			{
				title : i18n('medications'),
				action : 'medications',
				xtype : 'grid',
				width : 300,
				store : me.ImmuRelationStore,
				columns : [
				{
					xtype : 'actioncolumn',
					width : 20,
					items : [
					{
						icon : 'resources/images/icons/delete.png',
						tooltip : i18n('remove'),
						scope : me,
						handler : me.onRemoveRelation
					}]
				},
				{
					header : i18n('code'),
					width : 100,
					dataIndex : 'code'
				},
				{
					header : i18n('description'),
					flex : 1,
					dataIndex : 'code_text'
				}],
				bbar :
				{
					xtype : 'medicationlivetsearch',
					margin : 5,
					fieldLabel : i18n('add_problem'),
					hideLabel : false,
					disable : true,
					listeners :
					{
						scope : me,
						select : me.addMedications
					}
				}
			},
			{
				title : i18n('labs'),
				action : 'labs',
				xtype : 'grid',
				store : me.ImmuRelationStore,
				width : 300,
				columns : [
				{
					xtype : 'actioncolumn',
					width : 20,
					items : [
					{
						icon : 'resources/images/icons/delete.png',
						tooltip : i18n('remove'),
						scope : me,
						handler : me.onRemoveRelation
					}]
				},
				{
					header : i18n('value_name'),
					flex : 1,
					dataIndex : 'value_name'
				},
				{
					header : i18n('less_than'),
					flex : 1,
					dataIndex : 'less_than'
				},
				{
					header : i18n('greater_than'),
					flex : 1,
					dataIndex : 'greater_than'
				},
				{
					header : i18n('equal_to'),
					flex : 1,
					dataIndex : 'equal_to'
				}]
			}]

		});
		/**
		 * Labs Container
		 */
		me.labContainer = Ext.create('Ext.container.Container',
		{
			action : i18n('laboratories'),
			layout :
			{
				type : 'vbox',
				align : 'stretch'
			},
			items : [
			{
				/**
				 * line One
				 */
				xtype : 'fieldcontainer',
				layout : 'hbox',
				defaults :
				{
					margin : '0 10 5 0',
					action : 'field'
				},
				items : [
				{
					xtype : 'textfield',
					fieldLabel : i18n('short_name_alias'),
					name : 'code_text_short',
					labelWidth : 130,
					width : 500
				},
				{
					xtype : 'mitos.checkbox',
					fieldLabel : i18n('active'),
					name : 'active',
					anchor : '100%',
					labelWidth : 50

				}]
			},
			{
				xtype : 'grid',
				frame : true,
				store : me.labObservationsStore,
				plugins : Ext.create('Ext.grid.plugin.CellEditing',
				{
					clicksToEdit : 2
				}),
				columns : [
				{
					header : i18n('label_alias'),
					dataIndex : 'code_text_short',
					width : 100,
					editor :
					{
						xtype : 'textfield'
					}
				},
				{
					header : i18n('loinc_name'),
					dataIndex : 'loinc_name',
					width : 200
				},
				{
					header : i18n('loinc_number'),
					dataIndex : 'loinc_number',
					width : 100
				},
				{
					header : i18n('default_unit'),
					dataIndex : 'default_unit',
					width : 100,
					editor :
					{
						xtype : 'mitos.unitscombo'
					}
				},
				{
					header : i18n('req_opt'),
					dataIndex : 'required_in_panel',
					width : 75
				},
				{
					header : i18n('range_start'),
					dataIndex : 'range_start',
					width : 100,
					editor :
					{
						xtype : 'numberfield'
					}
				},
				{
					header : i18n('range_end'),
					dataIndex : 'range_end',
					width : 100,
					editor :
					{
						xtype : 'numberfield'
					}
				},
				{
					header : i18n('description'),
					dataIndex : 'description',
					flex : 1,
					editor :
					{
						xtype : 'textfield'
					}
				}]
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
			}]
		});
		me.dataManagerGrid = Ext.create('App.ux.GridPanel',
		{
			region : 'center',
			store : me.store,
			viewConfig :
			{
				loadMask : true
			},
			columns : [
			{
				width : 100,
				header : i18n('code_type'),
				sortable : true,
				dataIndex : 'code_type',
				renderer : code_type
			},
			{
				width : 100,
				header : i18n('code'),
				sortable : true,
				dataIndex : 'code'
			},
			{
				header : i18n('short_name'),
				dataIndex : 'code_text_short',
				width : 100,
				flex : 1
			},
			{
				header : i18n('long_name'),
				sortable : true,
				dataIndex : 'code_text',
				flex : 2
			},
			{
				width : 60,
				header : i18n('active'),
				sortable : true,
				dataIndex : 'active',
				renderer : me.boolRenderer
			}],
			plugins : Ext.create('App.ux.grid.RowFormEditing',
			{
				autoCancel : false,
				errorSummary : false,
				clicksToEdit : 1,
				listeners :
				{
					scope : me,
					beforeedit : me.beforeServiceEdit
				}
			}),
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.store,
				displayInfo : true,
				emptyMsg : i18n('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : ['-',
				{
					xtype : 'mitos.codestypescombo',
					width : 150,
					listeners :
					{
						scope : me,
						select : me.onCodeTypeSelect
					}
				}, '-',
				{
					text : i18n('add'),
					iconCls : 'icoAddRecord',
					scope : me,
					handler : me.onAddData
				}, '-',
				{
					xtype : 'textfield',
					emptyText : i18n('search'),
					width : 200,
					enableKeyEvents : true,
					listeners :
					{
						scope : me,
						keyup : me.onSearch,
						buffer : 500
					}
				}, '-',
				{
					xtype : 'button',
					text : i18n('show_inactive_codes_only'),
					enableToggle : true,
					listeners :
					{
						scope : me,
						toggle : me.onActivePressed
					}
				}]
			})
		});
		// END GRID
		me.pageBody = [me.dataManagerGrid];
		me.callParent();
	},
	onAddData : function()
	{
		var me = this;
		if (me.code_type == 'Laboratories')
		{
			Ext.Msg.alert('Opps!', i18n('ops_laboratories'));
		}
		else
		{
			me.dataManagerGrid.plugins[0].cancelEdit();
			me.store.add(
			{
				code_type : me.code_type
			});
			me.dataManagerGrid.plugins[0].startEdit(0, 0);
		}
	},
	beforeServiceEdit : function(context, e)
	{
		var me = this, editor = context.editor, code_type = e.record.data.code_type, grids, thisForm;
		if (code_type == 'CPT4')
		{
			thisForm = me.cptContainer;
		}
		else
		if (code_type == 'HCPCS')
		{
			thisForm = me.hpccsContainer;
		}
		else
		if (code_type == 'Immunizations')
		{
			thisForm = me.cvxCintainer;
		}
		else
		if (code_type == 'Laboratories')
		{
			thisForm = me.labContainer;
		}
		if (!editor.items.length)
		{
			editor.add(thisForm);
			editor.setFields();
		}
		else
		if (this.currForm != thisForm)
		{
			editor.remove(0, false);
			editor.add(thisForm);
			editor.setField();
		}
		/**
		 * find grids inside the form and load the its store with the row ID
		 * @type {*}
		 */
		if (thisForm)
		{
			grids = thisForm.query('grid');
			for (var i = 0; i < grids.length; i++)
			{
				grids[i].getStore().load(
				{
					params :
					{
						selectedId : me.getSelectId()
					}
				});
			}
			this.currForm = thisForm;
		}
	},
	onSearch : function(field)
	{
		var me = this, store = me.store;
		me.dataQuery = field.getValue();
		store.proxy.extraParams =
		{
			active : me.active,
			code_type : me.code_type,
			query : me.dataQuery
		};
		me.store.load();
	},
	onCodeTypeSelect : function(combo, record)
	{
		var me = this, store = me.store;
		me.code_type = record[0].data.option_value;
		store.proxy.extraParams =
		{
			active : me.active,
			code_type : me.code_type,
			query : me.dataQuery
		};
		me.store.load();
	},
	//        onObservationSelect:function(combo, record){
	//            say(record[0].data);
	//            this.labObservationsStore.add({
	//                    lab_id:this.getSelectId(),
	//                    observation_element_id:record[0].data.id
	//                });
	//            combo.reset();
	//        },
	onActivePressed : function(btn, pressed)
	{
		var me = this, store = me.store;
		me.active = pressed ? 0 : 1;
		store.proxy.extraParams =
		{
			active : me.active,
			code_type : me.code_type,
			query : me.dataQuery
		};
		me.store.load();
	},
	onFormTapChange : function(panel, newCard, oldCard)
	{
		this.ImmuRelationStore.proxy.extraParams =
		{
			code_type : newCard.action,
			selectedId : this.getSelectId()
		};
		this.ImmuRelationStore.load();
	},
	addActiveProblem : function(field, model)
	{
		this.ImmuRelationStore.add(
		{
			code : model[0].data.code,
			code_text : model[0].data.code_text,
			code_type : 'problems',
			foreign_id : model[0].data.id,
			immunization_id : this.getSelectId()
		});
		field.reset();
	},
	addMedications : function(field, model)
	{
		this.ImmuRelationStore.add(
		{
			code : model[0].data.PRODUCTNDC,
			code_text : model[0].data.PROPRIETARYNAME,
			code_type : 'medications',
			foreign_id : model[0].data.id,
			immunization_id : this.getSelectId()
		});
		field.reset();
	},
	addLabObservation : function()
	{
		this.labObservationsStore.add(
		{
			lab_id : this.getSelectId(),
			label : '',
			name : '',
			//unit:'M/uL (H)',
			range_start : '-99999',
			range_end : '99999'

		});
	},
	onRemoveRelation : function(grid, rowIndex)
	{
		var me = this, store = grid.getStore(), record = store.getAt(rowIndex);
		store.remove(record);
	},
	getSelectId : function()
	{
		var row = this.dataManagerGrid.getSelectionModel().getLastSelected();
		return row.data.id;
	},
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		this.dataManagerGrid.query('combobox')[0].setValue("CPT4");
		this.store.proxy.extraParams =
		{
			active : this.active,
			code_type : this.code_type,
			query : this.dataQuery
		};
		this.store.load();
		callback(true);
	}
});
//ens servicesPage class