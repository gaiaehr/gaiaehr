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

Ext.define('App.model.administration.Medications', {
	extend: 'Ext.data.Model',
	fields: [
		{
			name: 'RXCUI',
			type: 'auto'
		},
		{
			name: 'CODE',
			type: 'auto'
		},
		{
			name: 'STR',
			type: 'auto'
		},
		{
			name: 'DST',
			type: 'auto'
		},
		{
			name: 'DRT',
			type: 'auto'
		},
		{
			name: 'DDF',
			type: 'auto'
		},
		{
			name: 'DDFA',
			type: 'auto'
		},
		{
			name: 'RXN_QUANTITY',
			type: 'auto'
		},
		{
			name: 'SAB',
			type: 'auto'
		},
		{
			name: 'RXAUI',
			type: 'auto'
		}
	],
	proxy: {
		type: 'direct',
		api: {
			read: 'Rxnorm.getRXNORMList'
		},
		reader: {
			totalProperty: 'totals',
			root: 'data'
		},
		filterParam: 'query',
		encodeFilters: function(filters){
			return filters[0].value;
		}
	}
});