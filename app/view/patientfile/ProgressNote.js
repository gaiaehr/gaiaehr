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
Ext.define('App.view.patientfile.ProgressNote', {
	extend           : 'Ext.panel.Panel',
    alias            : 'widget.progressnote',
    loadMask         : false,
	initComponent: function() {
		var me = this;

        me.tpl = new Ext.XTemplate(
            '<div class="progressNote">' +
            '   <div class="secession general-data">' +
            '       <div class="title"> General </div>' +
            '       <table width="100%" ">' +
            '           <tr>' +
            '               <td>' +
            '                   <div class="header row">Name: {patient_name} </div>' +
            '                   <div class="header row">Record: #{pid} </div>' +
            '                   <div class="header row">Provider date: {open_by} </div>' +
            '                   <div class="header row">Onset Date: {onset_date} </div>' +
            '                   <div class="header row">Signed by: {signed_by} </div>' +
            '               </td>' +
            '               <td>' +
            '                   <div class="header row">Service date: {start_date} </div>' +
            '                   <div class="header row">Visit Category: {visit_category} </div>' +
            '                   <div class="header row">Facility: {facility} </div>' +
            '                   <div class="header row">Sensitivity: {sensitivity} </div>' +
            '                   <div class="header row">Close On: {close_date} </div>' +
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
            '           <p><span>Subjective:</span> {subjective} </p>' +
            '           <p><span>Objective:</span> {objective}</p>' +
            '           <p><span>Assessment:</span> {assessment}</p>' +
            '           <p><span>Plan:</span> {plan}</p>' +
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
            '                           <div class="column">' +
            '                               <div class="header row">Date & Time</div>' +
            '                               <div class="header row">Weight Lbs</div>' +
            '                               <div class="header row">Weight Kg</div>' +
            '                               <div class="header row">Height in</div>' +
            '                               <div class="header row">Height cm</div>' +
            '                               <div class="header row">BP systolic</div>' +
            '                               <div class="header row">BP diastolic</div>' +
            '                               <div class="header row">Pulse</div>' +
            '                               <div class="header row">Respiration</div>' +
            '                               <div class="header row">Temp F</div>' +
            '                               <div class="header row">Temp C</div>' +
            '                               <div class="header row">Temp Location</div>' +
            '                               <div class="header row">Oxygen Saturation</div>' +
            '                               <div class="header row">Head Circumference in</div>' +
            '                               <div class="header row">Head Circumference cm</div>' +
            '                               <div class="header row">Waist Circumference in</div>' +
            '                               <div class="header row">Waist Circumference cm</div>' +
            '                               <div class="header row">BMI</div>' +
            '                               <div class="header row">BMI Status</div>' +
            '                               <div class="header row">Other Notes</div>' +
            '                               <div class="header row">Administer</div>' +
            '                           </div>' +
            '                       </td>' +
            '                       <tpl for="vitals">' +
            '                           <td>' +
            '                               <div class="column">' +
            '                                   <div class="row" style="white-space: nowrap">{date}</div>' +
            '                                   <div class="row">{weight_lbs}</div>' +
            '                                   <div class="row">{weight_kg}</div>' +
            '                                   <div class="row">{height_in}</div>' +
            '                                   <div class="row">{height_cm}</div>' +
            '                                   <div class="row">{bp_systolic}</div>' +
            '                                   <div class="row">{bp_diastolic}</div>' +
            '                                   <div class="row">{pulse}</div>' +
            '                                   <div class="row">{respiration}</div>' +
            '                                   <div class="row">{temp_f}</div>' +
            '                                   <div class="row">{temp_c}</div>' +
            '                                   <div class="row">{temp_location}</div>' +
            '                                   <div class="row">{oxygen_saturation}</div>' +
            '                                   <div class="row">{head_circumference_in}</div>' +
            '                                   <div class="row">{head_circumference_cm}</div>' +
            '                                   <div class="row">{waist_circumference_in}</div>' +
            '                                   <div class="row">{waist_circumference_cm}</div>' +
            '                                   <div class="row">{bmi}</div>' +
            '                                   <div class="row">{bmi_status}</div>' +
            '                                   <div class="row">{other_notes}</div>' +
            '                                   <div class="row">{administer}</div>' +
            '                               </div>' +
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
