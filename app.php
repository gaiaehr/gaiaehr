<?php
/* Main Screen Application
 *
 * Description: This is the main application, with all the panels
 * also this is the viewport of the application, this will call
 * all the app->screen panels
 *
 * version 0.0.3
 * revision: N/A
 * author: GI Technologies, 2011
 * modified: Ernesto J Rodriguez (Certun)
 *
 * @namespace App.data.REMOTING_API
 */
if(!defined('_GaiaEXEC')) die('No direct access allowed.');
/**
 * Reset session flop count
 */
$_SESSION['site']['flops'] = 0;
/*
 * Include Globals and run setGlobals static method to set the global settings
 */
include_once($_SESSION['site']['root'].'/dataProvider/Globals.php');
Globals::setGlobals();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $_SESSION['global_settings']['GaiaEHR_name'] ?></title>
        <!--test stuff-->
        <link rel="stylesheet" type="text/css" href="ui_app/dashboard.css" >
        <!--end test stuff-->
        <link rel="stylesheet" type="text/css" href="lib/<?php echo $_SESSION['dir']['ext']; ?>/resources/css/<?php echo $_SESSION['global_settings']['css_header'] ?>">
        <!--calendar css-->
        <link rel="stylesheet" type="text/css" href="lib/<?php echo $_SESSION['dir']['ext_cal'] ?>/resources/css/extensible-all.css" />
        <!--ens calendar css-->
        <link rel="stylesheet" type="text/css" href="ui_app/style_newui.css" >
        <link rel="stylesheet" type="text/css" href="ui_app/custom_app.css" >
        <link rel="shortcut icon" href="favicon.ico" >
        <!--<script type="text/javascript" src="app/view/App.js"></script>-->
    </head>
    <body>

        <!-- Loading Mask -->
        <div id="mainapp-loading-mask" class="x-mask mitos-mask"></div>
        <div id="mainapp-x-mask-msg">
            <div id="mainapp-loading" class="x-mask-msg mitos-mask-msg">
                <div>Loading GaiaEHR...</div>
            </div>
        </div>
        <!-- slide down message div -->
        <span id="app-msg" style="display:none;"></span>

        <!-- Ext library -->
        <script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['ext']; ?>/ext-all-debug.js"></script>
        <script src="data/api.php"></script>
        <script type="text/javascript">
            Ext.Loader.setConfig({
                enabled			: true,
                disableCaching	: false,
                paths			: {
                    //'Ext'         : 'lib/extjs-4.1.0-rc1/src',
                    'Ext.ux'      : 'app/classes/ux',
                    'App'         : 'app',
                    'Extensible'  : 'lib/extensible-1.5.1/src'
                }
            });
            Ext.direct.Manager.addProvider(App.data.REMOTING_API);
        </script>

        <!-- swfobject is webcam library -->
        <script type="text/javascript" src="lib/webcam_control/swfobject.js"></script>
        <!-- Extensible calendar library -->
        <script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['ext_cal'] ?>/src/Extensible.js"></script>
        <!-- Languages -->
        <script type="text/javascript" src="langs/<?php echo $_SESSION['lang']['code'] ?>.js"></script>
        <!-- JS Registry -->
        <script type="text/javascript" src="registry.js.php"></script>
        <!-- form validation vtypes -->
        <script type="text/javascript" src="repo/formValidation/formValidation.js"></script>
        <!-- webcam -->
        <script type="text/javascript" src="lib/jpegcam/htdocs/webcam.js"></script>


        <script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['ext']; ?>/examples/ux/LiveSearchGridPanel.js"></script>
        <script type="text/javascript" src="lib/<?php echo $_SESSION['dir']['ext']; ?>/src/grid/plugin/RowEditing.js"></script>

        <!-- Override classes -->
        <script type="text/javascript" src="app/classes/Overrides.js"></script>

        <!-- Models -->
        <script type="text/javascript" src="lib/extensible-1.5.1/src/calendar/data/EventStore.js"></script>
        <script type="text/javascript" src="app/model/navigation/Navigation.js"></script>
        <script type="text/javascript" src="app/model/poolarea/PoolArea.js"></script>
        <script type="text/javascript" src="app/model/poolarea/PoolDropAreas.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Vitals.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Encounters.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Encounter.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Immunization.js"></script>
        <script type="text/javascript" src="app/model/patientfile/ImmunizationCheck.js"></script>
        <script type="text/javascript" src="app/model/patientfile/PatientImmunization.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Allergies.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Dental.js"></script>
        <script type="text/javascript" src="app/model/patientfile/MedicalIssues.js"></script>
