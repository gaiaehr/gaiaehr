/*
 GaiaEHR (Electronic Health Records)
 Facilities.js
 Copyright (C) 201 Certun

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
Ext.define('App.model.administration.Facility', {
    extend: 'Ext.data.Model',
    extend: 'Ext.data.Model',
    table: {
        name:'facility',
        engine:'InnoDB',
        autoIncrement:1,
        charset:'utf8',
        collate:'utf8_bin',
        comment:'Account'
    },
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'name',
            type: 'string'
        },
        {
            name: 'active',
            type: 'bool'
        },
        {
            name: 'phone',
            type: 'string'
        },
        {
            name: 'fax',
            type: 'string'
        },
        {
            name: 'street',
            type: 'string'
        },
        {
            name: 'city',
            type: 'string'
        },
        {
            name: 'state',
            type: 'string'
        },
        {
            name: 'postal_code',
            type: 'string'
        },
        {
            name: 'country_code',
            type: 'string'
        },
        {
            name: 'federal_ein',
            type: 'string'
        },
        {
            name: 'service_location',
            type: 'bool'
        },
        {
            name: 'billing_location',
            type: 'bool'
        },
        {
            name: 'accepts_assignment',
            type: 'bool'
        },
        {
            name: 'pos_code',
            type: 'string'
        },
        {
            name: 'x12_sender_id',
            type: 'string'
        },
        {
            name: 'attn',
            type: 'string'
        },
        {
            name: 'domain_identifier',
            type: 'string'
        },
        {
            name: 'facility_npi',
            type: 'string'
        },
        {
            name: 'tax_id_type',
            type: 'string'
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            read: Facilities.getFacilities,
            create: Facilities.addFacility,
            update: Facilities.updateFacility,
            destroy: Facilities.deleteFacility
        }
    }
});