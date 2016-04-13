/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

Ext.define('App.model.administration.GeoIpLocation', {
    extend: 'Ext.data.Model',
    table: {
        name: 'geo_ip_location',
        comment: 'IPs Country codes'
    },
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'ip_start',
            type: 'string',
            len: 80
        },
        {
            name: 'ip_end',
            type: 'string',
            len: 80
        },
        {
            name: 'ip_start_num',
            type: 'int',
            len: 15
        },
        {
            name: 'ip_end_num',
            type: 'int',
            len: 15
        },
        {
            name: 'country',
            type: 'string',
            len: 30
        },
        {
            name: 'country_code',
            type: 'string',
            len: 30
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: 'GeoIpLocation.getAllLocations'
        }
    }
});