<!--        <script type="text/javascript" src="app/model/patientfile/PreventiveCare.js"></script>-->
        <script type="text/javascript" src="app/model/patientfile/Medications.js"></script>
        <script type="text/javascript" src="app/model/patientfile/Surgery.js"></script>
        <script type="text/javascript" src="app/model/patientfile/EventHistory.js"></script>
        <script type="text/javascript" src="app/model/patientfile/QRCptCodes.js"></script>
<!--    <script type="text/javascript" src="app/model/patientfile/VisitPayment.js"></script>-->
        <script type="text/javascript" src="app/model/patientfile/CptCodes.js"></script>
        <script type="text/javascript" src="app/model/patientfile/PatientsPrescription.js"></script>
        <script type="text/javascript" src="app/model/patientfile/PatientsLabsOrders.js"></script>
        <script type="text/javascript" src="app/model/patientfile/MeaningfulUseAlert.js"></script>
        <script type="text/javascript" src="app/model/fees/Billing.js"></script>
        <script type="text/javascript" src="app/model/fees/EncountersPayments.js"></script>
        <script type="text/javascript" src="app/model/administration/Services.js"></script>
        <script type="text/javascript" src="app/model/administration/PreventiveCare.js"></script>
        <script type="text/javascript" src="app/model/administration/ActiveProblems.js"></script>
        <script type="text/javascript" src="app/model/administration/Medications.js"></script>
        <script type="text/javascript" src="app/model/administration/HeadersAndFooters.js"></script>
        <script type="text/javascript" src="app/model/administration/DefaultDocuments.js"></script>
        <script type="text/javascript" src="app/model/administration/ImmunizationRelations.js"></script>



        <!-- Stores -->
        <script type="text/javascript" src="app/store/navigation/Navigation.js"></script>
        <script type="text/javascript" src="app/store/poolarea/PoolArea.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Vitals.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Encounters.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Encounter.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Immunization.js"></script>
        <script type="text/javascript" src="app/store/patientfile/ImmunizationCheck.js"></script>
        <script type="text/javascript" src="app/store/patientfile/PatientImmunization.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Allergies.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Dental.js"></script>
        <script type="text/javascript" src="app/store/patientfile/MedicalIssues.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Medications.js"></script>
        <script type="text/javascript" src="app/store/patientfile/Surgery.js"></script>
