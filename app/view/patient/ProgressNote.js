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

Ext.define('App.view.patient.ProgressNote', {
	extend           : 'Ext.panel.Panel',
    alias            : 'widget.progressnote',
    bodyPadding      : 5,
    autoScroll       : true,
    loadMask         : false,
	initComponent: function() {
		var me = this;

        me.tpl = new Ext.XTemplate(
            '<div class="progressNote">' +
            '   <div class="secession general-data">' +
            '       <div class="title"> ' + _('general') + ' </div>' +
            '       <table width="100%">' +
            '           <tr>' +
            '               <td>' +
            '                   <div class="header row">' + _('name') + ': {patient_name} </div>' +
            '                   <div class="header row">' + _('record') + ': #{pid} </div>' +
            '                   <div class="header row">' + _('provider_date') + ': {open_by} </div>' +
            '                   <div class="header row">' + _('onset_date') + ': {[values.onset_date || "-"]} </div>' +
            '                   <div class="header row">' + _('provider') + ': {[values.signed_by || "-"]} </div>' +
            '               </td>' +
            '               <td>' +
            '                   <div class="header row">' + _('service_date') + ': {service_date} </div>' +
            '                   <div class="header row">' + _('visit_category') + ': {visit_category} </div>' +
            '                   <div class="header row">' + _('facility') + ': {facility} </div>' +
            '                   <div class="header row">' + _('priority') + ': {priority} </div>' +
            '                   <div class="header row">' + _('signed_on') + ': {[values.close_date || "-"]} </div>' +
            '               </td>' +
            '           </tr>' +
            '           <tr>' +
            '               <td colspan="2">' +
            '                   <div class="header row" style="white-space: normal;">' + _('chief_complaint') + ': {brief_description} </div>' +
            '               </td>' +
            '           </tr>' +
            '       </table>' +
            '   </div>' +
            /**
             * Review of System Secession
             */
            '   <tpl if="reviewofsystems">' +
            '       <div class="secession">' +
            '           <div class="title"> ' + _('review_of_systems') + ' </div>' +
            '           <tpl for="reviewofsystems">' +
            '               <tpl if="this.isNotNull(value)">' +
            '                   <div class="pblock"> {name}: {value} </div>' +
            '               </tpl>' +
            '           </tpl>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Review of System Checks Secession
             */
            '   <tpl if="reviewofsystemschecks">' +
            '       <div class="secession">' +
            '           <div class="title"> ' + _('review_of_system_checks') + ' </div>' +
            '           <tpl for="reviewofsystemschecks">' +
            '               <tpl if="this.isNotNull(value)">' +
            '                   <div class="pblock"> {name}: {value} </div>' +
            '               </tpl>' +
            '           </tpl>' +
            '       </div>' +
            '   </tpl>' +

            /**
             * SOAP Secession
             */
            '   <tpl for="soap">' +
            '       <div class="secession">' +
            '           <div class="title"> ' + _('soap') + ' </div>' +
            '           <p><span>' + _('subjective') + ':</span> {[this.doHtmlDecode(values.subjective) || "-"]} </p>' +
            '           <p><span>' + _('objective') + ':</span> {[this.doHtmlDecode(values.objective) || "-"]}</p>' +
            '           <p><span>' + _('assessment') + ':</span> {[this.doHtmlDecode(values.assessment) || "-"]}</p>' +
            '           <p><span>' + _('plan') + ':</span> {[this.doHtmlDecode(values.plan) || "-"]}</p>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Speech Dictation Secession
             */
            '   <tpl for="speechdictation">' +
            '       <div class="secession">' +
            '           <div class="title"> ' + _('speech_dictation') + ' </div>' +
            '           <p><span>' + _('dictation') + ':</span> {dictation}</p>' +
            '           <p><span>' + _('additional_notes') + ':</span> {additional_notes}</p>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Vitals Secession
             */
            '   <tpl if="vitals">' +
            '       <div class="secession vitals-data">' +
            '           <div class="title"> ' + _('vitals') + ' </div>' +
            '           <div style="overflow-x: auto">' +
            '               <table>' +
            '                   <tr>' +
            '                       <td>' +
            '                          <table class="x-grid-table x-grid-table-vitals vitals-column">' +
            '                              <tbody>' +
            '                                  <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell x-grid-table-vitals-date">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n['date_&_time'] + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('weight_lbs') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('weight_kg') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('height_in') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('height_cm') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('bp_systolic') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('bp_diastolic') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('pulse') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('respiration') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('temp_f') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('temp_c') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('temp_location') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('oxygen_saturation') + '%</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('head_circumference_in') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('head_circumference_cm') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('waist_circumference_in') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('waist_circumference_cm') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('bmi') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('bmi_status') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('other_notes') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + _('administer') + '<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n['Sign by'] + '<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                               </tbody>' +
            '                           </table>' +
            '                       </td>' +
            '                       <tpl for="vitals">' +
            '                           <td>' +
            '                           <table class="x-grid-table x-grid-table-vitals vitals-column">' +
            '                               <tbody>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell x-grid-table-vitals-date">' +
            '                                           <div class="x-grid-cell-inner ">{date}</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row first">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.weight_lbs)]}</div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row first">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.weight_kg)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
		        '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.height_in)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.height_cm)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.bp_systolic)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.bp_diastolic)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.pulse)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.respiration)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.temp_f)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.temp_c)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.temp_location.toUpperCase())]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.oxygen_saturation)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.head_circumference_in)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.head_circumference_cm)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
		        '                       <tpl if="!this.isMetric()">' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.waist_circumference_in)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       <tpl else>',
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.waist_circumference_cm)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
	            '                       </tpl>',
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.bmi)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.bmi_status)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[this.getVitalsValue(values.other_notes)]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[(values.administer_by == null || values.administer_by == " ") ? "-" : values.administer_by]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row  x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[(values.authorized_by == null || values.authorized_by == " ") ? "-" : values.authorized_by]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                               </tbody>' +
            '                           </table>' +
            '                           </td>' +
            '                       </tpl>' +
            '                   </tr>' +
            '               </table>' +
            '           </div>' +
            '       </div>' +
            '   </tpl>' +
            '</div>',
            {

	            isNotNull: function(value){
	                return value != 'null' && value != null;
	            },

		        isNotEmpty:function(value){

	            },

		        getVitalsValue:function(value){
			        return (value == 0 || value == null) ? '-' : value;
		        },

	            isMetric:function(){
		            return g('units_of_measurement') == 'metric';
	            },

	            doHtmlDecode:function(v){
		            return Ext.String.htmlDecode(v);
	            }



            }
        );

		me.callParent(arguments);
	}

});
