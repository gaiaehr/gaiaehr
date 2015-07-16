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

Ext.define('Modules.reportcenter.controller.ReportCenter', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'ReportCenterPanel',
			selector: '#ReportCenterPanel'
		}
	],

	/**
	 * Function to add categories with the respective with to the
	 * Report Center Panel
	 */
	addCategory: function(category, width){
		return this.getReportCenterPanel().add(Ext.create('Ext.container.Container', {
			cls: 'CategoryContainer',
			width: width,
			layout: 'anchor',
			items: [
				{
					xtype: 'container',
					cls: 'title',
					margin: '0 0 5 0',
					html: category
				}
			]
		}));
	},

	/**
	 * Function to add Items to the category
	 */
	addReportByCategory: function(category, text, handler){
		return category.add(Ext.create('Ext.button.Button', {
			cls: 'CategoryContainerItem',
			anchor: '100%',
			margin: '0 0 5 0',
			textAlign: 'left',
			text: text,
			handler: handler
		}));
	},


});