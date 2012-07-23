<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Navigation.php
 * Date: 2/1/12
 * Time: 7:04 PM
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'].'/dataProvider/ACL.php');

class Navigation {
    /**
     * @var \ACL
     */
    private $ACL;
    private $lang;
    private $t;

    function __construct(){
        $this->ACL = new ACL();
        $this->lang = $_SESSION['lang']['code'];
        $this->t = $this->getTexts();
    }

    public function getNavigation(){
        // *************************************************************************************
        // Renders the items of the navigation panel
        // Default Nav Data
        // *************************************************************************************
        $nav = array(
            array( 'text' => $this->t['dashboard'][$this->lang],     'disabled'=> ($this->ACL->hasPermission('access_dashboard')       ? false:true), 'leaf' => true, 'cls' => 'file', 'iconCls' => 'icoDash',      'id' => 'panelDashboard' ),
            array( 'text' => $this->t['calendar'][$this->lang],      'disabled'=> ($this->ACL->hasPermission('access_calendar')        ? false:true), 'leaf' => true, 'cls' => 'file', 'iconCls' => 'icoCalendar',  'id' => 'panelCalendar' ),
            array( 'text' => $this->t['messages'][$this->lang],      'disabled'=> ($this->ACL->hasPermission('access_messages')        ? false:true), 'leaf' => true, 'cls' => 'file', 'iconCls' => 'mail',         'id' => 'panelMessages' ),
            array( 'text' => $this->t['patient_Search'][$this->lang],'disabled'=> ($this->ACL->hasPermission('access_patient_search')  ? false:true), 'leaf' => true, 'cls' => 'file', 'iconCls' => 'searchUsers',  'id' => 'panelPatientSearch' ),
            array( 'text' => 'Patient Pool Areas','disabled'=> false, 'leaf' => true, 'cls' => 'file', 'iconCls' => 'icoPoolArea16',  'id' => 'panelPoolArea' )
        );
        // *************************************************************************************
        // Patient Folder
        // *************************************************************************************
	    $patient = array( 'text' => 'Patient', 'cls' => 'folder', 'expanded' => true );

	    if($this->ACL->hasPermission('add_patient')){
		    $patient['children'][] = array( 'text' => $this->t['new_patient'][$this->lang], 'leaf' => true, 'cls' => 'file', 'id' => 'panelNewPatient' );
	    }
	    if($this->ACL->hasPermission('access_patient_summary')){
		    $patient['children'][] = array( 'text' => $this->t['patient_summary'][$this->lang], 'leaf' => true, 'cls' => 'file', 'id' => 'panelSummary' );
	    }
	    if($this->ACL->hasPermission('access_patient_visits')){
		    $patient['children'][] = array( 'text' => $this->t['visist_history'][$this->lang], 'leaf' => true, 'cls' => 'file', 'id' => 'panelVisits' );
	    }
	    if($this->ACL->hasPermission('access_encounters')){
		    $patient['children'][] = array( 'text' => $this->t['encounter'][$this->lang], 'leaf' => true, 'cls' => 'file', 'id' => 'panelEncounter' );
	    }
	    if($this->ACL->hasPermission('access_visit_checkout')){
		    $patient['children'][] = array( 'text' => $this->t['visit_checkout'][$this->lang], 'leaf' => true, 'cls' => 'file', 'id' => 'panelVisitCheckout' );
	    }

	    array_push($nav, $patient);
        // *************************************************************************************
        // Fees Folder
        // *************************************************************************************
        array_push( $nav, array( 'text' => $this->t['billing_manager'][$this->lang], 'cls' => 'folder', 'expanded' => true, 'children' =>
            array(
                array( 'text' => $this->t['payment'][$this->lang],      'leaf' => true, 'cls' => 'file', 'id' => 'panelPayments' ),
                array( 'text' => $this->t['billing'][$this->lang],      'leaf' => true, 'cls' => 'file', 'id' => 'panelBilling' )
            )
        ));

        // *************************************************************************************
        // Administration Folder
        // *************************************************************************************
	    $admin = array( 'text' => 'Administration', 'cls' => 'folder', 'expanded' => true);

	    if($this->ACL->hasPermission('access_gloabal_settings')){
		    $admin['children'][] = array( 'text' => 'Global Settings', 'leaf' => true, 'cls' => 'file', 'id' => 'panelGlobals' );
	    }
	    if($this->ACL->hasPermission('access_facilities')){
		    $admin['children'][] = array( 'text' => 'Facilities', 'leaf' => true, 'cls' => 'file', 'id' => 'panelFacilities' );
	    }
	    if($this->ACL->hasPermission('access_users')){
		    $admin['children'][] = array( 'text' => 'Users', 'leaf' => true, 'cls' => 'file', 'id' => 'panelUsers' );
	    }
	    if($this->ACL->hasPermission('access_practice')){
		    $admin['children'][] = array( 'text' => 'Practice', 'leaf' => true, 'cls' => 'file', 'id' => 'panelPractice' );
	    }
	    if($this->ACL->hasPermission('access_data_manager')){
		    $admin['children'][] = array( 'text' => 'Data Manager', 'leaf' => true, 'cls' => 'file', 'id' => 'panelDataManager' );
	    }
	    if($this->ACL->hasPermission('access_preventive_care')){
		    $admin['children'][] = array( 'text' => 'Preventive Care', 'leaf' => true, 'cls' => 'file', 'id' => 'panelPreventiveCare' );
	    }
	    if($this->ACL->hasPermission('access_medications')){
		    $admin['children'][] = array( 'text' => 'Medications', 'leaf' => true, 'cls' => 'file', 'id' => 'panelMedications' );
	    }
	    if($this->ACL->hasPermission('access_floor_plans')){
		    $admin['children'][] = array( 'text' => 'Floor Areas', 'leaf' => true, 'cls' => 'file', 'id' => 'panelFloorPlans' );
	    }
	    if($this->ACL->hasPermission('access_roles')){
		    $admin['children'][] = array( 'text' => 'Roles', 'leaf' => true, 'cls' => 'file', 'id' => 'panelRoles' );
	    }
	    if($this->ACL->hasPermission('access_layouts')){
		    $admin['children'][] = array( 'text' => 'Layouts', 'leaf' => true, 'cls' => 'file', 'id' => 'panelLayout' );
	    }
	    if($this->ACL->hasPermission('access_lists')){
		    $admin['children'][] = array( 'text' => 'Lists', 'leaf' => true, 'cls' => 'file', 'id' => 'panelLists' );
	    }
	    if($this->ACL->hasPermission('access_event_log')){
		    $admin['children'][] = array( 'text' => 'Event Log', 'leaf' => true, 'cls' => 'file', 'id' => 'panelLog' );
	    }
	    if($this->ACL->hasPermission('access_documents')){
		    $admin['children'][] = array( 'text' => 'Documents', 'leaf' => true, 'cls' => 'file', 'id' => 'panelDocuments' );
	    }

	    if(
	        $this->ACL->hasPermission('access_gloabal_settings') ||
	        $this->ACL->hasPermission('access_facilities') ||
	        $this->ACL->hasPermission('access_users') ||
	        $this->ACL->hasPermission('access_practice') ||
	        $this->ACL->hasPermission('access_services') ||
	        $this->ACL->hasPermission('access_medications') ||
	        $this->ACL->hasPermission('access_floor_plans') ||
	        $this->ACL->hasPermission('access_roles') ||
	        $this->ACL->hasPermission('access_layouts') ||
	        $this->ACL->hasPermission('access_lists') ||
	        $this->ACL->hasPermission('access_event_log')
        ) array_push($nav, $admin) ;

        // *************************************************************************************
        // Miscellaneous Folder
        // *************************************************************************************
        array_push( $nav, array( 'text' => 'Miscellaneous', 'cls' => 'folder', 'expanded' => false, 'children' =>
            array(
                array( 'text' => 'Web Search',      'leaf' => true, 'cls' => 'file', 'id' => 'panelWebsearch' ),
                array( 'text' => 'Address Book',    'leaf' => true, 'cls' => 'file', 'id' => 'panelAddressbook' ),
                array( 'text' => 'Office Notes',    'leaf' => true, 'cls' => 'file', 'id' => 'panelOfficeNotes' ),
                array( 'text' => 'My Settings',     'leaf' => true, 'cls' => 'file', 'id' => 'panelMySettings' ),
                array( 'text' => 'My Account',      'leaf' => true, 'cls' => 'file', 'id' => 'panelMyAccount' )
            )
        ));

        return $nav;

    }

