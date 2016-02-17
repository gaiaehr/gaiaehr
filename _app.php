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
				lang = {},
				user = {},
				settings = {},
				globals = {},
				ext = '<?php print EXTJS ?>',
				version = '<?php print VERSION ?>',
				site = '<?php print $site ?>',
				requires,
				AppClipboard;
		</script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta content="utf-8" http-equiv="encoding">
		<title>GaiaEHR :: Loading...</title>
		<link rel="stylesheet" type="text/css" href="resources/css/dashboard.css">
		<link rel="stylesheet" type="text/css" href="resources/css/ext-all-gray.css">
		<link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
		<link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">
		<link rel="stylesheet" type="text/css" href="lib/darkroomjs/build/css/darkroom.min.css">
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<!-- Loading Mask -->
		<div id="mainapp-loading-mask" class="x-mask mitos-mask" style="width: 100%; height: 100%"></div>
		<div id="mainapp-loading" class="mitos-mask-msg x-mask-msg x-layer x-mask-msg-default x-border-box">
			<div id="mainapp-x-mask-msg" class="x-mask-msg-inner">
				<div class="x-mask-msg-text">
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
		<script type="text/javascript" src="lib/darkroomjs/vendor/fabric.js"></script>
		<script type="text/javascript" src="lib/darkroomjs/build/js/darkroom.min.js"></script>

        <script type="text/javascript">

            window.i18n = window._ = function(key){
                return window.lang[key] || '*'+key+'*';
            };

            window.say = function(args){
	            console.log(args);
            };

            window.g = function(global){
	            return window.globals[global] === undefined ? false : window.globals[global];
            };

            window.a = function(acl){
	            return eval(window.acl[acl]) || false;
            };

            window.onbeforeunload = function(){
	            return _('you_should_logout_before_quitting');
            };

            ZeroClipboard.config( { moviePath: 'lib/ZeroClipboard/ZeroClipboard.swf' } );
            AppClipboard = new ZeroClipboard();
            AppClipboard.on("complete", function (client, args) {
	            app.msg(i18n('sweet'), args.text + ' - ' + i18n('copied_to_clipboard'));
            });


            (function(){

	            /**
	             * Ext Localization file
	             * Using a anonymous function, in javascript.
	             * Is not intended to be used globally just this once.
	             */
                document.write('<script type="text/javascript" src="lib/<?php print EXTJS ?>/locale/' +
					i18n('i18nExtFile') +
					'?_v' +
					version +
					'"><\/script>'
				);

	            var s;
	            /**
	             * Modules Styles
	             */
	            if(window.styles){
		            for(s = 0; s < window.styles.length; s++){
			            document.write('<link rel="stylesheet" type="text/style" href="'+ window.styles[s] + '?_v' + version + '"><\/link>');
		            }
	            }

	            /**
	             * Modules Scripts
	             */
	            if(window.scripts){
		            for(s = 0; s < window.scripts.length; s++){
			            document.write('<script type="text/javascript" src="' + window.scripts[s] + '?_v' + version + '"><\/script>');
		            }
	            }
            })();

            Ext.Loader.setConfig({
                enabled: true,
                disableCaching: true,
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

				if(e.xhr && e.xhr.aborted) return;

				app.alert(
					'<p><span style="font-weight:bold">'+
					(e.where != 'undefined' ? e.message : e.message.replace(/\n/g,''))  +
					'</span></p><hr>' +
					'<p>'+
					(typeof e.where != 'undefined' ? e.where.replace(/\n/g,'<br>') : e.data != null ?  e.data : '') +
					'</p>',
					'error'
				);
			});
		</script>

		<script type="text/javascript" src="app/ux/Overrides.js"></script>
		<script type="text/javascript" src="app/ux/VTypes.js"></script>

		<!-- this is the compiled/minified version -->

		<?php if(HOST != 'localhost') { ?>
			<script type="text/javascript" src="app/app.min.js?_v<?php print VERSION ?>"></script>
		<?php } ?>

		<script type="text/javascript">
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

	            requires:[
		            'Ext.ux.LiveSearchGridPanel',
		            'Ext.ux.SlidingPager',
		            'Ext.ux.PreviewPlugin',
		            'Ext.ux.form.SearchField',

		            'App.ux.grid.LiveSearchGridPanel',
		            'App.ux.RatingField',
		            'App.ux.grid.GridToHtml',
		            'App.ux.grid.Printer',
		            /**
		             * Load the activity by the user
		             * This will detect the activity of the user, if the user are idle by a
		             * certain time, it will logout.
		             */
		            'App.ux.ActivityMonitor',
		            /**
		             * Load the classes that the CORE application needs
		             */
		            'App.ux.AbstractPanel',
		            'App.ux.LiveCPTSearch',
		            'App.ux.LiveICDXSearch',
		            'App.ux.LiveImmunizationSearch',
		            'App.ux.LiveLabsSearch',
		            'App.ux.LiveCDTSearch',
		            'App.ux.LiveRXNORMAllergySearch',
		            'App.ux.LiveRXNORMSearch',
		            'App.ux.LivePatientSearch',
                    'App.ux.LiveRadsSearch',
		            'App.ux.LiveSigsSearch',
		            'App.ux.LiveUserSearch',
		            'App.ux.ManagedIframe',
		            'App.ux.NodeDisabled',
		            'App.ux.PhotoIdWindow',
		            'App.ux.PatientEncounterCombo',
		            /**
		             * Load the RenderPanel
		             * This is the main panel when all the forms are rendered.
		             */
		            'App.ux.RenderPanel',
		            /**
		             * Load the charts related controls
		             */
		            'Ext.fx.target.Sprite',
		            /**
		             * Load the DropDown related components
		             */
		            'Ext.dd.DropZone', 'Ext.dd.DragZone',
		            /**
		             * Load the form specific related fields
		             * Not all the fields are the same.
		             */
		            'App.ux.form.fields.Help',
		            'App.ux.form.fields.Checkbox',
		            'App.ux.form.fields.ColorPicker',
		            'App.ux.form.fields.Currency',
		            'App.ux.form.fields.CustomTrigger',
		            'App.ux.form.fields.DateTime',
		            'App.ux.form.fields.MultiText',
		            'App.ux.form.fields.Percent',
		            'App.ux.form.fields.plugin.BadgeText',
		            'App.ux.form.fields.plugin.ReadOnlyLabel',
		            'App.ux.form.AdvanceForm',
		            'App.ux.form.Panel',
		            'App.ux.grid.DeleteColumn',
		            'App.ux.grid.EventHistory',
		            'App.ux.grid.RowFormEditing',
		            'App.ux.grid.RowFormEditor',
                    'App.ux.window.voidComment',
		            /**
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
                    'App.ux.combo.ComboOptionList',
		            'App.ux.combo.CVXManufacturers',
		            'App.ux.combo.CVXManufacturersForCvx',
		            'App.ux.combo.EncounterICDS',
		            'App.ux.combo.EncounterPriority',
		            'App.ux.combo.Ethnicity',
		            'App.ux.combo.Facilities',
		            'App.ux.combo.FloorPlanAreas',
		            'App.ux.combo.FloorPlanZones',
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
		            /**
		             * Dynamically load the modules
		             */
		            'Modules.Module'
	            ],
				models:[
					'miscellaneous.AddressBook',

					'patient.CarePlanGoal',
					'patient.CognitiveAndFunctionalStatus',
					'patient.SmokeStatus',
					'patient.PatientPossibleDuplicate',

                    /**
                     * Load the models, the model are the representative of the database
                     * table structure with modifications behind the PHP counterpart.
                     * All table should be declared here, and Sencha's ExtJS models.
                     * This are spread in all the core application.
                     */
					'administration.ActiveProblems',
					'administration.Applications',
					'administration.DefaultDocuments',
					'administration.DocumentsTemplates',
					'administration.DocumentToken',
					'administration.ExternalDataLoads',
					'administration.Facility',
					'administration.FacilityStructure',
					'administration.FloorPlans',
					'administration.FloorPlanZones',
					'administration.FormListOptions',
					'administration.FormsList',
					'administration.Globals',
					'administration.HeadersAndFooters',
					'administration.ImmunizationRelations',
					'administration.InsuranceCompany',
					'administration.LabObservations',
					'administration.Laboratories',
					'administration.LayoutTree',
					'administration.ListOptions',
					'administration.Lists',
					'administration.AuditLog',
					'administration.Medications',
					'administration.Modules',
					'administration.ParentFields',
					'administration.Pharmacies',
					'administration.PreventiveCare',
					'administration.PreventiveCareActiveProblems',
					'administration.PreventiveCareLabs',
					'administration.PreventiveCareMedications',
					'administration.ProviderCredentialization',
					'administration.ReferringProviderFacility',
					'administration.ReferringProvider',
					'administration.Services',
					'administration.Specialty',
					'administration.TemplatePanel',
					'administration.TemplatePanelTemplate',
					'administration.TransactionLog',
					'administration.User',
					'administration.XtypesComboModel',

					'miscellaneous.OfficeNotes',
					'miscellaneous.Amendment',

					'account.VoucherLine',
					'account.Voucher',

					'fees.Billing',
					'fees.Checkout',
					'fees.EncountersPayments',
					'fees.PaymentTransactions',
					'navigation.Navigation',

					'patient.Vitals',
					'patient.ReviewOfSystems',
					'patient.FamilyHistory',
					'patient.SOAP',
					'patient.HCFAOptions',
					'patient.EncounterService',

					'patient.encounter.snippetTree',
					'patient.encounter.Procedures',

					'patient.AppointmentRequest',
					'patient.AdvanceDirective',
					'patient.Allergies',
					'patient.CheckoutAlertArea',
					'patient.CptCodes',
					'patient.Dental',
					'patient.Disclosures',
					'patient.DismissedAlerts',
					'patient.DoctorsNote',
					'patient.Encounter',
					'patient.EventHistory',
					'patient.CVXCodes',
					'patient.ImmunizationCheck',
					'patient.LaboratoryTypes',
					'patient.Insurance',
					'patient.MeaningfulUseAlert',
					'patient.Medications',
					'patient.Notes',
					'patient.Patient',
					'patient.PatientContacts',
					'patient.PatientActiveProblem',
					'patient.PatientArrivalLog',
					'patient.PatientCalendarEvents',
					'patient.PatientDocuments',
					'patient.PatientImmunization',
					'patient.PatientLabsResults',
					'patient.PatientsLabOrderItems',
					'patient.PatientSocialHistory',
					'patient.PatientsOrderObservation',
					'patient.PatientsOrderResult',
					'patient.PatientsOrders',
					'patient.PatientsPrescriptionMedications',
					'patient.PatientsPrescriptions',
					'patient.PatientsXrayCtOrders',
					'patient.PreventiveCare',
					'patient.QRCptCodes',
					'patient.Referral',
					'patient.Reminders',
					'patient.Surgery',
					'patient.VectorGraph',
					'patient.VisitPayment',
					'patient.charts.BMIForAge',
					'patient.charts.HeadCircumferenceInf',
					'patient.charts.LengthForAgeInf',
					'patient.charts.StatureForAge',
					'patient.charts.WeightForAge',
					'patient.charts.WeightForAgeInf',
					'patient.charts.WeightForRecumbentInf',
					'patient.charts.WeightForStature',
					'areas.PatientArea',
					'areas.PoolArea',
					'areas.PoolDropAreas'
				],
                stores:[
	                'miscellaneous.AddressBook',

					'patient.CarePlanGoals',
					'patient.CognitiveAndFunctionalStatus',
	                'patient.SmokeStatus',
	                'patient.PatientPossibleDuplicates',

                /**
                 * Load all the stores used by GaiaEHR
                 * this includes ComboBoxes, and other stores used by the web application
                 * most of this stores are consumed by the dataStore directory.
                 */
	                'administration.ActiveProblems',
	                'administration.Applications',
	                'administration.DefaultDocuments',
	                'administration.DocumentsTemplates',
	                'administration.DocumentToken',
	                'administration.ExternalDataLoads',
	                'administration.Facility',
	                'administration.FacilityStructures',
	                'administration.FloorPlans',
	                'administration.FloorPlanZones',
	                'administration.FormListOptions',
	                'administration.FormsList',
	                'administration.Globals',
	                'administration.HeadersAndFooters',
	                'administration.ImmunizationRelations',
	                'administration.InsuranceCompanies',
	                'administration.LabObservations',
	                'administration.Laboratories',
	                'administration.LayoutTree',
	                'administration.ListOptions',
	                'administration.Lists',
	                'administration.AuditLog',
	                'administration.Medications',
	                'administration.Modules',
	                'administration.ParentFields',
	                'administration.Pharmacies',
	                'administration.PreventiveCare',
	                'administration.PreventiveCareActiveProblems',
	                'administration.PreventiveCareLabs',
	                'administration.PreventiveCareMedications',
	                'administration.ProviderCredentializations',
	                'administration.Services',
	                'administration.TransactionLogs',
	                'administration.User',
	                'administration.XtypesComboModel',

	                'miscellaneous.OfficeNotes',
	                'miscellaneous.Amendments',

	                'account.VoucherLine',
	                'account.Voucher',

	                'fees.Billing',
	                'fees.Checkout',
	                'fees.EncountersPayments',
	                'fees.PaymentTransactions',
	                'navigation.Navigation',

	                'patient.encounter.snippetTree',
	                'patient.encounter.Procedures',

	                'patient.AdvanceDirectives',
	                'patient.Allergies',
	                'patient.CheckoutAlertArea',
	                'patient.CptCodes',
	                'patient.Dental',
	                'patient.Disclosures',
	                'patient.DoctorsNotes',
	                'patient.EncounterServices',
	                'patient.Encounters',
	                'patient.CVXCodes',
	                'patient.ImmunizationCheck',
	                'patient.LaboratoryTypes',
	                'patient.MeaningfulUseAlert',
	                'patient.Medications',
                    'patient.RxOrders',
	                'patient.Notes',
	                'patient.Patient',
                    'patient.PatientContacts',
	                'patient.PatientActiveProblems',
	                'patient.PatientArrivalLog',
	                'patient.PatientCalendarEvents',
	                'patient.PatientDocuments',
	                'patient.DismissedAlerts',
	                'patient.PatientImmunization',
	                'patient.PatientLabsResults',
	                'patient.PatientsLabOrderItems',
	                'patient.PatientSocialHistory',
	                'patient.PatientsOrderObservations',
	                'patient.PatientsOrderResults',
	                'patient.PatientsOrders',
	                'patient.PatientsPrescriptionMedications',
	                'patient.PatientsPrescriptions',
	                'patient.PatientsXrayCtOrders',
	                'patient.PreventiveCare',
	                'patient.QRCptCodes',
	                'patient.Referrals',
	                'patient.Reminders',
	                'patient.Surgery',
	                'patient.VectorGraph',
	                'patient.VisitPayment',
	                'patient.Vitals',
	                'patient.charts.BMIForAge',
	                'patient.charts.HeadCircumferenceInf',
	                'patient.charts.LengthForAgeInf',
	                'patient.charts.StatureForAge',
	                'patient.charts.WeightForAge',
	                'patient.charts.WeightForAgeInf',
	                'patient.charts.WeightForRecumbentInf',
	                'patient.charts.WeightForStature',
	                'areas.PatientAreas',
	                'areas.PoolAreas',
	                'areas.PoolDropAreas'
                ],
                views:[
	                /**
	                 * Load the patient window related panels
	                 */
	                'patient.windows.Medical',
	                'patient.windows.Charts',
	                'patient.windows.PreventiveCare',
	                'patient.windows.DocumentViewer',
	                'patient.windows.NewEncounter',
	                'patient.windows.ArrivalLog',
	                'patient.windows.EncounterCheckOut',
	                'patient.windows.DocumentErrorNote',
	                /**
	                 * Load the patient related panels
	                 */
	                'dashboard.panel.PortalColumn',
	                'dashboard.panel.PortalDropZone',
	                'dashboard.panel.PortalPanel',

	                'dashboard.Dashboard',
	                'dashboard.panel.NewResults',
	                'dashboard.panel.DailyVisits',

	                /**
	                 * Load the root related panels
	                 */
	                'messages.Messages',
	                /**
	                 * Load the areas related panels
	                 */
	                'areas.FloorPlan',
	                'areas.PatientPoolAreas',
	                /**
	                 * Load vector charts panel
	                 */
	                'patient.charts.BPPulseTemp',
	                'patient.charts.HeadCircumference',
	                'patient.charts.HeightForStature',
	                /*
	                 * Load the patient related panels
	                 */
	                'patient.Patient',

	                'patient.encounter.AppointmentRequestGrid',
	                'patient.encounter.CurrentProceduralTerminology',
	                'patient.encounter.HealthCareFinancingAdministrationOptions',
	                'patient.encounter.ICDs',
	                'patient.encounter.SOAP',

	                'patient.windows.PossibleDuplicates',

	                'patient.DoctorsNotes',
	                'patient.ItemsToReview',
	                'patient.EncounterDocumentsGrid',
	                'patient.encounter.ICDs',
	                'patient.CheckoutAlertsView',
	                'patient.Encounter',
	                'patient.Vitals',
	                'patient.NewPatient',
	                'patient.Summary',
	                'patient.ProgressNote',
	                'patient.RemindersAlert',
	                'patient.Reminders',
	                'patient.Results',
	                'patient.SocialHistory',
	                'patient.Visits',
	                'patient.windows.Medical',
	                'patient.VisitCheckout',
	                /**
	                 * Load the fees related panels
	                 */
	                'fees.Billing',
	                'fees.PaymentEntryWindow',
	                'fees.Payments',
	                /**
	                 * Load the administration related panels
	                 */

	                'administration.practice.Facilities',
	                'administration.practice.FacilityConfig',
	                'administration.practice.Insurance',
	                'administration.practice.Laboratories',
	                'administration.practice.Pharmacies',
	                'administration.practice.Practice',
	                'administration.practice.ProviderNumbers',
	                'administration.practice.ReferringProviders',
	                'administration.practice.Specialties',

	                'administration.Applications',
	                'administration.DataManager',
	                'administration.Documents',
	                'administration.Globals',
	                'administration.Layout',
	                'administration.Lists',
	                'administration.AuditLog',
	                'administration.Medications',
	                'administration.Modules',
	                'administration.FloorPlans',
	                'administration.PreventiveCare',
	                'administration.Roles',
	                'administration.ExternalDataLoads',
	                'administration.Users',
	                /**
	                 * Load the miscellaneous related panels
	                 */
	                'miscellaneous.AddressBook',
	                'miscellaneous.Amendments',
	                'miscellaneous.MyAccount',
	                'miscellaneous.MySettings',
	                'miscellaneous.OfficeNotes',
	                'miscellaneous.Websearch',
	                'signature.SignatureWindow'
                ],

                controllers:[
	                'administration.AuditLog',
	                'administration.CPT',
	                'administration.DataPortability',
	                'administration.DecisionSupport',
	                'administration.FacilityStructure',
	                'administration.HL7',
	                'administration.Practice',
	                'administration.ReferringProviders',
	                'administration.Roles',
	                'administration.Specialties',
	                'administration.TemplatePanels',
	                'administration.Users',

	                'areas.FloorPlan',

	                'dashboard.Dashboard',
	                'dashboard.panel.NewResults',
	                'dashboard.panel.DailyVisits',

	                'miscellaneous.Amendments',

	                'AlwaysOnTop',
	                'Cron',
	                'DocumentViewer',
	                'DualScreen',
	                'Header',
	                'InfoButton',
	                'KeyCommands',
	                'LogOut',
	                'Navigation',
	                'Notification',
	                'Scanner',
	                'ScriptCam',
	                'Support',

	                'patient.ActiveProblems',
	                'patient.AdvanceDirectives',
	                'patient.Allergies',
	                'patient.AppointmentRequests',
	                'patient.CarePlanGoals',
	                'patient.CCD',
	                'patient.CCDImport',
	                'patient.CognitiveAndFunctionalStatus',
	                'patient.DecisionSupport',
	                'patient.DoctorsNotes',
	                'patient.Documents',
	                'patient.FamilyHistory',
	                'patient.HL7',
	                'patient.Immunizations',
	                'patient.Insurance',
	                'patient.ItemsToReview',
	                'patient.LabOrders',
	                'patient.Medical',
	                'patient.Medications',
	                'patient.Patient',
	                'patient.ProgressNotesHistory',
	                'patient.RadOrders',
	                'patient.Referrals',
	                'patient.Reminders',
	                'patient.Results',
	                'patient.RxOrders',
	                'patient.Social',
	                'patient.Vitals',

	                'patient.Summary',
                    'patient.encounter.Encounter',
	                'patient.encounter.EncounterDocuments',
	                'patient.encounter.EncounterSign',
	                'patient.encounter.Snippets',
                    'patient.encounter.SOAP',
                    'patient.encounter.SuperBill'
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
