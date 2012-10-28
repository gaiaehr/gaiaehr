/*
 GaiaEHR (Electronic Health Records)
 PreventiveCare.js
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
Ext.define('App.view.administration.PreventiveCare',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelPreventiveCare',
	pageTitle : i18n('preventive_care'),
	uses : ['App.ux.GridPanel', 'App.ux.combo.CodesTypes', 'App.ux.combo.Titles'],
	initComponent : function()
	{
		var me = this;

		me.active = 1;
		me.dataQuery = '';
		me.category_id = '3';

		me.store = Ext.create('App.store.administration.PreventiveCare');
		me.activeProblemsStore = Ext.create('App.store.administration.PreventiveCareActiveProblems');
		me.medicationsStore = Ext.create('App.store.administration.PreventiveCareMedications');
		me.labsStore = Ext.create('App.store.administration.PreventiveCareLabs');

		function code_type(val)
		{
			if (val == '1')
			{
				return 'CPT4';
			}
			else
			if (val == '2')
			{
				return 'ICD9';
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


		me.guidelineGrid = Ext.create('App.ux.GridPanel',
		{
			region : 'center',
			store : me.store,
			columns : [
			{
				xtype : 'actioncolumn',
				width : 30,
				items : [
				{
					icon : 'resources/images/icons/delete.png', // Use a URL in the icon config
					tooltip : i18n('remove'),
					handler : function(grid, rowIndex, colIndex)
					{
						var rec = grid.getStore().getAt(rowIndex);
					},
					getClass : function()
					{
						return 'x-grid-icon-padding';
					}
				}]
			},

			{
				flex : 1,
				header : i18n('description'),
				sortable : true,
				dataIndex : 'description'
			},
			{
				width : 100,
				header : i18n('age_start'),
				sortable : true,
				dataIndex : 'age_start'
			},
			{
				width : 100,
				header : i18n('age_end'),
				sortable : true,
				dataIndex : 'age_end'
			},
			{
				width : 100,
				header : i18n('sex'),
				sortable : true,
				dataIndex : 'sex'
			},
			{
				width : 100,
				header : i18n('frequency'),
				sortable : true,
				dataIndex : 'frequency'
			}],
			plugins : Ext.create('App.ux.grid.RowFormEditing',
			{
				autoCancel : false,
				errorSummary : false,
				clicksToEdit : 1,
				listeners :
				{
					scope : me,
					beforeedit : me.beforeServiceEdit,
					edit : me.onServiceEdit,
					canceledit : me.onServiceCancelEdit

				},
				formItems : [
				{
					/**
					 * CVX Container
					 */
					xtype : 'tabpanel',
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
								fieldLabel : i18n('description'),
								name : 'description',
								labelWidth : 130,
								width : 703
							},
							{
								xtype : 'mitos.sexcombo',
								fieldLabel : i18n('sex'),
								name : 'sex',
								width : 100,
								labelWidth : 30

							},
							{
								fieldLabel : i18n('active'),
								xtype : 'checkboxfield',
								labelWidth : 75,
								name : 'active'
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
								name : 'coding_system',
								readOnly : true

							},
							{
								xtype : 'numberfield',
								fieldLabel : i18n('frequency'),
								margin : '0 0 5 0',
								value : 0,
								minValue : 0,
								width : 150,
								name : 'frequency'

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
								name : 'only_once'
							}]

						}]
					},
					{
						title : i18n('active_problems'),
						action : 'problems',
						xtype : 'grid',
						margin : 5,
						store : me.activeProblemsStore,
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
						store : me.medicationsStore,
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
						store : me.labsStore,
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
							dataIndex : 'less_than',
							editor :
							{
								xtype : 'numberfield'
							}
						},
						{
							header : i18n('greater_than'),
							flex : 1,
							dataIndex : 'greater_than',
							editor :
							{
								xtype : 'numberfield'
							}
						},
						{
							header : i18n('equal_to'),
							flex : 1,
							dataIndex : 'equal_to',
							editor :
							{
								xtype : 'numberfield'
							}
						}],

						plugins : Ext.create('Ext.grid.plugin.CellEditing',
						{
							autoCancel : true,
							errorSummary : false,
							clicksToEdit : 2,
							listeners :
							{
								scope : me,
								edit : me.afterLabTimeEdit

							}

						}),
						bbar :
						{
							xtype : 'labslivetsearch',
							margin : 5,
							fieldLabel : i18n('add_labs'),
							hideLabel : false,
							listeners :
							{
								scope : me,
								select : me.addLabs
							}
						}
					}]

				}]
			}),

			tbar : me.PagingToolbar = Ext.create('Ext.PagingToolbar',
			{
				store : me.store,
				displayInfo : true,
				emptyMsg : i18n('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				}),
				items : ['-',
				{
					xtype : 'mitos.preventivecaretypescombo',
					width : 150,
					listeners :
					{
						scope : me,
						select : me.onCodeTypeSelect
					}
				}]
			})
		});
		// END GRID

		me.pageBody = [me.guidelineGrid];
		me.callParent(arguments);
	}, // end of initComponent

	onServiceEdit : function(context, e)
	{

	},

	onServiceCancelEdit : function(context, e)
	{

	},

	afterLabTimeEdit : function(editor, e)
	{

	},

	beforeServiceEdit : function(context, e)
	{
		var editor = context.editor, grids = editor.query('grid');
		for (var i = 0; i < grids.length; i++)
		{
			grids[i].store.load(
			{
				params :
				{
					id : e.record.data.id
				}
			});
		}
	},

	onFormTapChange : function(panel, newCard, oldCard)
	{
		//say(newCard);
		//this.ImmuRelationStore.proxy.extraParams = { code_type: newCard.action, selected_id:this.getSelectId() };
		//this.ImmuRelationStore.load();
	},

	//	onSearch: function(field) {
	//		var me = this,
	//			store = me.store;
	//		me.dataQuery = field.getValue();
	//
	//		store.proxy.extraParams = {active: me.active, code_type: me.code_type, query: me.dataQuery};
	//		me.store.load();
	//	},

	onCodeTypeSelect : function(combo, record)
	{
		var me = this;
		me.category_id = record[0].data.option_value;
		if (me.category_id == 'dismiss')
		{

		}
		else
		{
			me.PagingToolbar.moveFirst();
			//            me.store.proxy.pageParam = 1;
			//            me.store.proxy.startParam = 0;
			me.store.proxy.extraParams =
			{
				category_id : me.category_id
			};
			me.store.load();
		}
	},

	//	onNew: function(form, model) {
	//		form.getForm().reset();
	//		var newModel = Ext.ModelManager.create({}, model);
	//		form.getForm().loadRecord(newModel);
	//	},

	addActiveProblem : function(field, model)
	{

		this.activeProblemsStore.add(
		{
			code : model[0].data.code,
			code_text : model[0].data.code_text,
			guideline_id : this.getSelectId()
		});
		field.reset();
	},
	addMedications : function(field, model)
	{
		this.medicationsStore.add(
		{

			code : model[0].data.id,
			code_text : model[0].data.PROPRIETARYNAME,
			guideline_id : this.getSelectId()
		});
		field.reset();

	},
	addLabs : function(field, model)
	{

		this.labsStore.add(
		{
			code : model[0].data.loinc_number,
			value_name : model[0].data.loinc_name,
			less_than : '0',
			greater_than : '0',
			equal_to : '0',
			preventive_care_id : this.getSelectId()
		});
		field.reset();

	},

	onRemoveRelation : function(grid, rowIndex, colIndex)
	{
		var me = this, store = grid.getStore(), record = store.getAt(rowIndex);
		store.remove(record);
	},

	getSelectId : function()
	{
		var row = this.guidelineGrid.getSelectionModel().getLastSelected();
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
		this.guidelineGrid.query('combobox')[0].setValue(this.category_id);
		this.store.proxy.extraParams =
		{
			category_id : this.category_id
		};
		this.store.load();
		callback(true);
	}
});
//ens servicesPage class