/**
 * Encounter.ejs.php
 * Encounter Panel
 * v0.0.1
 *
 * Author: Ernesto J. Rodriguez
 * Modified:
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace Encounter.getEncounter
 * @namespace Encounter.createEncounter
 * @namespace Encounter.checkOpenEncounters
 * @namespace Encounter.closeEncounter
 * @namespace Encounter.getVitals
 * @namespace Encounter.addVitals
 */
Ext.define('App.view.patient.Vitals', {
	extend           : 'Ext.view.View',
    alias            : 'widget.vitalsdataview',
	trackOver        : true,
    cls              : 'vitals',
    itemSelector     : 'table.vitals-column',
    overItemCls      : 'vitals-column-over',
    selectedItemCls  : 'vitals-column-selected',
    loadMask         : true,
    singleSelect     : true,
	emptyText        : '<div class="view_empty_text"><span>' + i18n('no_vitals_to_display') + '</span></div>',
	initComponent: function() {
		var me = this;

        me.tpl = '<table>' +
	        '   <tbody>' +
            '       <tr>' +
            '       <tpl for=".">' +
            '           <td>' +
            '               <table class="x-grid-table x-grid-table-vitals vitals-column {[ (values.auth_uid == null || values.auth_uid == 0 ) ? "vitals-column-caution" : ""]}">' +
	        '                   <tbody>' +
            '                       <tr class="x-grid-row">' +
	        '                           <td class="x-grid-cell x-grid-table-vitals-date">' +
	        '                               <div class="x-grid-cell-inner ">{[Ext.Date.format(values.date, "Y-m-d")]}<br>{[Ext.Date.format(values.date, "h:i:s a")]}</div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row first">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.weight_lbs || "-"]}</div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.weight_kg || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.height_in || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.height_cm || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.bp_systolic || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.bp_diastolic || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.pulse || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.respiration || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.temp_f || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.temp_c || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.temp_location ? values.temp_location.toUpperCase() : "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.oxygen_saturation || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.head_circumference_in || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.head_circumference_cm || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.waist_circumference_in || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.waist_circumference_cm || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.bmi || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.bmi_status || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row x-grid-row-alt">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[values.other_notes || "-"]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[(values.administer_by == null || values.administer_by == " ") ? "-" : values.administer_by]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
            '                       <tr class="x-grid-row ">' +
	        '                           <td class="x-grid-cell">' +
	        '                               <div class="x-grid-cell-inner ">{[(values.authorized_by == null || values.authorized_by == " ") ? "-" : values.authorized_by]}<div>' +
	        '                           </td>' +
	        '                       </tr>' +
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
