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

Ext.define('App.view.administration.PreventiveCare',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelPreventiveCare',
	pageTitle : _('preventive_care'),
	uses : ['Ext.grid.Panel', 'App.ux.combo.CodesTypes', 'App.ux.combo.Titles'],
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


		me.guidelineGrid = Ext.create('Ext.grid.Panel',
		{
			region : 'center',
			store : me.store,
			columns : [
			{
				xtype : 'actioncolumn',
				width : 30,
				items : [
				{
					icon : 'resources/images/icons/delete.png',
					tooltip : _('remove'),
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
				header : _('description'),
				sortable : true,
				dataIndex : 'description'
			},
			{
				width : 100,
				header : _('age_start'),
				sortable : true,
				dataIndex : 'age_start'
			},
			{
				width : 100,
				header : _('age_end'),
				sortable : true,
				dataIndex : 'age_end'
			},
			{
				width : 100,
				header : _('sex'),
				sortable : true,
				dataIndex : 'sex'
			},
			{
				width : 100,
				header : _('frequency'),
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
				items : [
				{
					/**
					 * CVX Container
					 */
					xtype : 'tabpanel',
					action : _('immunizations'),
					layout : 'fit',
					plain : true,
					listeners :
					{
						scope : me,
						tabchange : me.onFormTapChange
					},
					items : [
					{
						title : _('general'),
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
								fieldLabel : _('description'),
								name : 'description',
								labelWidth : 130,
								width : 703
							},
							{
								xtype : 'gaiaehr.sexcombo',
								fieldLabel : _('sex'),
								name : 'sex',
								width : 100,
								labelWidth : 30

							},
							{
								fieldLabel : _('active'),
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
								fieldLabel : _('coding_system'),
								labelWidth : 130,
								value : 'CVX',
								name : 'coding_system',
								readOnly : true

							},
							{
								xtype : 'numberfield',
								fieldLabel : _('frequency'),
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
								fieldLabel : _('age_start'),
								name : 'age_start',
								labelWidth : 75,
								width : 140,
								value : 0,
								minValue : 0

							},
							{
								fieldLabel : _('must_be_pregnant'),
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
								fieldLabel : _('code'),
								name : 'code',
								labelWidth : 130
							},
							{
								xtype : 'numberfield',
								fieldLabel : _('times_to_perform'),
								name : 'times_to_perform',
								width : 250,
								value : 0,
								minValue : 0,
								tooltip : _('greater_than_1_or_just_check_perform_once')

							},
							{

								xtype : 'numberfield',
								fieldLabel : _('age_end'),
								name : 'age_end',
								labelWidth : 75,
								width : 140,
								value : 0,
								minValue : 0

							},
							{
								fieldLabel : _('perform_only_once'),
								xtype : 'checkboxfield',
								labelWidth : 105,
								name : 'only_once'
							}]

						}]
					},
					{
						title : _('active_problems'),
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
								tooltip : _('remove'),
								scope : me,
								handler : me.onRemoveRelation
							}]
						},
						{
							header : _('code'),
							width : 100,
							dataIndex : 'code'
						},
						{
							header : _('description'),
							flex : 1,
							dataIndex : 'code_text'
						}],
						bbar :
						{
							xtype : 'liveicdxsearch',
							margin : 5,
							fieldLabel : _('add_problem'),
							hideLabel : false,
							listeners :
							{
								scope : me,
								select : me.addActiveProblem
							}
						}
					},
					{
						title : _('medications'),
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
								tooltip : _('remove'),
								scope : me,
								handler : me.onRemoveRelation
							}]
						},
						{
							header : _('code'),
							width : 100,
							dataIndex : 'code'
						},
						{
							header : _('description'),
							flex : 1,
							dataIndex : 'code_text'
						}],
						bbar :
						{
							xtype : 'rxnormlivetsearch',
							margin : 5,
							fieldLabel : _('add_problem'),
							hideLabel : false,
							listeners :
							{
								scope : me,
								select : me.addMedications
							}
						}
					},
					{
						title : _('labs'),
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
								tooltip : _('remove'),
								scope : me,
								handler : me.onRemoveRelation
							}]
						},
						{
							header : _('value_name'),
							flex : 1,
							dataIndex : 'value_name'
						},
						{
							header : _('less_than'),
							flex : 1,
							dataIndex : 'less_than',
							editor :
							{
								xtype : 'numberfield'
							}
						},
						{
							header : _('greater_than'),
							flex : 1,
							dataIndex : 'greater_than',
							editor :
							{
								xtype : 'numberfield'
							}
						},
						{
							header : _('equal_to'),
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
							fieldLabel : _('add_labs'),
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
				emptyMsg : _('no_office_notes_to_display'),
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
	},

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
