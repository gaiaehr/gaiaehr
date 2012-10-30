/**
 * visits.ejs.php
 * Visits List
 * v0.0.1
 *
 * Author: Ernesto J. Rodriguez
 * Modified:
 *
 * GaiaEHR (Electronic Health Records) 2011
 * @namespace Encounter.getEncounters
 */
Ext.define('App.view.patient.Visits', {
	extend   : 'App.ux.RenderPanel',
	id       : 'panelVisits',
	pageTitle: i18n('visits_history'),
	uses     : [
		'App.ux.GridPanel',
		'Ext.ux.PreviewPlugin'
	],

	initComponent: function() {
		var me = this;

		me.store = Ext.create('App.store.patient.Encounters');

		function open(val) {
			if(val !== null) {
				return '<img src="resources/images/icons/yes.gif" />';
			} else {
				return '<img src="resources/images/icons/no.gif" />';
			}
		}

		//******************************************************************
		// Visit History Grid
		//******************************************************************
		me.historyGrid = Ext.create('Ext.grid.Panel', {
			title     : i18n('encounter_history'),
			store     : me.store,
			columns   : [
				{ header: 'eid', sortable: false, dataIndex: 'eid', hidden: true},
				{ width: 150, header: i18n('date'), sortable: true, dataIndex: 'service_date', renderer: Ext.util.Format.dateRenderer('Y-m-d H:i:s') },
				{ flex: 1, header: i18n('reason'), sortable: true, dataIndex: 'brief_description' },
				{ width: 180, header: i18n('provider'), sortable: false, dataIndex: 'provider' },
				{ width: 120, header: i18n('facility'), sortable: false, dataIndex: 'facility' },
				{ width: 120, header: i18n('billing_facility'), sortable: true, dataIndex: 'billing_facility' },
				{ width: 45, header: i18n('close') + '?', sortable: true, dataIndex: 'close_date', renderer: me.openBool }
			],
			viewConfig: {
				itemId   : 'view',
				plugins  : [
					{
						pluginId       : 'preview',
						ptype          : 'preview',
						bodyField      : 'brief_description',
						previewExpanded: false
					}
				],
				listeners: {
					scope       : me,
					itemclick   : me.gridItemClick,
					itemdblclick: me.gridItemDblClick
				}
			},
			tbar      : Ext.create('Ext.PagingToolbar', {
				store      : me.store,
				displayInfo: true,
				emptyMsg   : 'No Encounters Found',
				plugins    : Ext.create('Ext.ux.SlidingPager', {}),
				items      : [
					{
						iconCls      : '',
						text         : i18n('show_details'),
						enableToggle : true,
						scope        : me,
						toggleHandler: me.onDetailToggle
					},
					'-',
					{
						text   : i18n('new_encounter'),
						scope  : me,
						handler: me.createNewEncounter
					}
				]
			})
		});
		me.pageBody = [me.historyGrid];

		me.callParent(arguments);
	},

	openBool: function(val) {
		if(val !== null) {
			return '<img src="resources/images/icons/yes.gif" />';
		} else {
			return '<img src="resources/images/icons/no.gif" />';
		}
	},

	onDetailToggle: function(btn, pressed) {
		this.historyGrid.getComponent('view').getPlugin('preview').toggleExpanded(pressed);
	},

	gridItemClick: function(view) {
		view.getPlugin('preview').toggleRowExpanded();
	},

	gridItemDblClick: function(view, record) {
		app.openEncounter(record.data.eid);
	},

	createNewEncounter: function() {
		app.createNewEncounter();
	},

	/**
	 * This function is called from Viewport.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		if(this.checkIfCurrPatient()) {

			var patient = this.getCurrPatient();
			this.updateTitle(patient.name + ' (' + i18n('encounters') + ')');
			this.store.load();
			callback(true);
		} else {
			callback(false);
			this.currPatientError();
		}
	}
});
