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

Ext.define('App.view.patient.DoctorsNotes', {
	extend: 'Ext.grid.Panel',
	requires: [
		'App.store.patient.DoctorsNotes',
		'App.ux.grid.RowFormEditing',
		'App.ux.form.fields.MultiText',
		'App.ux.combo.Templates'
	],
	xtype: 'patientdoctorsnotepanel',
	title: _('doctors_notes'),
	itemId: 'DoctorsNotes',
	columnLines: true,
	store: Ext.create('App.store.patient.DoctorsNotes', {
		storeId: 'DoctorsNotesStore',
		groupField: 'order_date',
		remoteFilter: true,
		pageSize: 200,
		sorters: [
			{
				property: 'order_date',
				direction: 'DESC'
			}
		]
	}),
	selModel: Ext.create('Ext.selection.CheckboxModel', {
		showHeaderCheckbox: false
	}),
	features: [
		{
			ftype: 'grouping'
		}
	],
	columns: [
		{
			xtype: 'actioncolumn',
			width: 20,
			items: [
				{
					icon: 'resources/images/icons/cross.png',
					tooltip: _('remove')
				}
			]
		},
		{
			xtype: 'datecolumn',
			text: _('date'),
			dataIndex: 'order_date',
			format: g('date_display_format')
		},
		{
			text: _('type'),
			dataIndex: 'template_id',
			renderer: function(v){
				return App.Current.getController('patient.DoctorsNotes').templatesRenderer(v);
			},
			allowBlank: false
		},
		{
			xtype: 'datecolumn',
			text: _('from'),
			dataIndex: 'from_date',
			format: g('date_display_format')
		},
		{
			xtype: 'datecolumn',
			text: _('to'),
			dataIndex: 'to_date',
			format: g('date_display_format')
		},
		{
			text: _('comments'),
			dataIndex: 'comments',
			flex: 1
		},
		{
			text: _('restrictions'),
			dataIndex: 'string_restrictions',
			flex: 1
		}

	],
	plugins: [
		{
			ptype: 'rowformediting',
			clicksToEdit: 2,
			items: [
				{
					xtype: 'container',
					layout: {
						type: 'hbox'
					},
					items: [
						{
							xtype: 'fieldset',
							layout: 'anchor',
							title: _('general'),
							height: 145,
							width: 300,
							defaults: {
								margin: '0 0 5 0'
							},
							items: [
								{
									xtype: 'datefield',
									fieldLabel: _('order_date'),
									format: g('date_display_format'),
									name: 'order_date'
								},
								{
									xtype: 'documentstemplatescombo',
									fieldLabel: _('document'),
									name: 'template_id'
								},
								{
									xtype: 'datefield',
									fieldLabel: _('from'),
									format: g('date_display_format'),
									name: 'from_date'
								},
								{
									xtype: 'datefield',
									fieldLabel: _('to'),
									format: g('date_display_format'),
									name: 'to_date'
								}
							]
						},
						{
							xtype: 'fieldset',
							layout: 'fit',
							title: _('comments'),
							flex: 1,
							height: 145,
							margin: '0 5',
							items: [
								{
									xtype: 'textareafield',
									anchor: '100%',
									margin: 5,
									name: 'comments'
								}
							]
						},
						{
							xtype: 'fieldset',
							title: _('restrictions'),
							height: 145,
							width: 400,
							autoScroll: true,
							items: [
								{
									xtype: 'multitextfield',
									name: 'restrictions'
								}
							]
						}
					]
				}
			]
		}
	],
	tbar: [
		'->',
		'-',
		{
			text: _('new_order'),
			iconCls: 'icoAdd',
			action: 'encounterRecordAdd',
			itemId: 'newDoctorsNoteBtn'

		},
		'-',
		{
			text: _('print'),
			iconCls: 'icoPrint',
			disabled: true,
			margin: '0 5 0 0',
			itemId: 'printDoctorsNoteBtn'
		}
	]
});
