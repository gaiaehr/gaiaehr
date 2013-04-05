/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

Ext.define('App.model.administration.InsuranceCompany', {
    extend:'Ext.data.Model',
    table: {
        name:'insurance_companies',
        comment:'Insurance Companies'
    },
    fields:[
        { name:'id', type:'int', comment: 'Insurance ID'},
        { name:'name', type:'string' },
        { name:'attn', type:'string' },
        { name:'cms_id', type:'string' },
        { name:'freeb_type', type:'string' },
        { name:'x12_receiver_id', type:'string' },
        { name:'x12_default_partner_id', type:'string' },
        { name:'alt_cms_id', type:'string' },
        { name:'address_id', type:'int' },
        { name:'line1', type:'string' },
        { name:'line2', type:'string' },
        { name:'city', type:'string' },
        { name:'state', type:'string' },
        { name:'zip', type:'string' },
        { name:'plus_four', type:'string' },
        { name:'country', type:'string' },
        { name:'address_full', type:'string' },
        { name:'phone_id', type:'int' },
        { name:'phone_country_code', type:'string' },
        { name:'phone_area_code', type:'string' },
        { name:'phone_prefix', type:'string' },
        { name:'phone_number', type:'string' },
        { name:'phone_full', type:'string' },
        { name:'fax_id', type:'int' },
        { name:'fax_country_code', type:'string' },
        { name:'fax_area_code', type:'string' },
        { name:'fax_prefix', type:'string' },
        { name:'fax_number', type:'string' },
        { name:'fax_full', type:'string' },
        { name:'active', type:'bool' }
    ],
    proxy:{
        type:'direct',
        api:{
            read:Practice.getInsurances,
            create:Practice.addInsurance,
            update:Practice.updateInsurance
        }
    }
});