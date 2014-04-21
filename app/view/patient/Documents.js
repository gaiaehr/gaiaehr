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
	title: i18n('documents'),
	layout: 'border',
	items: [
		{
			xtype: 'grid',
			region: 'west',
			split: true,
			flex: 1,
			columnLines: true,
			features: [
				{
					ftype: 'grouping',
					groupHeaderTpl: Ext.create('Ext.XTemplate',
						'Group: {name:this.getGroupName}',
						{
							getGroupName: function(name){
								return app.getController('patient.Documents').getGroupName(name);
							}
						}
					)
				}
			],
			itemId: 'patientDocumentGrid',
			minWidth: 450,
			store: Ext.create('App.store.patient.PatientDocuments', {
				autoLoad: false,
				remoteFilter: true,
				remoteSort: false,
				autoSync: false,
				pageSize: 200
			}),
			columns: [
				{
					xtype: 'actioncolumn',
					width: 30,
					items: [
						{
							icon: 'resources/images/icons/icoLessImportant.png',
							tooltip: i18n('validate_file_integrity_hash'),
							handler: function(grid, rowIndex){
								App.Current.getController('patient.Documents').onDocumentHashCheckBtnClick(grid, rowIndex)
							},
							getClass: function(){
								return 'x-grid-icon-padding';
							}
						}
					]
				},
				{
					header: i18n('type'),
					dataIndex: 'docType'
				},
				{
					xtype: 'datecolumn',
					header: i18n('date'),
					dataIndex: 'date',
					format: 'Y-m-d'

				},
				{
					header: i18n('title'),
					dataIndex: 'title',
					flex: 1,
					editor: {
						xtype: 'textfield',
						action: 'title'
					}
				},
				{
					header: i18n('encrypted'),
					dataIndex: 'encrypted',
					width: 70,
					renderer: this.boolRenderer
				}
			],
			plugins: Ext.create('Ext.grid.plugin.RowEditing', {
				autoCancel: true,
				errorSummary: false,
				clicksToEdit: 2

			}),
			tbar: [
					i18n('group_by') + ':',
				{
					xtype: 'button',
					text: i18n('date'),
					enableToggle: true,
					action: 'groupDate',
					toggleGroup: 'documentgridgroup'
				},
				{
					xtype: 'button',
					text: i18n('type'),
					enableToggle: true,
					action: 'docType',
					toggleGroup: 'documentgridgroup'
				},
				'->',
				'-',
				{
					text: i18n('upload_document'),
					itemId: 'documentUploadBtn'
				}
			]
		},
		{
			xtype: 'panel',
			region: 'center',
			flex: 2,
			layout: 'fit',
			frame: true,
			itemId: 'patientDocumentViewerPanel',
			style: 'background-color:white',
			items: [
				{
					xtype: 'miframe',
					style: 'background-color:white',
					autoMask: false,
					itemId: 'patientDocumentViewerFrame'
				}
			]
		}
	]

});