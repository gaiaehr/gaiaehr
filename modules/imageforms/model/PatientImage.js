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
Ext.define('Modules.imageforms.model.PatientImage', {
	extend: 'Ext.data.Model',
	table: {
		name: 'patient_images',
		comment: 'patient images added in the image form panel'
	},
	fields: [
		{
			name: 'id',
			type: 'int'
		},
		{
			name: 'pid',
			type: 'int',
			index: true
		},
		{
			name: 'eid',
			type: 'int',
			index: true
		},
		{
			name: 'notes',
			type: 'string',
			dataType: 'text'
		},
		{
			name: 'image',
			type: 'string',
			dataType: 'mediumtext'
		},
		{
			name: 'drawing',
			type: 'string',
			dataType: 'mediumtext'
		},
		{
			name: 'create_uid',
			type: 'int',
			index: true
		},
		{
			name: 'update_uid',
			type: 'int',
			index: true
		},
		{
			name: 'create_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
		},
		{
			name: 'update_date',
			type: 'date',
			dateFormat: 'Y-m-d H:i:s',
			index: true
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'ImageForm.getImages',
			create: 'ImageForm.addImage',
			update: 'ImageForm.updateImage',
			destroy: 'ImageForm.destroyImage'
		}
	}
});