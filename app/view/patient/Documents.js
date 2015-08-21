/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

Ext.define('App.view.patient.Documents', {
	extend: 'Ext.panel.Panel',
	requires: [
		'App.ux.combo.Templates',
		'Ext.grid.plugin.RowEditing',
		'App.store.patient.PatientDocuments',
		'App.ux.ManagedIframe',
		'Ext.grid.feature.Grouping',
		'Ext.form.ComboBox'
	],
	xtype: 'patientdocumentspanel',
	title: _('documents'),
	layout: 'border',
	initComponent: function(){
		var me = this,
			store = Ext.create('App.store.patient.PatientDocuments', {
				autoLoad: false,
				remoteFilter: true,
				remoteSort: false,
				autoSync: false,
				pageSize: 200,
				groupField: 'groupDate'
			}),
			docCtrl = App.app.getController('patient.Documents');

		me.items = [
			{
				xtype: 'grid',
				region: 'west',
				split: true,
				flex: 1,
				columnLines: true,
				selType: 'checkboxmodel',
				features: [
					{
						ftype: 'grouping',
						groupHeaderTpl: Ext.create('Ext.XTemplate',
							'Group: {name:this.getGroupName}',
							{
								getGroupName: function(name){
									return docCtrl.getGroupName(name);
								}
							}
						)
					}
				],
				itemId: 'patientDocumentGrid',
				store: store,
				columns: [
					{
						xtype: 'actioncolumn',
						width: 23,
						icon: 'resources/images/icons/icoLessImportant.png',
						tooltip: _('validate_file_integrity_hash'),
						handler: function(grid, rowIndex){
							docCtrl.onDocumentHashCheckBtnClick(grid, rowIndex);
						},
						getClass: function(){
							return 'x-grid-icon-padding';
						}
					},
					{
						xtype: 'actioncolumn',
						width: 23,
						icon: 'resources/images/icons/delete.png',
						tooltip: _('delete'),
						hidden: !eval(a('delete_patient_documents')),
						handler: function(grid, rowIndex, colIndex, item, e, recprd){


							alert('hello');

						},
						getClass: function(){
							return 'x-grid-icon-padding';
						}
					},
					{
						header: _('type'),
						dataIndex: 'docType'
					},
					{
						xtype: 'datecolumn',
						header: _('date'),
						dataIndex: 'date',
						format: 'Y-m-d'

					},
					{
						header: _('title'),
						dataIndex: 'title',
						flex: 1,
						editor: {
							xtype: 'textfield',
							action: 'title'
						}
					},
					{
						header: _('encrypted'),
						dataIndex: 'encrypted',
						width: 70,
						renderer: function(v){
							return app.boolRenderer(v);
						}
					}
				],
				plugins: Ext.create('Ext.grid.plugin.RowEditing', {
					autoCancel: true,
					errorSummary: false,
					clicksToEdit: 2

				}),
				tbar: [
					_('group_by') + ':',
					{
						xtype: 'button',
						text: _('date'),
						enableToggle: true,
						action: 'groupDate',
						pressed: true,
						toggleGroup: 'documentgridgroup'
					},
					{
						xtype: 'button',
						text: _('type'),
						enableToggle: true,
						action: 'docType',
						toggleGroup: 'documentgridgroup'
					},
					'->',
					'-',
					{
						text: _('add_document'),
						itemId: 'documentUploadBtn'
					}
				],
				bbar: Ext.create('Ext.PagingToolbar', {
					pageSize: 10,
					store: store,
					displayInfo: true,
					plugins: Ext.create('Ext.ux.SlidingPager', {})
				})
			},
			{
				xtype: 'panel',
				region: 'center',
				flex: 2,
				layout: 'fit',
				frame: true,
				itemId: 'patientDocumentViewerPanel',
				style: 'background-color:#e7e7e7',
				items: [
					{
						xtype: 'miframe',
						style: 'background-color:#e7e7e7',
						autoMask: false,
						itemId: 'patientDocumentViewerFrame'
					}
				]
			}
		];

		me.callParent(arguments);
	}
});