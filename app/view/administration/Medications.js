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

Ext.define('App.view.administration.Medications',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelMedications',
	pageTitle : _('medications'),

	initComponent : function()
	{
		var me = this;
		me.query = '';

		me.storeMedications = Ext.create('App.store.administration.Medications');

		me.medicationsGrid = Ext.create('Ext.grid.Panel',
		{
			region : 'center',
			store : me.storeMedications,
			columns : [
			{
				width : 70,
				header : _('number'),
				dataIndex : 'PRODUCTNDC',
				sortable : true
			},
			{
				width : 80,
				header : _('name'),
				dataIndex : 'PROPRIETARYNAME',
				sortable : true
			},
			{
				width : 200,
				header : _('active_component'),
				dataIndex : 'NONPROPRIETARYNAME',
				sortable : true
			},
			{
				width : 175,
				header : _('dosage'),
				dataIndex : 'DOSAGEFORMNAME',
				sortable : true
			},
			{
				width : 45,
				header : _('number'),
				dataIndex : 'ACTIVE_NUMERATOR_STRENGTH',
				sortable : true
			},
			{
				flex : 1,
				header : _('unit'),
				dataIndex : 'ACTIVE_INGRED_UNIT',
				sortable : true
			}],
			plugins : Ext.create('App.ux.grid.RowFormEditing',
			{
				autoCancel : false,
				errorSummary : false,
				clicksToEdit : 1,
				enableRemove : true,
				items : [
				{

					title : 'general',
					xtype : 'container',
					padding : 10,
					layout : 'vbox',
					items : [
					{
						/**
						 * Line one
						 */
						xtype : 'fieldcontainer',
						layout : 'hbox',
						defaults :
						{
							margin : '0 10 5 0'
						},
						items : [
						{
							xtype : 'textfield',
							fieldLabel : _('name'),
							width : 150,
							labelWidth : 50,
							name : 'PROPRIETARYNAME'

						},
						{
							xtype : 'textfield',
							fieldLabel : _('active_component'),
							width : 350,
							labelWidth : 125,
							name : 'NONPROPRIETARYNAME'

						},

						{
							xtype : 'textfield',
							fieldLabel : _('dosage'),
							width : 200,
							labelWidth : 50,
							name : 'DOSAGEFORMNAME'

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
							margin : '0 10 5 0'
						},
						items : [
						{
							xtype : 'textfield',
							fieldLabel : _('code'),
							labelWidth : 50,
							width : 150,
							name : 'PRODUCTNDC'

						},
						{
							xtype : 'textfield',
							fieldLabel : _('dosis'),
							margin : '0 0 5 0',
							value : 0,
							minValue : 0,
							width : 275,
							labelWidth : 125,
							name : 'ACTIVE_NUMERATOR_STRENGTH'

						},
						{
							xtype : 'textfield',
							name : 'ACTIVE_INGRED_UNIT',
							width : 75

						}]

					}]

				}]

			}),
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.storeMedications,
				displayInfo : true,
				emptyMsg : _('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager'),
				items : ['-',
                    {
                        text : 'Add New',
                        scope : me,
                        handler : me.onAddMedication
                    },
                    '-',
                    {
                        xtype : 'textfield',
                        emptyText : _('search'),
                        enableKeyEvents : true,
                        itemId : 'query',
                        listeners :
                        {
                            scope : me,
                            keyup : me.onSearchMedications,
                            buffer : 500
                        }
                    },
                    '-',
                    {
                        text : _('reset'),
                        scope : me,
                        handler : me.onFieldReset
                    },
                    {
                        text: 'Print',
                        iconCls: 'icon-print',
                        handler : function(){
                            App.ux.grid.Printer.printAutomatically = false;
                            App.ux.grid.Printer.print(this.up('grid'));
                        }
                    }
                ]
			})

		});
		me.pageBody = [me.medicationsGrid];
		me.callParent(arguments);
	}, // end of initComponent

	onFieldReset : function()
	{

	},

	onAddMedication : function()
	{
		this.medicationsGrid.editingPlugin.cancelEdit();

		this.storeMedications.insert(0,
		{
		});
		this.medicationsGrid.editingPlugin.startEdit(0, 0);

	},

	onSearchMedications : function(field)
	{
		var me = this, store = me.storeMedications;

		me.query = field.getValue();

		store.proxy.extraParams =
		{
			query : me.query
		};
		store.load();
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function()
	{
		this.medicationsGrid.down('toolbar').getComponent('query').reset();
		this.storeMedications.proxy.extraParams =
		{
		};
		this.storeMedications.load();

	}
});
//ens servicesPage class