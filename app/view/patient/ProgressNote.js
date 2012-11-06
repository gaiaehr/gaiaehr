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
            '       <div class="title"> ' + i18n('general') + ' </div>' +
            '       <table width="100%">' +
            '           <tr>' +
            '               <td>' +
            '                   <div class="header row">' + i18n('name') + ': {patient_name} </div>' +
            '                   <div class="header row">' + i18n('record') + ': #{pid} </div>' +
            '                   <div class="header row">' + i18n('provider_date') + ': {open_by} </div>' +
            '                   <div class="header row">' + i18n('onset_date') + ': {[values.onset_date || "-"]} </div>' +
            '                   <div class="header row">' + i18n('signed_by') + ': {[values.signed_by || "-"]} </div>' +
            '               </td>' +
            '               <td>' +
            '                   <div class="header row">' + i18n('service_date') + ': {service_date} </div>' +
            '                   <div class="header row">' + i18n('visit_category') + ': {visit_category} </div>' +
            '                   <div class="header row">' + i18n('facility') + ': {facility} </div>' +
            '                   <div class="header row">' + i18n('priority') + ': {priority} </div>' +
            '                   <div class="header row">' + i18n('close_on') + ': {[values.close_date || "-"]} </div>' +
            '               </td>' +
            '           </tr>' +
            '           <tr>' +
            '               <td colspan="2">' +
            '                   <div class="header row" style="white-space: normal;">' + i18n('brief_description') + ': {brief_description} </div>' +
            '               </td>' +
            '           </tr>' +
            '       </table>' +
            '   </div>' +
            /**
             * Review of System Secession
             */
            '   <tpl if="reviewofsystems">' +
            '       <div class="secession">' +
            '           <div class="title"> ' + i18n('review_of_systems') + ' </div>' +
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
            '           <div class="title"> ' + i18n('review_of_system_checks') + ' </div>' +
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
            '           <div class="title"> ' + i18n('soap') + ' </div>' +
            '           <p><span>' + i18n('subjective') + ':</span> {[values.subjective || "-"]} </p>' +
            '           <p><span>' + i18n('objective') + ':</span> {[values.objective || "-"]}</p>' +
            '           <p><span>' + i18n('assessment') + ':</span> {[values.assessment || "-"]}</p>' +
            '           <p><span>' + i18n('plan') + ':</span> {[values.plan || "-"]}</p>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Speech Dictation Secession
             */
            '   <tpl for="speechdictation">' +
            '       <div class="secession">' +
            '           <div class="title"> ' + i18n('speech_dictation') + ' </div>' +
            '           <p><span>' + i18n('dictation') + ':</span> {dictation}</p>' +
            '           <p><span>' + i18n('additional_notes') + ':</span> {additional_notes}</p>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Vitals Secession
             */
            '   <tpl if="vitals">' +
            '       <div class="secession vitals-data">' +
            '           <div class="title"> ' + i18n('vitals') + ' </div>' +
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
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('weight_lbs') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('weight_kg') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('height_in') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('height_cm') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('bp_systolic') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('bp_diastolic') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('pulse') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('respiration') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('temp_f') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('temp_c') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('temp_location') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('oxygen_saturation') + '%</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('head_circumference_in') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('head_circumference_cm') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('waist_circumference_in') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('waist_circumference_cm') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('bmi') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('bmi_status') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('other_notes') + '</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">' + i18n('administer') + '<div>' +
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
            '                                   <tr class="x-grid-row first">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.weight_lbs || "-"]}</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.weight_kg || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.height_in || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.height_cm || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.bp_systolic || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.bp_diastolic || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.pulse || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.respiration || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.temp_f || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.temp_c || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.temp_location ? values.temp_location.toUpperCase() : "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.oxygen_saturation || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.head_circumference_in || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.head_circumference_cm || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.waist_circumference_in || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.waist_circumference_cm || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.bmi || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.bmi_status || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row x-grid-row-alt">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[values.other_notes || "-"]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">{[(values.administer_by == null || values.administer_by == " ") ? "-" : values.administer_by]}<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row ">' +
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
            isNotEmpty:function(a,b,c,d){
                say(a);
                say(b);
                say(c);
                say(d);
            }


        });

		me.callParent(arguments);
	}

});
