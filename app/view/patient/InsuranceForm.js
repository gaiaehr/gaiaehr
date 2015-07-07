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

Ext.define('App.view.patient.InsuranceForm', {
	extend: 'Ext.form.Panel',
	requires: [
		'App.ux.combo.Insurances',
		'App.ux.combo.Combo'
	],
	xtype: 'patientinsuranceform',
	border: false,
	bodyBorder: false,
	closable: true,
	fieldDefaults: {
		labelAlign: 'right',
		labelWidth: 140
	},
	items: [
		{
			xtype:'container',
			layout: {
				type: 'hbox'
			},
			items: [
				{
					xtype: 'container',
					items: [
						{
							xtype: 'fieldset',
							title: _('insurance'),
							width: 660,
							margin: 0,
							items: [
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Insurance',
									hideLabel: true,
									enableKeyEvents: true,
									layout: 'hbox',
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'insurancescombo',
											fieldLabel: _('provider'),
											width: 440,
											margin: '0 5 0 0',
											name: 'insurance_id',
											queryMode: 'local',
											editable: false,
											allowBlank: false
										},
										{
											xtype: 'gaiaehr.combo',
											emptyText: _('type'),
											width: 140,
											name: 'insurance_type',
											list: 96,
											queryMode: 'local',
											allowBlank: false,
											editable: false,
											loadStore: true
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Insurance Dates',
									hideLabel: false,
									layout: 'hbox',
									width: 590,
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'datefield',
											emptyText: _('effective_date'),
											width: 140,
											margin: '0 5 0 0',
											name: 'effective_date'
										},
										{
											xtype: 'datefield',
											emptyText: _('expiration_date'),
											width: 140,
											name: 'expiration_date'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Insurance POL/GRP',
									hideLabel: false,
									layout: 'hbox',
									width: 590,
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'textfield',
											emptyText: _('policy_number'),
											width: 140,
											margin: '0 5 0 0',
											name: 'policy_number'
										},
										{
											xtype: 'textfield',
											emptyText: _('group_number'),
											width: 140,
											margin: '0 5 0 0',
											name: 'group_number'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: 'Insurance Covers',
									hideLabel: false,
									layout: 'hbox',
									width: 590,
									margin: '5 0 10 0',
									items: [
										{
											xtype: 'textfield',
											emptyText: _('medical'),
											width: 140,
											margin: '0 5 0 0',
											name: 'cover_medical'
										},
										{
											xtype: 'textfield',
											emptyText: _('dental'),
											width: 140,
											name: 'cover_dental'
										}
									]
								}
							]
						},
						{
							xtype: 'fieldset',
							title: _('subscriber'),
							width: 660,
							style: 'background-color: beige',
							items: [
								{
									xtype: 'gaiaehr.combo',
									fieldLabel: _('relationship'),
									width: 320,
									margin: '0 5 0 0',
									name: 'subscriber_relationship',
									list: 25,
									editable: false,
									allowBlank: false,
									queryMode: 'local',
									itemId: 'PatientInsuranceFormSubscribeRelationshipCmb'
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: _('name'),
									layout: 'hbox',
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'gaiaehr.combo',
											emptyText: _('title'),
											width: 60,
											margin: '0 5 0 0',
											name: 'subscriber_title',
											list: 22,
											queryMode: 'local',
											loadStore: true,
											editable: false
										},
										{
											xtype: 'textfield',
											emptyText: _('first_name'),
											width: 100,
											margin: '0 5 0 0',
											name: 'subscriber_given_name'
										},
										{
											xtype: 'textfield',
											emptyText: _('middle_name'),
											width: 100,
											margin: '0 5 0 0',
											name: 'subscriber_middle_name'
										},
										{
											xtype: 'textfield',
											emptyText: _('last_name'),
											width: 215,
											margin: '0 5 0 0',
											name: 'subscriber_surname'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: _('dob_sex_ss'),
									layout: 'hbox',
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'datefield',
											emptyText: _('dob'),
											width: 165,
											margin: '0 5 0 0',
											name: 'subscriber_dob'
										},
										{
											xtype: 'gaiaehr.combo',
											emptyText: _('sex'),
											width: 100,
											margin: '0 5 0 0',
											name: 'subscriber_sex',
											list: 95,
											queryMode: 'local',
											loadStore: true,
											editable: false
										},
										{
											xtype: 'textfield',
											emptyText: _('ss'),
											width: 215,
											name: 'subscriber_ss'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: _('phone'),
									layout: 'hbox',
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'textfield',
											emptyText: '000-000-0000',
											width: 165,
											margin: '0 5 0 0',
											name: 'subscriber_phone'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: _('address'),
									layout: 'hbox',
									margin: '5 0 0 0',
									items: [
										{
											xtype: 'textfield',
											emptyText: _('street'),
											width: 490,
											name: 'subscriber_street'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									layout: 'hbox',
									margin: '5 0 5 145',
									items: [
										{
											xtype: 'textfield',
											emptyText: _('city'),
											width: 165,
											margin: '0 5 0 0',
											name: 'subscriber_city'
										},
										{
											xtype: 'gaiaehr.combo',
											emptyText: _('state'),
											width: 110,
											margin: '0 5 0 0',
											name: 'subscriber_state',
											list: 20,
											queryMode: 'local',
											loadStore: true,
											editable: false

										},
										{
											xtype: 'gaiaehr.combo',
											emptyText: _('country'),
											width: 110,
											margin: '0 5 0 0',
											name: 'subscriber_country',
											list: 3,
											queryMode: 'local',
											loadStore: true,
											editable: false

										}, {
											xtype: 'textfield',
											emptyText: _('postal_code'),
											width: 90,
											name: 'subscriber_postal_code'
										}
									]
								},
								{
									xtype: 'fieldcontainer',
									fieldLabel: _('employer'),
									layout: 'hbox',
									margin: '5 0 10 0',
									items: [
										{
											xtype: 'textfield',
											width: 490,
											name: 'subscriber_employer'
										}
									]
								}
							]
						}
					]
				},
				{
					xtype: 'container',
					flex: 1,
					layout: {
						type: 'anchor'
					},
					items: [
						{
							xtype:'container',
							anchor: '98%',
							layout: {
								type: 'vbox',
								align: 'right'
							},
							items:[
								{
									xtype: 'panel',
									height: 182,
									width: 255,

									itemId: 'insContainer',
									items: [
										{
											xtype: 'image',
											action: 'insImage',
											width: 253,
											height: 153,
											src: 'data:image/jpeg;base64,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c2VudW0AAAAAUGdQcwAAAABQZ1BDAAAAAExlZnRVbnRGI1JsdAAAAAAAAAAAAAAAAFRvcCBVbnRGI1JsdAAAAAAAAAAAAAAAAFNjbCBVbnRGI1ByY0BZAAAAAAAAAAAAEGNyb3BXaGVuUHJpbnRpbmdib29sAAAAAA5jcm9wUmVjdEJvdHRvbWxvbmcAAAAAAAAADGNyb3BSZWN0TGVmdGxvbmcAAAAAAAAADWNyb3BSZWN0UmlnaHRsb25nAAAAAAAAAAtjcm9wUmVjdFRvcGxvbmcAAAAAADhCSU0D7QAAAAAAEABIAAAAAQABAEgAAAABAAE4QklNBCYAAAAAAA4AAAAAAAAAAAAAP4AAADhCSU0D8gAAAAAACgAA////////AAA4QklNBA0AAAAAAAQAAAB4OEJJTQQZAAAAAAAEAAAAHjhCSU0D8wAAAAAACQAAAAAAAAAAAQA4QklNJxAAAAAAAAoAAQAAAAAAAAABOEJJTQP1AAAAAABIAC9mZgABAGxmZgAGAAAAAAABAC9mZgABAKGZmgAGAAAAAAABADIAAAABAFoAAAAGAAAAAAABADUAAAABAC0AAAAGAAAAAAABOEJJTQP4AAAAAABwAAD/////////////////////////////A+gAAAAA/////////////////////////////wPoAAAAAP////////////////////////////8D6AAAAAD/////////////////////////////A+gAADhCSU0EAAAAAAAAAgAIOEJJTQQCAAAAAAASAAAAAAAAAAAAAAAAAAAAAAAAOEJJTQQwAAAAAAAJAQEBAQEBAQEBADhCSU0ELQAAAAAAAgAAOEJJTQQIAAAAAAAQAAAAAQAAAkAAAAJAAAAAADhCSU0EHgAAAAAABAAAAAA4QklNBBoAAAAAA0cAAAAGAAAAAAAAAAAAAACZAAAA/QAAAAkAaQBuAHMAdQByAGEAbgBjAGUAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAP0AAACZAAAAAAAAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAEAAAAAEAAAAAAABudWxsAAAAAgAAAAZib3VuZHNPYmpjAAAAAQAAAAAAAFJjdDEAAAAEAAAAAFRvcCBsb25nAAAAAAAA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OnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0RXZ0PSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VFdmVudCMiIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxMy0wNi0wNlQyMzo1OTo1NS0wNDowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAxMy0wOS0xNlQxMTowMTozMC0wNDowMCIgeG1wOk1vZGlmeURhdGU9IjIwMTMtMDktMTZUMTE6MDE6MzAtMDQ6MDAiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6Rjg2RjNEOTVEQjFFRTMxMThBNTM5OTE5MTgzMjBCMEUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RkE5ODgwNUMyNUNGRTIxMUEzM0FBQ0U5MEZCMDc0MTUiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpGQTk4ODA1QzI1Q0ZFMjExQTMzQUFDRTkwRkIwNzQxNSIgZGM6Zm9ybWF0PSJpbWFnZS9qcGVnIiBwaG90b3Nob3A6TGVnYWN5SVBUQ0RpZ2VzdD0iMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDEiIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSI+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249ImNyZWF0ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6RkE5ODgwNUMyNUNGRTIxMUEzM0FBQ0U5MEZCMDc0MTUiIHN0RXZ0OndoZW49IjIwMTMtMDYtMDZUMjM6NTk6NTUtMDQ6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDpGQjk4ODA1QzI1Q0ZFMjExQTMzQUFDRTkwRkIwNzQxNSIgc3RFdnQ6d2hlbj0iMjAxMy0wNi0wNlQyMzo1OTo1NS0wNDowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHN0RXZ0OmNoYW5nZWQ9Ii8iLz4gPHJkZjpsaSBzdEV2dDphY3Rpb249InNhdmVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOkY4NkYzRDk1REIxRUUzMTE4QTUzOTkxOTE4MzIwQjBFIiBzdEV2dDp3aGVuPSIyMDEzLTA5LTE2VDExOjAxOjMwLTA0OjAwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ1M2IChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgPD94cGFja2V0IGVuZD0idyI/Pv/iDFhJ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ACITQVEHMmEUcQhCgSORFVKhYhYzCbEkwdFDcvAX4YI0JZJTGGNE8aKyJjUZVDZFZCcKc4OTRnTC0uLyVWV1VjeEhaOzw9Pj8ykalKS0xNTk9JWltcXV5fUoR1dmOHaGlqa2xtbm9md3h5ent8fX5/dIWGh4iJiouMjY6Pg5SVlpeYmZqbnJ2en5KjpKWmp6ipqqusra6vr/2gAMAwEAAhEDEQA/AN/j37r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvdf//Q3+Pfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691//9Hf49+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3X//0t/j37r3XvfuvdEX+d+zMP2Ltn4/bD3A1YmD3d8n+vdv5VsfOlNXLQZPa3YFLUmkqJIaiOGoEch0sUcA/g+/de6Qv/DWXxl/5Xezv/QqxX/2M+/de69/w1l8Zf8Ald7O/wDQqxX/ANjPv3Xuvf8ADWXxl/5Xezv/AEKsV/8AYz7917r3/DWXxl/5Xezv/QqxX/2M+/de6RXZX8tL46bT653/ALqxdX2O2T21srdW4MctVubGTUrV+GwVfkaMVMSbdieWnNRTLrUMpZbgEfX37r3R7fjZ/wBk69Bf+IV6s/8AeGwXv3Xuhq9+690l971W6qHZe767YuNoszvej2vn6rZ2HyUiRY7Lbqp8TVzbexuQlkyGJjjoq7LpDFKzVVMBG5JljHrHuvdV0/6U/wCad/3jZ0r/AOf3D/8A2/ffuvde/wBKf807/vGzpX/z+4f/AO377917r3+lP+ad/wB42dK/+f3D/wD2/ffuvde/0p/zTv8AvGzpX/z+4f8A+377917r3+lP+ad/3jZ0r/5/cP8A/b99+6917/Sn/NO/7xs6V/8AP7h//t++/de69/pT/mnf942dK/8An9w//wBv337r3Xv9Kf8ANO/7xs6V/wDP7h//ALfvv3Xuvf6U/wCad/3jZ0r/AOf3D/8A2/ffuvde/wBKf807/vGzpX/z+4f/AO377917r3+lP+ad/wB42dK/+f3D/wD2/ffuvde/0p/zTv8AvGzpX/z+4f8A+377917r3+lP+ad/3jZ0r/5/cP8A/b99+6917/Sn/NO/7xs6V/8AP7h//t++/de69/pT/mnf942dK/8An9w//wBv337r3Xv9Kf8ANO/7xs6V/wDP7h//ALfvv3Xuvf6U/wCad/3jZ0r/AOf3D/8A2/ffuvde/wBKf807/vGzpX/z+4f/AO377917qz737r3Xvfuvde9+691//9Pf49+691737r3RRPln/wADvit/4t31Z/7z2/ffuvdG79+691737r3Xvfuvde9+690F3eH/ADJXt/8A8Rd2B/7yeW9+690zfGz/ALJ16C/8Qr1Z/wC8NgvfuvdDV7917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuve/de697917r3v3Xuv/1N/j37r3XvfuvdFE+Wf/AAO+K3/i3fVn/vPb99+690bv37r3Xvfuvde9+691737r3QXd4f8AMle3/wDxF3YH/vJ5b37r3TN8bP8AsnXoL/xCvVn/ALw2C9+690NXv3XukvvfPZHa2y937nw+363duX25tfP57F7VxpnGR3NkcRiavIUO38eaWiyVSK3M1NOtNF46aok8kg0xubKfde6rp/2e35Ff96++6v8Aqfvn/wC0r7917r3+z2/Ir/vX33V/1P3z/wDaV9+6917/AGe35Ff96++6v+p++f8A7Svv3Xuvf7Pb8iv+9ffdX/U/fP8A9pX37r3Xv9nt+RX/AHr77q/6n75/+0r7917r3+z2/Ir/AL1991f9T98//aV9+6917/Z7fkV/3r77q/6n75/+0r7917r3+z2/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Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvdf//R3+Pfuvde9+690GHc2+9x9Z9bbk3vtPr7N9p7gwn8H/h+w9utXLmM7/Es9i8RVfZtjcLuKtH8Loq+Ssk0Uc37VO19C3dfde6Ih/s9vyK/71991f8AU/fP/wBpX37r3Xv9nt+RX/evvur/AKn75/8AtK+/de69/s9vyK/71991f9T98/8A2lffuvde/wBnt+RX/evvur/qfvn/AO0r7917r3+z2/Ir/vX33V/1P3z/APaV9+6917/Z7fkV/wB6++6v+p++f/tK+/de69/s9vyK/wC9ffdX/U/fP/2lffuvde/2e35Ff96++6v+p++f/tK+/de69/s9vyK/71991f8AU/fP/wBpX37r3Xv9nt+RX/evvur/AKn75/8AtK+/de69/s9vyK/71991f9T98/8A2lffuvde/wBnt+RX/evvur/qfvn/AO0r7917r3+z2/Ir/vX33V/1P3z/APaV9+6917/Z7fkV/wB6++6v+p++f/tK+/de69/s9vyK/wC9ffdX/U/fP/2lffuvde/2e35Ff96++6v+p++f/tK+/de69/s9vyK/71991f8AU/fP/wBpX37r3Xv9nt+RX/evvur/AKn75/8AtK+/de69/s9vyK/71991f9T98/8A2lffuvde/wBnt+RX/evvur/qfvn/AO0r7917r3+z2/Ir/vX33V/1P3z/APaV9+6917/Z7fkV/wB6++6v+p++f/tK+/de69/s9vyK/wC9ffdX/U/fP/2lffuvde/2e35Ff96++6v+p++f/tK+/de69/s9vyK/71991f8AU/fP/wBpX37r3Xv9nt+RX/evvur/AKn75/8AtK+/de6s+9+691737r3Xvfuvdf/S3+Pfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691//9Pf49+691737r3QYdzds7c6N623J2luyizeR2/tf+D/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9B/7EDtH/wCzb37r3Xv+G7/h3/z6D/2IHaP/ANm3v3Xuvf8ADd/w7/59B/7EDtH/AOzb37r3Xv8Ahu/4d/8APoP/AGIHaP8A9m3v3Xuvf8N3/Dv/AJ9B/wCxA7R/+zb37r3Xv+G7/h3/AM+g/wDYgdo//Zt7917r3/Dd/wAO/wDn0H/sQO0f/s29+6917/hu/wCHf/PoP/Ygdo//AGbe/de69/w3f8O/+fQf+xA7R/8As29+6917/hu/4d/8+g/9iB2j/wDZt7917r3/AA3f8O/+fQf+xA7R/wDs29+6917/AIbv+Hf/AD6D/wBiB2j/APZt7917o6nv3Xuve/de697917r/19/j37r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvdf//Q3+Pfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691//9Hf49+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3X//0t/j37r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvde9+691737r3Xvfuvdf//Z'
										},
										{
											xtype: 'textareafield',
											action: 'insImage',
											name: 'image',
											hidden: true
										}
									],
									bbar: [
										'->',
										'-',
										{
											text: _('upload'),
											action: 'onWebCam'
										},
										'-'
									]
								}
							]
						},
						{
							xtype: 'textareafield',
							labelAlign: 'top',
							fieldLabel: _('notes'),
							margin: '5 0 0 10',
							grow: false,
							anchor: '98%',
							name: 'notes'
						}
					]
				}
			]
		}
	]

});

