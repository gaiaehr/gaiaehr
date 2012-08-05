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
    loadMask         : false,
	initComponent: function() {
		var me = this;

        me.tpl = new Ext.XTemplate(
            '<div class="progressNote">' +
            '   <div class="secession general-data">' +
            '       <div class="title"> General </div>' +
            '       <table width="100%">' +
            '           <tr>' +
            '               <td>' +
            '                   <div class="header row">Name: {patient_name} </div>' +
            '                   <div class="header row">Record: #{pid} </div>' +
            '                   <div class="header row">Provider date: {open_by} </div>' +
            '                   <div class="header row">Onset Date: {[values.onset_date || "-"]} </div>' +
            '                   <div class="header row">Signed by: {[values.signed_by || "-"]} </div>' +
            '               </td>' +
            '               <td>' +
            '                   <div class="header row">Service date: {start_date} </div>' +
            '                   <div class="header row">Visit Category: {visit_category} </div>' +
            '                   <div class="header row">Facility: {facility} </div>' +
            '                   <div class="header row">Priority: {priority} </div>' +
            '                   <div class="header row">Close On: {[values.close_date || "-"]} </div>' +
            '               </td>' +
            '           </tr>' +
            '           <tr>' +
            '               <td colspan="2">' +
            '                   <div class="header row" style="white-space: normal;">Brief Description: {brief_description} </div>' +
            '               </td>' +
            '           </tr>' +
            '       </table>' +
            '   </div>' +
            /**
             * Review of System Secession
             */
            '   <tpl if="reviewofsystems">' +
            '       <div class="secession">' +
            '           <div class="title"> Review of System </div>' +
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
            '           <div class="title"> Review of System Checks </div>' +
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
            '           <div class="title"> SOAP </div>' +
            '           <p><span>Subjective:</span> {[values.subjective || "-"]} </p>' +
            '           <p><span>Objective:</span> {[values.objective || "-"]}</p>' +
            '           <p><span>Assessment:</span> {[values.assessment || "-"]}</p>' +
            '           <p><span>Plan:</span> {[values.plan || "-"]}</p>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Speech Dictation Secession
             */
            '   <tpl for="speechdictation">' +
            '       <div class="secession">' +
            '           <div class="title"> Speech Dictation </div>' +
            '           <p><span>Dictation:</span> {dictation}</p>' +
            '           <p><span>Additional Notes:</span> {additional_notes}</p>' +
            '       </div>' +
            '   </tpl>' +
            /**
             * Vitals Secession
             */
            '   <tpl if="vitals">' +
            '       <div class="secession vitals-data">' +
            '           <div class="title"> Vitals </div>' +
            '           <div style="overflow-x: auto">' +
            '               <table>' +
            '                   <tr>' +
            '                       <td>' +
            '                          <table class="x-grid-table x-grid-table-vitals vitals-column">' +
            '                              <tbody>' +
            '                                  <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell x-grid-table-vitals-date">' +
            '                                           <div class="x-grid-cell-inner ">Date & Time</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Weight Lbs</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Weight Kg</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Height in</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Height cm</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">BP systolic</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">BP diastolic</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Pulse</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Respiration</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Temp F</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Temp C</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Temp Location</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Oxygen Saturation</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Head Circumference in</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Head Circumference cm</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Waist Circumference in</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Waist Circumference cm</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">BMI</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">BMI Status</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Other Notes</div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Administer<div>' +
            '                                       </td>' +
            '                                   </tr>' +
            '                                   <tr class="x-grid-row">' +
            '                                       <td class="x-grid-cell">' +
            '                                           <div class="x-grid-cell-inner ">Sign by<div>' +
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
