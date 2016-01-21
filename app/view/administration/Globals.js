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

Ext.define('App.view.administration.Globals', {
	extend: 'App.ux.RenderPanel',
	id: 'panelGlobals',
	pageTitle: 'GaiaEHR ' + _('global_settings'),
	uses: ['App.ux.form.fields.Checkbox'],
	initComponent: function(){
		var me = this;
		// *************************************************************************************
		// Global Data store
		// *************************************************************************************
		me.store = Ext.create('App.store.administration.Globals',{
			groupField: 'gl_category',
			remoteSort: false,
			autoSync: true,
			pageSize: 500,
			sorters: [
				{
					sorterFn: function(o1, o2){

						var getCat = function(o){
								var name = o.get('gl_category');
								if (name === 'General') {
									return 1;
								} else if (name === 'Locale') {
									return 2;
								} else if (name === 'Clinical') {
									return 3;
								} else if (name === 'Email') {
									return 4;
								} else if (name === 'Audit') {
									return 5;
								} else if (name === 'Fax/Scanner') {
									return 6;
								} else {
									return 7;
								}
							},
							cat1 = getCat(o1),
							cat2 = getCat(o2);

						if (cat1 === cat2) {
							return 0;
						}

						return cat1 < cat2 ? -1 : 1;
					}
				}
			]
		});


		//region Store Region
		me.default_top_pane_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('dashboard'),
					"option_id": "app/dashboard/dashboard.ejs.php"
				},
				{
					"title": _('calendar'),
					"option_id": "app/calendar/calendar.ejs.php"
				},
				{
					"title": _('messages'),
					"option_id": "app/messages/messages.ejs.php"
				}
			]
		});
		me.fullname_store = Ext.create('Ext.data.Store', {
			fields: ['format', 'option_id'],
			data: [
				{
					"format": _('last_first_middle'),
					"option_id": "0"
				},
				{
					"format": _('first_middle_last'),
					"option_id": "1"
				}
			]
		});
		me.main_navigation_menu_left_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('main_navigation_menu_left'),
					"option_id": "west"
				},
				{
					"title": _('main_navigation_menu_right'),
					"option_id": "east"
				}
			]
		});
		me.css_header_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('grey_default'),
					"option_id": "ext-all-gray.css"
				},
				{
					"title": _('blue'),
					"option_id": "ext-all.css"
				},
				{
					"title": _('access'),
					"option_id": "ext-all-access.css"
				}
			]
		});
		me.full_new_patient_form_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('oldstyle_static_form_without_search_or_duplication_check'),
					"option_id": "0"
				},
				{
					"title": _('all_demographics_fields_with_search_and_duplication_check'),
					"option_id": "1"
				},
				{
					"title": _('mandatory_or_specified_fields_only_search_and_dup_check'),
					"option_id": "2"
				},
				{
					"title": _('mandatory_or_specified_fields_only_dup_check_no_search'),
					"option_id": "3"
				}
			]
		});
		me.patient_search_results_style_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('encounter_statistics'),
					"option_id": "0"
				},
				{
					"title": _('mandatory_and_specified_fields'),
					"option_id": "1"
				}
			]
		});
		me.units_of_measurement_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('show_both_us_and_metric_main_unit_is_us'),
					"option_id": "1"
				},
				{
					"title": _('show_both_us_and_metric_main_unit_is_metric'),
					"option_id": "2"
				},
				{
					"title": _('show_us_only'),
					"option_id": "3"
				},
				{
					"title": _('show_metric_only'),
					"option_id": "4"
				}
			]
		});
		me.date_display_format_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('yyyy_mm_dd'),
					"option_id": "Y-m-d"
				},
				{
					"title": _('mm_dd_yyyy'),
					"option_id": "m/d/Y"
				},
				{
					"title": _('dd_mm_yyyy'),
					"option_id": "d/m/Y"
				}
			]
		});
		me.time_display_format_store = Ext.create('Ext.data.Store',	{
				fields: ['title', 'option_id'],
				data: [
					{
						"title": _('24_hr'),
						"option_id": "H:i"
					},
					{
						"title": i18n['12 hr'],
						"option_id": "g:i a"
					}
				]
			});
		me.currency_decimals_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('0'),
					"option_id": "0"
				},
				{
					"title": _('1'),
					"option_id": "1"
				},
				{
					"title": _('2'),
					"option_id": "2"
				}
			]
		});
		me.currency_dec_point_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('comma'),
					"option_id": ","
				},
				{
					"title": _('period'),
					"option_id": "."
				}
			]
		});
		me.currency_thousands_sep_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('comma'),
					"option_id": ","
				},
				{
					"title": _('period'),
					"option_id": "."
				},
				{
					"title": _('space'),
					"option_id": " "
				},
				{
					"title": _('none'),
					"option_id": ""
				}
			]
		});
		me.EMAIL_METHOD_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": "PHPMAIL",
					"option_id": "PHPMAIL"
				},
				{
					"title": "SENDMAIL",
					"option_id": "SENDMAIL"
				},
				{
					"title": "SMTP",
					"option_id": "SMTP"
				}
			]
		});
		me.state_country_data_type_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": _('text_field'),
					"option_id": "2"
				},
				{
					"title": _('single_selection_list'),
					"option_id": "1"
				},
				{
					"title": _('single_selection_list_with_ability_to_add_to_the_list'),
					"option_id": "26"
				}
			]
		});
		me.dummyStore = new Ext.data.ArrayStore({
			fields: ['title', 'option_id'],
			data: [
				[_('option_1'), 'Option 1'],
				[_('option_2'), 'Option 2'],
				[_('option_3'), 'Option 3'],
				[_('option_5'), 'Option 5'],
				[_('option_6'), 'Option 6'],
				[_('option_7'), 'Option 7']
			]
		});
		//region end


		me.grid = Ext.create('Ext.grid.Panel',{
			store: me.store,
			features: [
				{
					ftype:'grouping',
					groupHeaderTpl: _('category') + ': {name}'
				}
			],
			plugins:[
				{
					ptype:'cellediting'
				}
			],
			columns:[
				{
					text:_('title'),
					dataIndex:'gl_name',
					flex:1
				},
				{
					text:_('value'),
					dataIndex:'gl_value',
					flex:1,
					editor:{
						xtype:'textfield'
					}
				},
				{
					text:_('category'),
					dataIndex:'gl_category'
				}
			]
		});

		me.pageBody = [ me.grid ];

		me.callParent(arguments);
	},

	/**
	 *
	 * @param form
	 * @param store
	 */
	onGloblasSave: function(form, store){
		var record = form.getRecord(), values = form.getValues();
		Globals.updateGlobals(values, function(){
			store.load();
		});

		this.msg(_('new_global_configuration_saved'), _('refresh_the_application'));
	},
	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback){
		this.store.load();
		callback(true);
	}
});

