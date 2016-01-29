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

Ext.define('App.view.search.PatientSearch',
{
	extend : 'App.ux.RenderPanel',
	id : 'panelPatientSearch',
	pageTitle : _('advance_patient_search'),
	pageLayout : 'border',
	uses : ['Ext.grid.Panel'],
	initComponent : function()
	{
		var me = this;

		me.form = Ext.create('Ext.form.FormPanel',
		{
			region : 'north',
			height : 200,
			bodyPadding : 10,
			margin : '0 0 3 0',
			buttonAlign : 'left',
			items : [
			{
				xtype : 'fieldcontainer',
				fieldLabel : _('name'),
				layout : 'hbox',
				defaults :
				{
					margin : '0 5 0 0'
				},
				items : [
				{
					xtype : 'textfield',
					emptyText : _('first_name'),
					name : 'fname'
				},
				{
					xtype : 'textfield',
					emptyText : _('middle_name'),
					name : 'mname'
				},
				{
					xtype : 'textfield',
					emptyText : _('last_name'),
					name : 'lname'
				}]
			}],

			buttons : [
			{
				text : _('search'),
				iconCls : 'save',
				handler : function()
				{
					//TODO: Finish me.
				}
			}, '-',
			{
				text : _('reset'),
				iconCls : 'save',
				tooltip : _('hide_selected_office_note'),
				handler : function()
				{
					//TODO: Finish me.
				}
			}]
		});
		
		me.grid = Ext.create('Ext.grid.Panel',
		{
			region : 'center',
			//store    : me.store,
			columns : [
			{
				header : 'id',
				sortable : false,
				dataIndex : 'id',
				hidden : true
			},
			{
				width : 150,
				header : _('date'),
				sortable : true,
				dataIndex : 'date',
				renderer : Ext.util.Format.dateRenderer('Y-m-d H:i:s')
			},
			{
				width : 150,
				header : _('user'),
				sortable : true,
				dataIndex : 'user'
			},
			{
				flex : 1,
				header : _('note'),
				sortable : true,
				dataIndex : 'body'
			}],
			tbar : Ext.create('Ext.PagingToolbar',
			{
				store : me.store,
				displayInfo : true,
				emptyMsg : _('no_office_notes_to_display'),
				plugins : Ext.create('Ext.ux.SlidingPager',
				{
				})
			})
		});
		// END GRID
		me.pageBody = [me.form, me.grid];
		me.callParent(arguments);
	}, // end of initComponent
	
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive : function(callback)
	{
		callback(true);
	}
});
//ens oNotesPage class