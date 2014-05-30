<?php
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

if (!defined('_GaiaEXEC')) die('No direct access allowed.');
?>
<html>
	<head>
		<script type="text/javascript">
			var app,
				acl = {},
				user = {},
				settings = {},
				globals = {},
				ext = '<?php print EXTJS ?>',
				version = '<?php print VERSION ?>',
				site = '<?php print $site ?>',
				requires,
				AppClipboard;
		</script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>GaiaEHR :: Loading...</title>
		<link rel="stylesheet" type="text/css" href="resources/css/dashboard.css">
		<link rel="stylesheet" type="text/css" href="resources/css/ext-all-gray.css">
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar.css"/>
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar-colors.css"/>
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/recurrence.css"/>
		<link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
		<link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<!-- Loading Mask -->
		<div id="mainapp-loading-mask" class="x-mask mitos-mask"></div>
		<div id="mainapp-x-mask-msg">
			<div id="mainapp-loading" class="x-mask-msg mitos-mask-msg">
				<div>
					Loading GaiaEHR...
				</div>
			</div>
		</div>

        <!-- slide down message div -->
        <div id="msg-div"></div>



        <!-- Ext library -->
		<script type="text/javascript" src="lib/<?php print EXTJS ?>/ext-all-debug.js"></script>


		<!-- JSrouter and Ext.deirect API files -->
		<script src="JSrouter.php?site=<?php print $site ?>"></script>
		<script src="data/api.php?site=<?php print $site ?>"></script>
		<script type="text/javascript" src="lib/ZeroClipboard/ZeroClipboard.js"></script>

        <script type="text/javascript">

            window.i18n = function(key){
                return window.lang[key] || '*'+key+'*';
            };

            window.say = function(a){
                console.log(a);
            };

            window.g = function(global){
	            return window.globals[global] || false;
            };

            window.a = function(acl){
	            return window.acl[acl] || false;
            };

            ZeroClipboard.config( { moviePath: 'lib/ZeroClipboard/ZeroClipboard.swf' } );
            AppClipboard = new ZeroClipboard();
            AppClipboard.on("complete", function (client, args) {
	            app.msg(i18n('sweet'), args.text + ' - ' + i18n('copied_to_clipboard'));
            });

			/**
			 * Ext Localization file
			 * Using a anonymous function, in javascript.
			 * Is not intended to be used globally just this once.
			 */
            (function(){
                document.write('<script type="text/javascript" src="lib/<?php print EXTJS ?>/locale/' + i18n('i18nExtFile') + '?_v' + version + '"><\/script>')
            })();            // Set and enable Ext.loader for dynamic class loading
            Ext.Loader.setConfig({
                enabled: true,
                disableCaching: false,
                paths: {
                    'Ext': 'lib/<?php print EXTJS ?>/src',
                    'Ext.ux': 'lib/extjs-4.2.1/examples/ux',
                    'App': 'app',
                    'Modules': 'modules',
                    'Extensible': 'lib/extensible-1.5.1/src'
                }
            });

			for(var x = 0; x < App.data.length; x++){
				Ext.direct.Manager.addProvider(App.data[x]);
			}
			Ext.direct.Manager.on('exception', function(e, o){
				say(e);
				app.alert(
					'<p><span style="font-weight:bold">'+ (e.where != 'undefined' ? e.message : e.message.replace(/\n/g,''))  +'</span></p><hr>' +
						'<p>'+ (typeof e.where != 'undefined' ? e.where.replace(/\n/g,'<br>') : e.data) +'</p>',
					'error'
				);
			});
		</script>
		<script type="text/javascript" src="app/ux/Overrides.js"></script>
		<script type="text/javascript" src="app/ux/VTypes.js"></script>

		<script type="text/javascript">
			requires = [
				'Ext.ux.LiveSearchGridPanel',
				'Ext.ux.SlidingPager',
				'Ext.ux.PreviewPlugin',
				'Ext.ux.form.SearchField',
				'App.ux.RatingField',
				'App.ux.grid.GridToHtml',
				'App.ux.grid.Printer',

				/**
				 * Load the models, the model are the representative of the database
				 * table structure with modifications behind the PHP counterpart.
				 * All table should be declared here, and Sencha's ExtJS models.
				 * This are spread in all the core application.
				 */
                'App.model.administration.ActiveProblems',
                'App.model.administration.Applications',
                'App.model.administration.DefaultDocuments',
                'App.model.administration.DocumentsTemplates',
                'App.model.administration.DocumentToken',
                'App.model.administration.ExternalDataLoads',
                'App.model.administration.Facility',
                'App.model.administration.FloorPlans',
                'App.model.administration.FloorPlanZones',
                'App.model.administration.FormListOptions',
                'App.model.administration.FormsList',
                'App.model.administration.Globals',
                'App.model.administration.HeadersAndFooters',
                'App.model.administration.ImmunizationRelations',
                'App.model.administration.InsuranceCompany',
                'App.model.administration.LabObservations',
                'App.model.administration.Laboratories',
                'App.model.administration.LayoutTree',
                'App.model.administration.ListOptions',
                'App.model.administration.Lists',
                'App.model.administration.AuditLog',
                'App.model.administration.Medications',
                'App.model.administration.Modules',
                'App.model.administration.ParentFields',
                'App.model.administration.Pharmacies',
                'App.model.administration.PreventiveCare',
                'App.model.administration.PreventiveCareActiveProblems',
                'App.model.administration.PreventiveCareLabs',
                'App.model.administration.PreventiveCareMedications',
                'App.model.administration.Services',
                'App.model.administration.User',
                'App.model.administration.XtypesComboModel',

                'App.model.miscellaneous.OfficeNotes',

                'App.model.account.VoucherLine',
                'App.model.account.Voucher',

                'App.model.fees.Billing',
                'App.model.fees.Checkout',
                'App.model.fees.EncountersPayments',
                'App.model.fees.PaymentTransactions',
                'App.model.navigation.Navigation',

                'App.model.patient.encounter.snippetTree',
				'App.model.patient.encounter.Procedures',

                'App.model.patient.Allergies',
                'App.model.patient.CheckoutAlertArea',
                'App.model.patient.CptCodes',
                'App.model.patient.Dental',
                'App.model.patient.Disclosures',
				'App.model.patient.DismissedAlerts',
				'App.model.patient.DoctorsNote',
				'App.model.patient.Encounter',
                'App.model.patient.EncounterCPTsICDs',
                'App.model.patient.EventHistory',
                'App.model.patient.HCFAOptions',
                'App.model.patient.CVXCodes',
                'App.model.patient.ImmunizationCheck',
                'App.model.patient.LaboratoryTypes',
                'App.model.patient.Insurance',
                'App.model.patient.MeaningfulUseAlert',
                'App.model.patient.Medications',
                'App.model.patient.Notes',
                'App.model.patient.Patient',
				'App.model.patient.PatientActiveProblem',
				'App.model.patient.PatientArrivalLog',
                'App.model.patient.PatientCalendarEvents',
                'App.model.patient.PatientDocuments',
                'App.model.patient.PatientImmunization',
                'App.model.patient.PatientLabsResults',
                'App.model.patient.PatientsLabOrderItems',
                'App.model.patient.PatientSocialHistory',
                'App.model.patient.PatientsOrderObservation',
                'App.model.patient.PatientsOrderResult',
                'App.model.patient.PatientsOrders',
                'App.model.patient.PatientsPrescriptionMedications',
                'App.model.patient.PatientsPrescriptions',
                'App.model.patient.PatientsXrayCtOrders',
                'App.model.patient.PreventiveCare',
                'App.model.patient.QRCptCodes',
                'App.model.patient.Referral',
                'App.model.patient.Reminders',
                'App.model.patient.ReviewOfSystems',
                'App.model.patient.SOAP',
                'App.model.patient.Surgery',
                'App.model.patient.VectorGraph',
                'App.model.patient.VisitPayment',
                'App.model.patient.Vitals',
                'App.model.patient.charts.BMIForAge',
                'App.model.patient.charts.HeadCircumferenceInf',
                'App.model.patient.charts.LengthForAgeInf',
                'App.model.patient.charts.StatureForAge',
                'App.model.patient.charts.WeightForAge',
                'App.model.patient.charts.WeightForAgeInf',
                'App.model.patient.charts.WeightForRecumbentInf',
                'App.model.patient.charts.WeightForStature',
                'App.model.areas.PatientArea',
                'App.model.areas.PoolArea',
                'App.model.areas.PoolDropAreas',
				/**
				 * Load all the stores used by GaiaEHR
				 * this includes ComboBoxes, and other stores used by the web application
				 * most of this stores are consumed by the dataStore directory.
				 */
                'App.store.administration.ActiveProblems',
                'App.store.administration.Applications',
                'App.store.administration.DefaultDocuments',
                'App.store.administration.DocumentsTemplates',
                'App.store.administration.DocumentToken',
                'App.store.administration.ExternalDataLoads',
                'App.store.administration.Facility',
                'App.store.administration.FloorPlans',
                'App.store.administration.FloorPlanZones',
                'App.store.administration.FormListOptions',
                'App.store.administration.FormsList',
                'App.store.administration.Globals',
                'App.store.administration.HeadersAndFooters',
                'App.store.administration.ImmunizationRelations',
                'App.store.administration.InsuranceCompanies',
                'App.store.administration.LabObservations',
                'App.store.administration.Laboratories',
                'App.store.administration.LayoutTree',
                'App.store.administration.ListOptions',
                'App.store.administration.Lists',
                'App.store.administration.AuditLog',
                'App.store.administration.Medications',
                'App.store.administration.Modules',
                'App.store.administration.ParentFields',
                'App.store.administration.Pharmacies',
                'App.store.administration.PreventiveCare',
                'App.store.administration.PreventiveCareActiveProblems',
                'App.store.administration.PreventiveCareLabs',
                'App.store.administration.PreventiveCareMedications',
                'App.store.administration.Services',
                'App.store.administration.User',
                'App.store.administration.XtypesComboModel',

                'App.store.miscellaneous.OfficeNotes',

				'App.store.account.VoucherLine',
                'App.store.account.Voucher',

				'App.store.fees.Billing',
                'App.store.fees.Checkout',
                'App.store.fees.EncountersPayments',
                'App.store.fees.PaymentTransactions',
                'App.store.navigation.Navigation',

                'App.store.patient.encounter.snippetTree',
				'App.store.patient.encounter.Procedures',

                'App.store.patient.Allergies',
                'App.store.patient.CheckoutAlertArea',
                'App.store.patient.CptCodes',
                'App.store.patient.Dental',
                'App.store.patient.Disclosures',
                'App.store.patient.DoctorsNotes',
                'App.store.patient.EncounterCPTsICDs',
                'App.store.patient.Encounters',
                'App.store.patient.CVXCodes',
                'App.store.patient.ImmunizationCheck',
                'App.store.patient.LaboratoryTypes',
                'App.store.patient.MeaningfulUseAlert',
                'App.store.patient.Medications',
                'App.store.patient.Notes',
                'App.store.patient.Patient',
				'App.store.patient.PatientActiveProblems',
                'App.store.patient.PatientArrivalLog',
                'App.store.patient.PatientCalendarEvents',
                'App.store.patient.PatientDocuments',
                'App.store.patient.DismissedAlerts',
                'App.store.patient.PatientImmunization',
                'App.store.patient.PatientLabsResults',
                'App.store.patient.PatientsLabOrderItems',
                'App.store.patient.PatientSocialHistory',
                'App.store.patient.PatientsOrderObservations',
                'App.store.patient.PatientsOrderResults',
                'App.store.patient.PatientsOrders',
                'App.store.patient.PatientsPrescriptionMedications',
                'App.store.patient.PatientsPrescriptions',
                'App.store.patient.PatientsXrayCtOrders',
                'App.store.patient.PreventiveCare',
                'App.store.patient.QRCptCodes',
                'App.store.patient.Referrals',
                'App.store.patient.Reminders',
                'App.store.patient.Surgery',
                'App.store.patient.VectorGraph',
                'App.store.patient.VisitPayment',
                'App.store.patient.Vitals',
                'App.store.patient.charts.BMIForAge',
                'App.store.patient.charts.HeadCircumferenceInf',
                'App.store.patient.charts.LengthForAgeInf',
                'App.store.patient.charts.StatureForAge',
                'App.store.patient.charts.WeightForAge',
                'App.store.patient.charts.WeightForAgeInf',
                'App.store.patient.charts.WeightForRecumbentInf',
                'App.store.patient.charts.WeightForStature',
                'App.store.areas.PatientAreas',
                'App.store.areas.PoolAreas',
                'App.store.areas.PoolDropAreas',
				/*
				 * Load the activity by the user
				 * This will detect the activity of the user, if the user are idle by a
				 * certain time, it will logout.
				 */
				'App.ux.ActivityMonitor',
				/*
				 * Load the classes that the CORE application needs
				 */
				'App.ux.AbstractPanel',
				'App.ux.LiveCPTSearch',
				'App.ux.LiveImmunizationSearch',
				'App.ux.LiveMedicationSearch',
				'App.ux.LiveLabsSearch',
				'App.ux.LiveCDTSearch',
				'App.ux.LiveRXNORMAllergySearch',
				'App.ux.LiveRXNORMSearch',
				'App.ux.LivePatientSearch',
				'App.ux.LiveRadiologySearch',
				'App.ux.LiveSigsSearch',
				'App.ux.LiveSurgeriesSearch',
				'App.ux.ManagedIframe',
				'App.ux.NodeDisabled',
				'App.ux.PhotoIdWindow',
                'App.ux.PatientEncounterCombo',
				/*
				 * Load the RenderPanel
				 * This is the main panel when all the forms are rendered.
			     */
				'App.ux.RenderPanel',
				/*
				 * Load the charts related controls
				 */
				'Ext.fx.target.Sprite',
				/*
				 * Load the DropDown related components
				 */
				'Ext.dd.DropZone', 'Ext.dd.DragZone',
				/*
				 * Load the Extensible related controls and panels
				 * This is the Calendar Component that GaiaEHR uses.
				 */
				/*
				 * Load the form specific related fields
				 * Not all the fields are the same.
				 */
				'App.ux.form.fields.Help',
				'App.ux.form.fields.Checkbox',
				'App.ux.form.fields.ColorPicker',
				'App.ux.form.fields.Currency',
				'App.ux.form.fields.CustomTrigger',
				'App.ux.form.fields.DateTime',
				'App.ux.form.fields.Percent',
				'App.ux.form.fields.plugin.BadgeText',
				'App.ux.form.AdvanceForm',
				'App.ux.form.Panel',
				'App.ux.grid.EventHistory',
				'App.ux.grid.RowFormEditing',
				'App.ux.grid.RowFormEditor',
				/*
				 * Load the combo boxes spread on all the web application
				 * remember this are all reusable combo boxes.
				 */
				'App.ux.combo.ActiveFacilities',
				'App.ux.combo.ActiveInsurances',
				'App.ux.combo.ActiveProviders',
				'App.ux.combo.Allergies',
				'App.ux.combo.AllergiesAbdominal',
				'App.ux.combo.AllergiesLocation',
				'App.ux.combo.AllergiesSeverity',
				'App.ux.combo.AllergiesTypes',
				'App.ux.combo.Authorizations',
				'App.ux.combo.BillingFacilities',
				'App.ux.combo.CalendarCategories',
				'App.ux.combo.CalendarStatus',
				'App.ux.combo.CodesTypes',
				'App.ux.combo.Combo',
				'App.ux.combo.CVXManufacturers',
				'App.ux.combo.CVXManufacturersForCvx',
				'App.ux.combo.EncounterICDS',
				'App.ux.combo.EncounterPriority',
				'App.ux.combo.Ethnicity',
				'App.ux.combo.Facilities',
				'App.ux.combo.FloorPlanAreas',
				'App.ux.combo.FollowUp',
				'App.ux.combo.InsurancePayerType',
				'App.ux.combo.LabObservations',
				'App.ux.combo.LabsTypes',
				'App.ux.combo.Languages',
				'App.ux.combo.Lists',
				'App.ux.combo.MedicalIssues',
				'App.ux.combo.Medications',
				'App.ux.combo.MsgNoteType',
				'App.ux.combo.MsgStatus',
				'App.ux.combo.Occurrence',
				'App.ux.combo.Outcome',
				'App.ux.combo.Outcome2',
				'App.ux.combo.PayingEntity',
				'App.ux.combo.PaymentCategory',
				'App.ux.combo.PaymentMethod',
				'App.ux.combo.Pharmacies',
				'App.ux.combo.posCodes',
				'App.ux.combo.PrescriptionHowTo',
				'App.ux.combo.PrescriptionOften',
				'App.ux.combo.PrescriptionTypes',
				'App.ux.combo.PrescriptionWhen',
				'App.ux.combo.PreventiveCareTypes',
				'App.ux.combo.ProceduresBodySites',
				'App.ux.combo.Providers',
				'App.ux.combo.Race',
				'App.ux.combo.Roles',
				'App.ux.combo.Sex',
				'App.ux.combo.SmokingStatus',
				'App.ux.combo.Surgery',
				'App.ux.combo.TaxId',
				'App.ux.combo.Templates',
				'App.ux.combo.Themes',
				'App.ux.combo.Time',
				'App.ux.combo.Titles',
				'App.ux.combo.TransmitMethod',
				'App.ux.combo.Types',
				'App.ux.combo.Units',
				'App.ux.combo.Users',
				'App.ux.combo.YesNoNa',
				'App.ux.combo.YesNo',
				'App.ux.window.Window',
				'App.ux.NodeDisabled',
				'App.view.search.PatientSearch',
				/*
				 * Load the patient window related panels
				 */
				'App.view.patient.windows.Medical',
				'App.view.patient.windows.Charts',
				'App.view.patient.windows.PreventiveCare',
				'App.view.patient.windows.Orders',
				'App.view.patient.windows.DocumentViewer',
				'App.view.patient.windows.NewEncounter',
				'App.view.patient.windows.ArrivalLog',
				'App.view.patient.windows.EncounterCheckOut',
				/*
				 * Load the patient related panels
				 */
				'App.view.dashboard.panel.PortalColumn',
				'App.view.dashboard.panel.PortalDropZone',
				'App.view.dashboard.panel.PortalPanel',
				'App.view.dashboard.panel.OnotesPortlet',
				'App.view.dashboard.panel.VisitsPortlet',
				'App.view.dashboard.Dashboard',
				/*
				* Load the root related panels
				*/
				//'App.view.calendar.ExtensibleAll',
				'App.view.calendar.Calendar',
				'App.view.messages.Messages',
				/*
				 * Load the areas related panels
				 */
				'App.view.areas.FloorPlan',
				'App.view.areas.PatientPoolDropZone',
				/**
				 * Load vector charts panel
				 */
				'App.view.patient.charts.BPPulseTemp',
				'App.view.patient.charts.HeadCircumference',
				'App.view.patient.charts.HeightForStature',
				/*
				 * Load the patient related panels
				 */
				'App.view.patient.Patient',

				'App.view.patient.encounter.CurrentProceduralTerminology',
				'App.view.patient.encounter.HealthCareFinancingAdministrationOptions',
				'App.view.patient.encounter.ICDs',
                'App.view.patient.encounter.SOAP',

				'App.view.patient.DoctorsNotes',
				'App.view.patient.ItemsToReview',
				'App.view.patient.EncounterDocumentsGrid',
				'App.view.patient.encounter.ICDs',
				'App.view.patient.CheckoutAlertsView',
				'App.view.patient.Encounter',
				'App.view.patient.Vitals',
				'App.view.patient.NewPatient',
				'App.view.patient.Summary',
				'App.view.patient.ProgressNote',
				'App.view.patient.Results',
				'App.view.patient.SocialHistory',
				'App.view.patient.Visits',
				'App.view.patient.windows.Medical',
				'App.view.patient.VisitCheckout',
				/*
				 * Load the fees related panels
				 */
				'App.view.fees.Billing',
				'App.view.fees.PaymentEntryWindow',
				'App.view.fees.Payments',
				/*
				 * Load the administration related panels
				 */
				'App.view.administration.Applications',
				'App.view.administration.DataManager',
				'App.view.administration.Documents',
				'App.view.administration.Facilities',
				'App.view.administration.Globals',
				'App.view.administration.Layout',
				'App.view.administration.Lists',
				'App.view.administration.Log',
				'App.view.administration.Medications',
				'App.view.administration.Modules',
				'App.view.administration.FloorPlans',
				'App.view.administration.Practice',
				'App.view.administration.PreventiveCare',
				'App.view.administration.Roles',
				'App.view.administration.ExternalDataLoads',
				'App.view.administration.Users',
				/*
				 * Load the miscellaneous related panels
				 */
				'App.view.miscellaneous.AddressBook',
				'App.view.miscellaneous.MyAccount',
				'App.view.miscellaneous.MySettings',
				'App.view.miscellaneous.OfficeNotes',
				'App.view.miscellaneous.Websearch',
				'App.view.signature.SignatureWindow',
				/*
				 * Dynamically load the modules
				 */
				'Modules.Module'
			];
            (function(){
                var scripts = document.getElementsByTagName('script'), localhostTests = [/^localhost$/, /\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(:\d{1,5})?\b/ // IP v4
                ], host = window.location.hostname, isDevelopment = null, queryString = window.location.search, test, path, i, ln, scriptSrc, match;
                for(i = 0, ln = scripts.length; i < ln; i++){
                    scriptSrc = scripts[i].src;
                    match = scriptSrc.match(/bootstrap\.js$/);
                    if(match){
                        path = scriptSrc.substring(0, scriptSrc.length - match[0].length);
                        break;
                    }
                }
                if(queryString.match('(\\?|&)debug') !== null){
                    isDevelopment = true;
                }else if(queryString.match('(\\?|&)nodebug') !== null){
                    isDevelopment = false;
                }
                if(isDevelopment === null){
                    for(i = 0, ln = localhostTests.length; i < ln; i++){
                        test = localhostTests[i];
                        if(host.search(test) !== -1){
                            isDevelopment = true;
                            break;
                        }
                    }
                }
                if(isDevelopment === null && window.location.protocol === 'file:'){
                    isDevelopment = true;
                }
                if(isDevelopment || !isDevelopment){
                    say('Loading GaiaEHR Classes (Development)');
                    //				var jsb3Buffer = '"files": [';
                    document.write('<script type="text/javascript" charset="UTF-8" src="app/view/calendar/ExtensibleAll.js?_v' + version + '"><\/script>');
                    for(var r = 0; r < requires.length; r++){
                        document.write('<script type="text/javascript" charset="UTF-8" src="' + Ext.Loader.getPath(requires[r]) + '?_v' + version + '"><\/script>');
                        //						var arrayBuffer = Ext.Loader.getPath(requires[r]).split('/'),
                        //								fileName = arrayBuffer.pop();
                        //								filePath = arrayBuffer.join('/');
                        //				        jsb3Buffer = jsb3Buffer + '{' +
                        //					        '"path": "'+filePath+'/",' +
                        //						    '"name": "'+fileName+'"' +
                        //				            '},';
                    }
                    //			   jsb3Buffer = jsb3Buffer+' ]';
                }else{
                    say('Loading GaiaEHR Classes (Production)');
                    document.write('<script type="text/javascript" charset="UTF-8" src="app/app-all.js' + '?_v' + version + '"><\/script>');
                }
            })();
            /**
			 * Function to Copy to the clip board.
			 * This function is consumable in all the application.
			 */
            function copyToClipBoard(token){
                app.msg('Sweet!', token + ' copied to clipboard, Ctrl-V or Paste where need it.');
                if(window.clipboardData){
                    window.clipboardData.setData('text', token);
                    return null;
                }else{
                    return (token);
                }
            }
            /**
			 * onWebCamComplete
			 * ???
			 */
            function onWebCamComplete(msg){
                app.onWebCamComplete(msg);
            }
            /**
			 * Function to pop-up a Window and enable the user to print the QR Code.
			 */
            function printQRCode(pid){
                var src = settings.site_url + '/patients/' + app.patient.pid + '/patientDataQrCode.png?';
                app.QRCodePrintWin = window.open(src, 'QRCodePrintWin', 'left=20,top=20,width=800,height=600,toolbar=0,resizable=0,location=1,scrollbars=0,menubar=0,directories=0');
                Ext.defer(function(){
                    app.QRCodePrintWin.print();
                }, 1000);
            }
            /**
			 * Sencha ExtJS OnReady Event
			 * When all the JS code is loaded execute the entire code once.
			 */
            Ext.application({
                name: 'App',
                models:[

                ],
                stores:[

                ],
                views:[

                ],
                controllers:[
	                'administration.HL7',
	                'administration.Practice',
	                'administration.ReferringProviders',
	                'administration.Specialities',

	                'Cron',
	                'DualScreen',
	                'LogOut',
	                'Navigation',
	                'Support',
	                'Notification',
	                'ScriptCam',

	                'patient.ActiveProblems',
	                'patient.Allergies',
	                'patient.DoctorsNotes',
	                'patient.Documents',
	                'patient.FamilyHistory',
	                'patient.Immunizations',
	                'patient.LabOrders',
	                'patient.Medical',
	                'patient.Medications',
	                'patient.RadOrders',
	                'patient.Referrals',
	                'patient.Results',
	                'patient.RxOrders',
	                'patient.SocialHistory',

	                'patient.Summary',
                    'patient.encounter.SOAP'
                ],
                launch: function() {

                    App.Current = this;

                    CronJob.run(function(){
                        say('Loading GaiaEHR');
                        window.app = Ext.create('App.view.Viewport');
                    });
                }
            });
		</script>
	</body>
</html>