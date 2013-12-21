/**
 * GaiaEHR (Electronic Health Records)
 * RenderPanel.js
 * UX
 * Copyright (C) 2012 Ernesto Rodriguez
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
Ext.define('App.ux.RenderPanel', {
	extend: 'Ext.container.Container',
	alias: 'widget.renderpanel',
	cls: 'RenderPanel',
	layout: 'border',
	frame: false,
	border: false,
	pageLayout: 'fit',
	pageBody: [],
	pageTitle: '',
	pageButtons: null,
	pageTBar: null,
	pageBBar: null,
	pagePadding: null,
	showRating: false,

	initComponent: function(){
		var me = this;
		Ext.apply(me, {
			items: [
				me.mainHeader = Ext.widget('container', {
					cls: 'RenderPanel-header',
					itemId: 'RenderPanel-header',
					region: 'north',
					height: 33
				}),
				{
					cls: 'RenderPanel-body-container',
					itemId: 'RenderPanel-body-container',
					xtype: 'container',
					region: 'center',
					layout: 'fit',
					padding: this.pagePadding == null ? 5 : this.pagePadding,
					items: [
						me.mainBoddy = Ext.widget('panel', {
							cls: 'RenderPanel-body',
							frame: true,
							layout: this.pageLayout,
							border: false,
							itemId: 'pageLayout',
							defaults: {frame: false, border: false, autoScroll: true},
							items: me.pageBody,
							tbar: me.pageTBar,
							bbar: me.pageBBar,
							buttons: me.pageButtons
						})
					]
				}
			]
		}, this);

		me.pageTitleDiv = me.mainHeader.add(
			Ext.widget('container', {
				cls: 'panel_title',
				style: 'float:left',
				html: me.pageTitle
			})
		);

		me.pageReadOnlyDiv = me.mainHeader.add(
			Ext.widget('container', {
				style: 'float:left'
			})
		);

		if(me.showRating){
			me.pageRankingDiv = me.mainHeader.add(
				Ext.widget('ratingField', {
					style: 'float:left',
					listeners: {
						scope: me,
						click: function(field, val){
							Patient.setPatientRating({pid: app.patient.pid, rating: val}, function(){
								app.msg('Sweet!', i18n('record_saved'))
							});
						}
					}
				})
			);
		}

		me.pageTimerDiv = me.mainHeader.add(
			Ext.widget('container', {
				style: 'float:right'
			})
		);

		me.callParent(arguments);
	},

	updateTitle: function(pageTitle, readOnly, timer){
		this.pageTitleDiv.update(pageTitle);
		this.pageReadOnlyDiv.update(readOnly ? i18n('read_only') : '');
		this.pageTimerDiv.update(timer);
	},

	getPageHeader: function(){
		return this.getComponent('RenderPanel-header');
	},

	getPageBodyContainer: function(){
		return this.getComponent('RenderPanel-body-container');
	},

	getPageBody: function(){
		return this.mainBoddy;
	},

	onActive: function(callback){
		if(typeof callback == 'function') callback(true);
	}


});
