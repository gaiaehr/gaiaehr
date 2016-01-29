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

Ext.define('App.view.miscellaneous.AmendmentDetails', {
	extend: 'Ext.window.Window',
	requires: [
		'App.ux.LiveUserSearch'
	],
	itemId: 'AmendmentDetailsWindow',
	title: _('amendment'),
	width: 800,
	closeAction: 'hide',
	items: [
		{
			xtype:'form',
			itemId: 'AmendmentDetailsForm',
			bodyPadding: 5,
			border: false,
			bodyBorder: false,
			items:[
				{
					xtype:'grid',
					title: _('amendment_data'),
					itemId: 'AmendmentDetailsDataGrid',
					margin: '0 0 5 0',
					frame: true,
					height: 150,
					store: Ext.create('Ext.data.Store',{
						proxy: {
							type: 'ajax',
							reader: {
								type: 'array'
							}
						},
						fields: [
							{
								name: 'field_name',
								type:' string'
							},
							{
								name: 'field_label',
								type:' string'
							},
							{
								name: 'old_value',
								type:' string'
							},
							{
								name: 'new_value',
								type:' string'
							},
							{
								name: 'data_key',
								type:' string'
							},
							{
								name: 'approved',
								type:' bool'
							}
						]
					}),
					columns:[
						{
							width: 200,
							dataIndex:'field_label',
							renderer: function(v, meta, record){
								return v + ' (' + record.data.data_key + ')'
							}
						},
						{
							text: _('new_value'),
							dataIndex:'new_value',
							flex: 1,
							tdCls: 'lightGreenBg'
						},
						{
							text: _('old_value'),
							dataIndex:'old_value',
							flex: 1,
							tdCls: 'lightRedBg'
						}
					]
				},
				{
					xtype:'textareafield',
					fieldLabel: _('message'),
					name: 'amendment_message',
					labelAlign: 'top',
					anchor:'100%',
					readOnly: true
				},
				{
					xtype:'textfield',
					fieldLabel: _('response_message'),
					labelAlign: 'top',
					itemId: 'AmendmentDetailsResponseMessageField',
					name: 'response_message',
					anchor:'100%'
				}
			]
		}

	],
	buttons:[
		{
			xtype: 'userlivetsearch',
			width: 300,
			acl: 'amendments_access&amendments_response',
			itemId: 'AmendmentDetailsUserLiveSearch',
			allowBlank: false
		},
		{
			text: _('assign'),
			itemId: 'AmendmentDetailsAssignBtn'
		},
		{
			xtype: 'tbfill'
		},
		{
			xtype: 'tbtext',
			text: '-',
			itemId: 'AmendmentDetailsResponseText'
		},
		{
			text: _('deny'),
			itemId: 'AmendmentDetailsDenyBtn',
			icon: 'resources/images/icons/no.gif'
		},
		{
			text: _('approve'),
			itemId: 'AmendmentDetailsApproveBtn',
			icon: 'resources/images/icons/yes.gif'
		}
	]
});
