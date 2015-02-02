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

Ext.define('App.ux.AddTabButton', {
	alias: 'plugin.AddTabButton',
	extend: 'Ext.AbstractPlugin',

	// start defaults
	toolTip: 'Add Tab',     // add btn ToolTip
	iconCls: null,          // add btn icon class
	btnText: '+',           // add btn text, button text is not use if iconCls is set
	forceText: false,       // use the btnText even if an icon is used

	panelConfig: {              // default config for new added panel
		xtype: 'panel',
		title: 'New Tab',
		closable: true,
		hidden: false
	},
	// end defaults

	constructor: function(config){
		this.panelConfig = Ext.apply(this.panelConfig, config.tabConfig || {});
		this.callParent(arguments);
	},

	/**
	 * @param tabPanel
	 */
	init: function(tabPanel){
		var me = this;

		// set tabPanel global
		me.tabPanel = tabPanel;

		if(tabPanel instanceof Ext.TabPanel){
			// add add btn tab to the TabBar
			me.btn = me.tabPanel.getTabBar().add({
				xtype: 'tab',
				minWidth: 25,
				text: me.iconCls && !me.forceText ? '' : me.btnText, // if icon is used remove text
				iconCls: me.iconCls,
				tooltip: me.toolTip,
				handler: me.onAddTabClick,
				closable: false,
				scope: me
			});

			// fix the tab margin and padding
			me.btn.on('render', function(){
				if(me.iconCls && !me.forceText){
					var style;
					if(me.tabPanel.tabPosition == 'top'){
						style = 'margin: 0 0 3px 0; padding: 0';
					} else if(me.tabPanel.tabPosition == 'bottom'){
						style = 'margin: 3px 0 0 0; padding: 0';
					}
					me.btn.btnWrap.applyStyles(style);
				}
			});
		}
	},

	/**
	 *  Adds new Tab to TabPanel
	 */
	onAddTabClick: function(){
		var tab = this.tabPanel.add(this.panelConfig);
		this.tabPanel.setActiveTab(tab);
	},

	/**
	 * disable or enable the Add button
	 * @param value
	 */
	setDisabled: function(value){
		this.btn.setDisabled(value);
	},

	/**
	 * hide or show the Add button
	 * @param value
	 */
	setVisible: function(value){
		this.btn.setVisible(value);
	}
});