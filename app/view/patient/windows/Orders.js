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

Ext.define('App.view.patient.windows.Orders', {
	extend: 'App.ux.window.Window',
	requires:[
		'App.view.patient.LabOrders',
		'App.view.patient.RadOrders',
		'App.view.patient.RxOrders'
	],
	title: i18n('order_window'),
	closeAction: 'hide',
	height: 700,
	width: 1300,
	layout: 'fit',
	bodyStyle: 'background-color:#fff',
	modal: true,

	pid: null,
	eid: null,
	buttons: [
		{
			text: i18n('close'),
			handler: function(btn){
				btn.up('window').close();
			}
		}
	],
	initComponent: function(){
		var me = this;

		me.items = [
			me.tabPanel = Ext.create('Ext.tab.Panel', {
				margin: 5,
				plain: true,
				items: [
					/**
					 * LAB ORDERS PANEL
					 */
					{
						xtype: 'patientlaborderspanel',
					},
					/**
					 * X-RAY PANEL
					 */
					{
						xtype: 'patientradorderspanel',
					},
					/**
					 * PRESCRIPTION PANEL
					 */
					{
						xtype:'patientrxorderspanel'
					},
					/**
					 * DOCTORS NOTE
					 */
					{
						title: i18n('new_doctors_note'),
						layout: {
							type: 'vbox',
							align: 'stretch'
						},
						items: [
							me.doctorsNoteTplCombo = Ext.widget('documentstemplatescombo', {
								fieldLabel: i18n('template'),
								action: 'template',
								labelWidth: 75,
								margin: '5 5 0 5',
								enableKeyEvents: true,
								listeners: {
									scope: me,
									select: me.onTemplateTypeSelect
								}
							}),
							me.doctorsNoteBody = Ext.widget('htmleditor', {
								name: 'body',
								action: 'body',
								itemId: 'body',
								enableFontSize: false,
								flex: 1,
								margin: '5 5 8 5'
							})
						],
						bbar: [
							'->', {
								text: i18n('create_doctors_notes'),
								scope: me,
								itemId: 'encounterRecordAdd',
								handler: me.onCreateDoctorsNote
							}
						]
					}
				]

			})
		];

		me.buttons = [
			{
				text: i18n('close'),
				scope: me,
				handler: function(){
					me.close();
				}
			}
		];
		/**
		 * windows listeners
		 * @type {{scope: *, show: Function, hide: Function}}
		 */
		me.listeners = {
			scope: me,
			show: me.onWinShow,
			hide: me.onWinHide
		};
		me.callParent(arguments);
	},

	/**
	 * OK!
	 * @param action
	 */
	cardSwitch: function(action){
		var layout = this.tabPanel.getLayout();
		if(action == 'lab'){
			layout.setActiveItem(0);
		}else if(action == 'xRay'){
			layout.setActiveItem(1);
		}else if(action == 'prescription'){
			layout.setActiveItem(2);
		}else if(action == 'notes'){
			layout.setActiveItem(3);
		}
	},



	/**
	 * OK!
	 * This will set the htmleditor value
	 * @param combo
	 * @param record
	 */
	onTemplateTypeSelect: function(combo, record){
		combo.up('panel').getComponent('body').setValue(record[0].data.body);
	},

	/**
	 * OK!
	 * On doctors note create
	 */
	onCreateDoctorsNote: function(){
		var me = this,
			params = {
				pid: eval(me.pid),
				eid: eval(me.eid),
				templateId: eval(me.doctorsNoteTplCombo.getValue()),
				docType: 'DoctorsNotes',
				body: me.doctorsNoteBody.getValue()
			};

		DocumentHandler.createDocument(params, function(provider, response){
			app.msg('Sweet!', 'Document Created');
			say(response.result);
			this.close();
		});
	},


	/**
	 *
	 * @param sm
	 * @param selected
	 */
	onSelectionChange: function(sm, selected){
		var grid = sm.views[0].panel;
		this[grid.action + 'PrintBtn'].setDisabled(selected.length == 0);

		if(grid.action == 'rx'){
			this.cloneRxBtn.setDisabled(selected.length == 0);
			//this.eRxBtn.setDisabled(selected.length == 0);
		}
	},


	/**
	 * OK!
	 * On window shows
	 */
	onWinShow: function(){
		var me = this,
			dock,
			visible;
		/**
		 * Fire Event
		 */
		me.fireEvent('orderswindowhide', me);
		/**
		 * set current patient data to panel
		 */
		me.pid = app.patient.pid;
		me.eid = app.patient.eid;
		/**
		 * read only stuff
		 */
		me.setTitle(app.patient.name + ' - ' + i18n('orders') + (app.patient.readOnly ? ' - <span style="color:red">[' + i18n('read_mode') + ']</span>' : ''));
		me.setReadOnly(app.patient.readOnly);


		/**
		 * Doctors Notes stuff
		 */
		me.doctorsNoteBody.reset();
		me.doctorsNoteTplCombo.reset();
		/**
		 * This will hide encounter panels and
		 * switch to notes panel if eid is null
		 */
		dock = this.tabPanel.getDockedItems()[0];
		visible = this.eid != null;
		dock.items.items[0].setVisible(visible);
		dock.items.items[1].setVisible(visible);
		dock.items.items[2].setVisible(visible);
		if(visible) me.encounderIcdsCodes.getStore().load({params: {eid: me.eid}});
		if(!visible) me.cardSwitch('notes');
	},

	/**
	 * OK!
	 * Loads patientDocumentsStore with new documents
	 */
	onWinHide: function(){
		var me = this;

		me.pid = null;
		me.eid = null;

		/**
		 * Fire Event
		 */
		me.fireEvent('orderswindowhide', me);
		if(app.getActivePanel().$className == 'App.view.patient.Summary'){
			app.getActivePanel().loadStores();
		}

	}

});