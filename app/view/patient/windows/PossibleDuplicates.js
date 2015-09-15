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

Ext.define('App.view.patient.windows.PossibleDuplicates', {
	extend: 'App.ux.window.Window',
	title: _('possible_duplicates'),
	itemId: 'PossiblePatientDuplicatesWindow',
	closeAction: 'hide',
	bodyStyle: 'background-color:#fff',
	modal: true,
	closable: false,
	requires: [
		'Ext.toolbar.Paging',
		'Ext.ux.SlidingPager'
	],
	initComponent: function(){
		var me = this;

		me.items = [
			{
				xtype: 'grid',
				store: me.store = Ext.create('App.store.patient.PatientPossibleDuplicates'),
				width: 700,
				maxHeight: 700,
				frame: true,
				margin: 5,
				hideHeaders: true,
				columns: [
					{
						dataIndex: 'image',
						width: 65,
						renderer: function(v){
							var src =  v != '' ? v : app.patientImage;
							return '<img src="' + src + '" class="icon32Round" />';
						}
					},
					{
						dataIndex: 'fullname',
						flex: 1,
						renderer: function(v, meta, record){
							var phone = record.data.home_phone !== '' ? record.data.home_phone : '000-000-0000',
								driver_lic = record.data.drivers_license !== '' ? record.data.drivers_license : '0000000000';

							return '<table cellpadding="1" cellspacing="0" border="0" width="100%" style="font-size: 12px;">' +
								'<tbody>' +

								'<tr>' +
								'<td width="20%"><b>' + _('record_number') + ':</b></td>' +
								'<td>' + record.data.record_number +'</td>' +
								'</tr>' +

								'<tr>' +
								'<td><b>' + _('patient') + ':</b></td>' +
								'<td>' + record.data.name + ' (' + record.data.sex + ') ' + record.data.DOBFormatted + '</td>' +
								'</tr>' +

								'</tr>' +
								'<tr>' +
								'<td><b>' + _('address') + ':</b></td>' +
								'<td>' + record.data.fulladdress + '</td>' +
								'</tr>' +

								'<tr>' +
								'<td><b>' + _('home_phone') + ':</b></td>' +
								'<td>' + phone + '</td>' +
								'</tr>' +

								'<tr>' +
								'<td><b>' + _('driver_lic') + ':</b></td>' +
								'<td>' + driver_lic + '</td>' +
								'</tr>' +

								'<tr>' +
								'<td><b>' + _('employer_name') + ':</b></td>' +
								'<td>' + record.data.employer_name + '</td>' +
								'</tr>' +

								'<tr>' +
								'<td><b>' + _('social_security') + ':</b></td>' +
								'<td>' + record.data.SS +'</td>' +
								'</tr>' +
								'</tbody>' +
								'</table>';

						}
					}
				],
				bbar: {
					xtype: 'pagingtoolbar',
					pageSize: 10,
					store: me.store,
					displayInfo: true,
					plugins: Ext.create('Ext.ux.SlidingPager')
				}
			}
		];

		me.buttons = [
			{
				text: _('cancel'),
				itemId: 'PossiblePatientDuplicatesCancelBtn',
				handler: function(btn){
					btn.up('window').close();
				}
			},
			'-',
			{
				text: _('continue'),
				itemId: 'PossiblePatientDuplicatesContinueBtn'
			}
		];

		me.callParent();
	}
});