    private function getTexts(){
        return array(
            'dashboard' => array(
                'en_US' => 'Dashboard',
                'es'    => 'Tablero',
            ),
            'calendar' => array(
                'en_US' => 'Calendar',
                'es'    => 'Calendario',
            ),
            'messages' => array(
                'en_US' => 'Messages',
                'es'    => 'Mensajes',
            ),
            'patient_Search' => array(
                'en_US' => 'Patient Search',
                'es'    => 'Busqueda de Paciente',
            ),
            'new_patient' => array(
                'en_US' => 'New Patient',
                'es'    => 'Nuevo Paciente',
            ),
            'patient_summary' => array(
                'en_US' => 'Patient Summary',
                'es'    => 'Resumen de Paciente',
            ),
            'visist_history' => array(
                'en_US' => 'Visits History',
                'es'    => 'Historial de Visitas',
            ),
            'encounter' => array(
                'en_US' => 'Encounter',
                'es'    => 'Encuentro',
            ),
            'visit_checkout' => array(
                'en_US' => 'Visit Checkout',
                'es'    => 'Salida de Paciente',
            ),
            'billing_manager' => array(
                'en_US' => 'Billing Area',
                'es'    => 'Area de Facturacion',
            ),
            'billing' => array(
                'en_US' => 'Encounter Billing',
                'es'    => 'Facturacion de Encouentro',
            ),
            'checkout' => array(
                'en_US' => 'Checkout',
                'es'    => 'Salida',
            ),
            'payment' => array(
                'en_US' => 'Payment Entry',
                'es'    => 'Entrada de Pagos',
            )
        );
    }
}
