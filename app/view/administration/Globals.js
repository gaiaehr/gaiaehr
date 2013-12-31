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
	pageTitle: 'GaiaEHR ' + i18n('global_settings'),
	uses: ['App.ux.form.fields.Checkbox'],
	initComponent: function(){
		var me = this;
		// *************************************************************************************
		// Global Data store
		// *************************************************************************************
		me.store = Ext.create('App.store.administration.Globals',{
			groupField: 'gl_category',
			remoteSort: false,
			sorters: [
				{
					sorterFn: function(o1, o2){

						var getCat = function(o){
								var name = o.get('gl_category');

								say(name);

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
					"title": i18n('dashboard'),
					"option_id": "app/dashboard/dashboard.ejs.php"
				},
				{
					"title": i18n('calendar'),
					"option_id": "app/calendar/calendar.ejs.php"
				},
				{
					"title": i18n('messages'),
					"option_id": "app/messages/messages.ejs.php"
				}
			]
		});
		me.fullname_store = Ext.create('Ext.data.Store', {
			fields: ['format', 'option_id'],
			data: [
				{
					"format": i18n('last_first_middle'),
					"option_id": "0"
				},
				{
					"format": i18n('first_middle_last'),
					"option_id": "1"
				}
			]
		});
		me.main_navigation_menu_left_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('main_navigation_menu_left'),
					"option_id": "west"
				},
				{
					"title": i18n('main_navigation_menu_right'),
					"option_id": "east"
				}
			]
		});
		me.css_header_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('grey_default'),
					"option_id": "ext-all-gray.css"
				},
				{
					"title": i18n('blue'),
					"option_id": "ext-all.css"
				},
				{
					"title": i18n('access'),
					"option_id": "ext-all-access.css"
				}
			]
		});
		me.full_new_patient_form_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('oldstyle_static_form_without_search_or_duplication_check'),
					"option_id": "0"
				},
				{
					"title": i18n('all_demographics_fields_with_search_and_duplication_check'),
					"option_id": "1"
				},
				{
					"title": i18n('mandatory_or_specified_fields_only_search_and_dup_check'),
					"option_id": "2"
				},
				{
					"title": i18n('mandatory_or_specified_fields_only_dup_check_no_search'),
					"option_id": "3"
				}
			]
		});
		me.patient_search_results_style_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('encounter_statistics'),
					"option_id": "0"
				},
				{
					"title": i18n('mandatory_and_specified_fields'),
					"option_id": "1"
				}
			]
		});
		me.units_of_measurement_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('show_both_us_and_metric_main_unit_is_us'),
					"option_id": "1"
				},
				{
					"title": i18n('show_both_us_and_metric_main_unit_is_metric'),
					"option_id": "2"
				},
				{
					"title": i18n('show_us_only'),
					"option_id": "3"
				},
				{
					"title": i18n('show_metric_only'),
					"option_id": "4"
				}
			]
		});
		me.date_display_format_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('yyyy_mm_dd'),
					"option_id": "Y-m-d"
				},
				{
					"title": i18n('mm_dd_yyyy'),
					"option_id": "m/d/Y"
				},
				{
					"title": i18n('dd_mm_yyyy'),
					"option_id": "d/m/Y"
				}
			]
		});
		me.time_display_format_store = Ext.create('Ext.data.Store',	{
				fields: ['title', 'option_id'],
				data: [
					{
						"title": i18n('24_hr'),
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
					"title": i18n('0'),
					"option_id": "0"
				},
				{
					"title": i18n('1'),
					"option_id": "1"
				},
				{
					"title": i18n('2'),
					"option_id": "2"
				}
			]
		});
		me.currency_dec_point_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('comma'),
					"option_id": ","
				},
				{
					"title": i18n('period'),
					"option_id": "."
				}
			]
		});
		me.currency_thousands_sep_store = Ext.create('Ext.data.Store', {
			fields: ['title', 'option_id'],
			data: [
				{
					"title": i18n('comma'),
					"option_id": ","
				},
				{
					"title": i18n('period'),
					"option_id": "."
				},
				{
					"title": i18n('space'),
					"option_id": " "
				},
				{
					"title": i18n('none'),
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
					"title": i18n('text_field'),
					"option_id": "2"
				},
				{
					"title": i18n('single_selection_list'),
					"option_id": "1"
				},
				{
					"title": i18n('single_selection_list_with_ability_to_add_to_the_list'),
					"option_id": "26"
				}
			]
		});
		me.dummyStore = new Ext.data.ArrayStore({
			fields: ['title', 'option_id'],
			data: [
				[i18n('option_1'), 'Option 1'],
				[i18n('option_2'), 'Option 2'],
				[i18n('option_3'), 'Option 3'],
				[i18n('option_5'), 'Option 5'],
				[i18n('option_6'), 'Option 6'],
				[i18n('option_7'), 'Option 7']
			]
		});
		//region end


		me.grid = Ext.create('Ext.grid.Panel',{
			store: me.store,
			features: [
				{
					ftype:'grouping',
					groupHeaderTpl: i18n('category') + ': {name}'
				}
			],
			columns:[
				{
					text:i18n('title'),
					dataIndex:'gl_name',
					flex:1
				},
				{
					text:i18n('value'),
					dataIndex:'gl_value',
					flex:1
				},
				{
					text:i18n('category'),
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

		this.msg(i18n('new_global_configuration_saved'), i18n('refresh_the_application'));
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

