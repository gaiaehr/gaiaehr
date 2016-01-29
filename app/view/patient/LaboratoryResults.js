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

Ext.define('App.view.patient.LaboratoryResults', {
	extend           : 'Ext.view.View',
    alias            : 'widget.lalboratoryresultsdataview',
	trackOver        : true,
    cls              : 'vitals',
    itemSelector     : 'table.vitals-column',
    overItemCls      : 'vitals-column-over',
    selectedItemCls  : 'vitals-column-selected',
    loadMask         : true,
    singleSelect     : true,
	emptyText        : '<div style="color: #cbcbcb; font-size: 40px; text-align:center">' + _('no_laboratory_results_to_display') + '</div>',
	initComponent: function() {
		var me = this;
        me.tpl = '<table>' +
	        '   <tbody>' +
            '       <tr>' +
            '       <tpl for=".">' +
            '           <td>' +
            '               <table class="x-grid-table x-grid-table-vitals vitals-column {[ (values.auth_uid == null || values.auth_uid == 0 ) ? "vitals-column-caution" : ""]}">' +
	        '                   <tbody>' +
            '                       <tr class="grid-row">' +
	        '                           <td class="grid-cell" style="border:none; padding:0">' +
	        '                               <div class="x-grid-cell-inner x-panel-header x-panel-header-default" style="border:none; font-weight:bold; padding: 5px 10px; margin-bottom:5px">{[Ext.Date.format(values.date, "Y-m-d")]}</div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tpl for="columns">' +
            '                           <tr class="x-grid-row">' +
	        '                               <td class="x-grid-cell">' +
	        '                                   <div class="x-grid-cell-inner ">{observation_value} {unit}</div>' +
	        '                               </td>' +
	        '                           </tr>' +
            '                       </tpl>' +
            '                   </tbody>' +
	        '               </table>' +
	        '           </td>' +
            '       </tpl>' +
            '       </tr>' +
	        '   </tbody>' +
            '</table>';

		me.callParent(arguments);
	}

});
