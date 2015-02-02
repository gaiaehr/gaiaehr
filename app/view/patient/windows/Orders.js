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
		'App.view.patient.RxOrders',
		'App.view.patient.DoctorsNotes'
	],
	title: i18n('order_window'),
	closeAction: 'hide',
	bodyStyle: 'background-color:#fff',
	modal: true,
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
				height: Ext.getBody().getHeight() < 700 ? (Ext.getBody().getHeight() - 100) : 600,
				width: Ext.getBody().getWidth() < 1550 ? (Ext.getBody().getWidth() - 50) : 1500,
				plain: true,
				items: [
					/**
					 * LAB ORDERS PANEL
					 */
					{
						xtype: 'patientlaborderspanel'
					},
					/**
					 * X-RAY PANEL
					 */
					{
						xtype: 'patientradorderspanel'
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
						xtype: 'patientdoctorsnotepanel'
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
	 * On window shows
	 */
	onWinShow: function(){
		var me = this,
			p = me.down('tabpanel'),
			w = Ext.getBody().getWidth() < 1550 ? (Ext.getBody().getWidth() - 50) : 1500,
			h = Ext.getBody().getHeight() < 700 ? (Ext.getBody().getHeight() - 100) : 600;

		p.setSize(w, h);

		me.alignTo(Ext.getBody(), 'c-c');
		/**
		 * Fire Event
		 */
		me.fireEvent('orderswindowshow', me);
		/**
		 * read only stuff
		 */
		me.setTitle(app.patient.name + ' - ' + i18n('orders') + (app.patient.readOnly ? ' - <span style="color:red">[' + i18n('read_mode') + ']</span>' : ''));
		me.setReadOnly(app.patient.readOnly);
	},

	/**
	 * OK!
	 * Loads patientDocumentsStore with new documents
	 */
	onWinHide: function(){
		var me = this;
		/**
		 * Fire Event
		 */
		me.fireEvent('orderswindowhide', me);
		if(app.getActivePanel().$className == 'App.view.patient.Summary'){
			app.getActivePanel().loadStores();
		}

	}

});