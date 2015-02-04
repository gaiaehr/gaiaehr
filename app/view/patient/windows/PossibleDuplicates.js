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
							var phone = record.data.home_phone != '' ? record.data.home_phone : '000-000-0000',
								driver_liv = record.data.drivers_license != '' ? record.data.drivers_license : '0000000000';

							var str = '<p style="margin: 5px"><b>' + _('name') + ':</b> ' + record.data.fname + ' ' + record.data.mname + ' ' + record.data.lname + '</p>';
							str += '<p style="margin: 5px"><b>' + _('address') + ':</b> ' + record.data.address + ' ' + record.data.address_cont + ' ' + record.data.city + ' ' + record.data.state + ' ' + record.data.zipcode + '</p>';
							str += '<p style="margin: 5px"><b>' + _('home_phone') + ':</b> ' + phone + ' <b>' + _('driver_lic') + ':</b> ' + driver_liv + ' <b>' + _('employer_name') + ':</b> ' + record.data.employer_name + '</p>';
							str += '<p style="margin: 5px"><b>' + _('social_security') + ':</b> ' + record.data.SS + '</p>';
							return '<div style="font-size: 12px;">' + str + '</div>';
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
				text: _('continue'),
				itemId: 'PossiblePatientDuplicatesContinueBtn'
			}
		];

		me.callParent();
	}
});