<!--        <script type="text/javascript" src="app/store/patientfile/PreventiveCare.js"></script>-->
        <script type="text/javascript" src="app/store/patientfile/EncounterEventHistory.js"></script>
        <script type="text/javascript" src="app/store/patientfile/QRCptCodes.js"></script>
        <script type="text/javascript" src="app/store/patientfile/PatientsPrescription.js"></script>
        <script type="text/javascript" src="app/store/patientfile/PatientsLabsOrders.js"></script>
        <script type="text/javascript" src="app/store/patientfile/MeaningfulUseAlert.js"></script>
        <script type="text/javascript" src="app/store/fees/Billing.js"></script>
        <script type="text/javascript" src="app/store/fees/EncountersPayments.js"></script>
        <script type="text/javascript" src="app/store/administration/Services.js"></script>
        <script type="text/javascript" src="app/store/administration/PreventiveCare.js"></script>
        <script type="text/javascript" src="app/store/administration/ActiveProblems.js"></script>
        <script type="text/javascript" src="app/store/administration/Medications.js"></script>
        <script type="text/javascript" src="app/store/administration/HeadersAndFooters.js"></script>
        <script type="text/javascript" src="app/store/administration/DefaultDocuments.js"></script>
        <script type="text/javascript" src="app/store/administration/ImmunizationRelations.js"></script>


        <!-- Classes -->
        <script type="text/javascript" src="app/classes/NodeDisabled.js"></script>
        <script type="text/javascript" src="app/classes/RenderPanel.js"></script>
        <script type="text/javascript" src="app/classes/ux/SlidingPager.js"></script>
        <script type="text/javascript" src="app/classes/GridPanel.js"></script>
        <script type="text/javascript" src="app/classes/LiveICDXSearch.js"></script>
        <script type="text/javascript" src="app/classes/LiveCPTSearch.js"></script>
        <script type="text/javascript" src="app/classes/LivePatientSearch.js"></script>
        <script type="text/javascript" src="app/classes/LiveImmunizationSearch.js"></script>
        <script type="text/javascript" src="app/classes/LiveMedicationSearch.js"></script>
        <script type="text/javascript" src="app/classes/ManagedIframe.js"></script>
        <script type="text/javascript" src="app/classes/PhotoIdWindow.js"></script>
        <script type="text/javascript" src="app/classes/CalCategoryComboBox.js"></script>
        <script type="text/javascript" src="app/classes/CalStatusComboBox.js"></script>
        <script type="text/javascript" src="app/classes/combo/Authorizations.js"></script>
        <script type="text/javascript" src="app/classes/combo/CodesTypes.js"></script>
        <script type="text/javascript" src="app/classes/combo/PreventiveCareTypes.js"></script>
        <script type="text/javascript" src="app/classes/combo/Facilities.js"></script>
        <script type="text/javascript" src="app/classes/combo/BillingFacilities.js"></script>
        <script type="text/javascript" src="app/classes/combo/InsurancePayerType.js"></script>
        <script type="text/javascript" src="app/classes/combo/Languages.js"></script>
        <script type="text/javascript" src="app/classes/combo/Lists.js"></script>
        <script type="text/javascript" src="app/classes/combo/MsgNoteType.js"></script>
        <script type="text/javascript" src="app/classes/combo/MsgStatus.js"></script>
        <script type="text/javascript" src="app/classes/combo/posCodes.js"></script>
        <script type="text/javascript" src="app/classes/combo/Roles.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesTypes.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesLocation.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesSystemic.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesSkin.js"></script>
        <script type="text/javascript" src="app/classes/combo/Allergies.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesSeverity.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesLocal.js"></script>
        <script type="text/javascript" src="app/classes/combo/AllergiesAbdominal.js"></script>
        <script type="text/javascript" src="app/classes/combo/Sex.js"></script>
        <script type="text/javascript" src="app/classes/combo/MedicalIssues.js"></script>
        <script type="text/javascript" src="app/classes/combo/TaxId.js"></script>
        <script type="text/javascript" src="app/classes/combo/Titles.js"></script>
        <script type="text/javascript" src="app/classes/combo/Outcome.js"></script>
        <script type="text/javascript" src="app/classes/combo/Outcome2.js"></script>
        <script type="text/javascript" src="app/classes/combo/PaymentMethod.js"></script>
        <script type="text/javascript" src="app/classes/combo/PaymentCategory.js"></script>
        <script type="text/javascript" src="app/classes/combo/PayingEntity.js"></script>
        <script type="text/javascript" src="app/classes/combo/Pharmacies.js"></script>
        <script type="text/javascript" src="app/classes/combo/Medications.js"></script>
        <script type="text/javascript" src="app/classes/combo/Occurrence.js"></script>
        <script type="text/javascript" src="app/classes/combo/Surgery.js"></script>
        <script type="text/javascript" src="app/classes/combo/TransmitMethod.js"></script>
        <script type="text/javascript" src="app/classes/combo/Types.js"></script>
        <script type="text/javascript" src="app/classes/combo/Users.js"></script>
        <script type="text/javascript" src="app/classes/combo/Units.js"></script>
        <script type="text/javascript" src="app/classes/combo/Providers.js"></script>
        <script type="text/javascript" src="app/classes/combo/Time.js"></script>
        <script type="text/javascript" src="app/classes/combo/PrescrptionOften.js"></script>
        <script type="text/javascript" src="app/classes/combo/PrescrptionWhen.js"></script>
        <script type="text/javascript" src="app/classes/combo/PrescrptionTypes.js"></script>
        <script type="text/javascript" src="app/classes/combo/PrescrptionHowTo.js"></script>
        <script type="text/javascript" src="app/classes/combo/LabsTypes.js"></script>
        <script type="text/javascript" src="app/classes/combo/LabObservations.js"></script>
        <script type="text/javascript" src="app/classes/combo/Templates.js"></script>
        <script type="text/javascript" src="app/classes/form/fields/Checkbox.js"></script>
        <script type="text/javascript" src="app/classes/form/fields/DateTime.js"></script>
        <script type="text/javascript" src="app/classes/form/fields/Currency.js"></script>
        <script type="text/javascript" src="app/classes/grid/RowFormEditor.js"></script>
        <script type="text/javascript" src="app/classes/grid/RowFormEditing.js"></script>
        <script type="text/javascript" src="app/classes/grid/EventHistory.js"></script>

        <!-- Views-->
        <script type="text/javascript" src="app/view/PatientPoolDropZone.js"></script>
        <script type="text/javascript" src="app/view/dashboard/Dashboard.js"></script>
        <script type="text/javascript" src="app/view/patientfile/ProgressNote.js"></script>
        <script type="text/javascript" src="app/view/patientfile/ChartsWindow.js"></script>
        <script type="text/javascript" src="app/view/patientfile/Vitals.js"></script>
        <script type="text/javascript" src="app/view/patientfile/LaboratoryResults.js"></script>
        <script type="text/javascript" src="app/view/patientfile/Visits.js"></script>
        <script type="text/javascript" src="app/view/patientfile/Summary.js"></script>
        <script type="text/javascript" src="app/view/patientfile/encounter/CurrentProceduralTerminology.js"></script>
        <script type="text/javascript" src="app/view/patientfile/encounter/ICDs.js"></script>
        <script type="text/javascript" src="app/view/patientfile/Encounter.js"></script>
        <script type="text/javascript" src="app/view/patientfile/PreventiveCareWindow.js"></script>
        <script type="text/javascript" src="app/view/patientfile/PatientCheckout.js"></script>
        <script type="text/javascript" src="app/view/patientfile/NewPatient.js"></script>
        <script type="text/javascript" src="app/view/patientfile/MedicalWindow.js"></script>
        <script type="text/javascript" src="app/view/patientfile/PreventiveCareWindow.js"></script>
        <script type="text/javascript" src="app/view/patientfile/NewDocumentsWindow.js"></script>
        <script type="text/javascript" src="app/view/patientfile/DocumentViewerWindow.js"></script>
        <script type="text/javascript" src="app/view/fees/PaymentEntryWindow.js"></script>
        <script type="text/javascript" src="app/view/administration/Facilities.js"></script>
        <script type="text/javascript" src="app/view/administration/Globals.js"></script>
        <script type="text/javascript" src="app/view/administration/Layout.js"></script>
        <script type="text/javascript" src="app/view/administration/Lists.js"></script>
        <script type="text/javascript" src="app/view/administration/Log.js"></script>
        <script type="text/javascript" src="app/view/administration/Practice.js"></script>
        <script type="text/javascript" src="app/view/administration/Roles.js"></script>
        <script type="text/javascript" src="app/view/administration/DataManager.js"></script>
        <script type="text/javascript" src="app/view/administration/PreventiveCare.js"></script>
        <script type="text/javascript" src="app/view/administration/Medications.js"></script>
        <script type="text/javascript" src="app/view/administration/Users.js"></script>
        <script type="text/javascript" src="app/view/administration/Documents.js"></script>
        <script type="text/javascript" src="app/view/messages/Messages.js"></script>
        <script type="text/javascript" src="app/view/search/PatientSearch.js"></script>
        <script type="text/javascript" src="app/view/miscellaneous/Addressbook.js"></script>
        <script type="text/javascript" src="app/view/miscellaneous/MyAccount.js"></script>
        <script type="text/javascript" src="app/view/miscellaneous/MySettings.js"></script>
        <script type="text/javascript" src="app/view/miscellaneous/OfficeNotes.js"></script>
        <script type="text/javascript" src="app/view/miscellaneous/Websearch.js"></script>

        <!-- Application Viewport -->
        <script type="text/javascript" src="app/view/Viewport.js"></script>
        <script type="text/javascript">
            var app;
            function say(a){
                console.log(a);
            }
            Ext.onReady(function(){
                app = Ext.create('App.view.Viewport');
            });

            function copyToClipBoard(token) {
	            app.msg('Sweet!', token + ' copied to clipboard, Ctrl-V or Paste where need it.');
                if(window.clipboardData){
                    window.clipboardData.setData('text', token);
                }else{
	                return (token);
                }
            }
	        function onWebCamComplete(msg){
		        app.onWebCamComplete(msg);
	        }
            function printQRCode(pid){
	            var src = settings.site_url + '/patients/' + app.currPatient.pid + '/patientDataQrCode.png?';
	            app.QRCodePrintWin = window.open(src,'QRCodePrintWin','left=20,top=20,width=150,height=150,toolbar=0,resizable=0,location=1,scrollbars=0,menubar=0,directories=0');
				Ext.defer(function(){
					app.QRCodePrintWin.print();
	            }, 1000);
  	        }
        </script>
    </body>
</html>