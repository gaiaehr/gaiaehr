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

Ext.define('App.model.administration.TemplatePanel', {
	extend: 'Ext.data.Model',
	table: {
		name: 'template_panels'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'specialty_id',
			type: 'int'
		},
		{
			name: 'description',
			type: 'string',
			len: 300
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'TemplatePanels.getTemplatePanels',
			create: 'TemplatePanels.createTemplatePanel',
			update: 'TemplatePanels.updateTemplatePanel',
			destroy: 'TemplatePanels.deleteTemplatePanel'
		},
		reader: {
			root: 'data'
		}
	},
	hasMany: [
		{
			model: 'App.model.administration.TemplatePanelTemplate',
			name: 'templates',
			foreignKey: 'panel_id',
			storeConfig: {
				groupField: 'template_type'
			}
		}
	]
});
