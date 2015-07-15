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

Ext.define('App.model.administration.TemplatePanelTemplate', {
	extend: 'Ext.data.Model',
	table: {
		name: 'template_panels_templates'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'panel_id',
			type: 'int'
		},
		{
			name: 'template_type',
			type: 'string',
			len: 80,
			comment: 'rx lab rad etc'
		},
		{
			name: 'description',
			type: 'string',
			len: 300
		},
		{
			name: 'template_data',
			type: 'string',
			dataType: 'mediumtext'
		},
		{
			name: 'active',
			type: 'bool'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'TemplatePanels.getTemplatePanelTemplates',
			create: 'TemplatePanels.createTemplatePanelTemplate',
			update: 'TemplatePanels.updateTemplatePanelTemplate',
			destroy: 'TemplatePanels.deleteTemplatePanelTemplate'
		},
		reader: {
			root: 'data'
		}
	}
});
