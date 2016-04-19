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

include_once(ROOT . '/dataProvider/i18nRouter.php');

class CombosData {

	/**
	 * @var MatchaCUP
	 */
	private $CLO;
	/**
	 * @var MatchaCUP
	 */
	private $CC;
	/**
	 * @var MatchaCUP
	 */
	private $P;
	/**
	 * @var MatchaCUP
	 */
	private $F;
	/**
	 * @var MatchaCUP
	 */
	private $FP;
	/**
	 * @var MatchaCUP
	 */
	private $I;
	/**
	 * @var MatchaCUP
	 */
	private $R;
	/**
	 * @var MatchaCUP
	 */
	private $U;
	/**
	 * @var MatchaCUP
	 */
	private $CL;
	/**
	 * @var MatchaCUP
	 */
	private $A;
	/**
	 * @var MatchaCUP
	 */
	private $DT;

	/**
	 * Main Sencha Model Getter and Setters
	 */
	public function getOptionsByListId(stdClass $params){
		if($this->CL == null)
			$this->CL = MatchaModel::setSenchaModel('App.model.administration.Lists');
		if($this->CLO == null)
			$this->CLO = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		if(isset($params->list_id)){
			if(is_numeric($params->list_id)){

				$sorters = new stdClass();
				$sorters->sort[0] = new stdClass();
				$sorters->sort[0]->property = 'seq';
				$sorters->sort[0]->direction = 'ASC';

				return $this->CLO->load(['list_id' => $params->list_id, 'active' => 1])->sort($sorters)->all();

			} else{
				if($params->list_id == 'activePharmacies'){
					return $this->getActivePharmacies();
				} elseif($params->list_id == 'activeProviders'){
					return $this->getActiveProviders();
				} elseif($params->list_id == 'activeFacilities'){
					return $this->getActiveFacilities();
				} elseif($params->list_id == 'billingFacilities'){
					return $this->getBillingFacilities();
				} elseif($params->list_id == 'activeInsurances'){
					return $this->getActiveInsurances();
				} elseif($params->list_id == 'referringPhysicians'){
					return $this->getReferringPhysicians();
				} else{
					return false;
				}
			}
		}
	}

	public function getTimeZoneList(){
		$locations = [];
		$zones = timezone_identifiers_list();
		foreach($zones as $zone)
			$locations[] = [
				'value' => $zone,
				'name' => str_replace('_', ' ', $zone)
			];
		return $locations;
	}

	public function getActivePharmacies(){
		if($this->P == null)
			$this->P = MatchaModel::setSenchaModel('App.model.administration.Pharmacies');
		$options = [];
		foreach($this->P->load(['active' => '1'])->all() as $option){
			$options[] = [
				'option_name' => $option['name'],
				'option_value' => $option['id']
			];
		}
		return $options;
	}

	public function getActiveInsurances(){
		if($this->I == null)
			$this->I = MatchaModel::setSenchaModel('App.model.administration.InsuranceCompany');
		$options = [];
		$this->I->addFilter('active', 1);
		$records = $this->I->load()->all();
		foreach($records as $record){
			$options[] = [
				'option_name' => $record['id'] . ': ' .$record['name'],
				'option_value' => $record['id']
			];
		}
		return $options;
	}

	public function getReferringPhysicians($query = ''){
		if($this->R == null)
			$this->R = MatchaModel::setSenchaModel('App.model.administration.ReferringProvider');

		$options = [];

		return $options;
	}

	public function getActiveProviders(){
		if($this->U == null)
			$this->U = MatchaModel::setSenchaModel('App.model.administration.User');

		$sql = 'SELECT users.id AS option_value, CONCAT(users.title, \' \', users.lname, \', \', users.fname) as option_name
			      FROM `users`
				 WHERE active = \'1\' AND authorized = \'1\' AND (npi IS NOT null AND npi != \'\')
				 ORDER BY option_name ASC';
		return $this->U->sql($sql)->all();
	}

	public function getFacilities(){
		if($this->F == null)
			$this->F = MatchaModel::setSenchaModel('App.model.administration.Facility');
		$sql = 'SELECT * FROM `facility` ORDER BY `name`';
		return $this->F->sql($sql)->all();
	}

	public function getActiveFacilities(){
		if($this->F == null)
			$this->F = MatchaModel::setSenchaModel('App.model.administration.Facility');
		$sql = 'SELECT id AS option_value, `name` AS option_name FROM `facility` WHERE active = \'1\' ORDER BY `name`';
		$records = $this->F->sql($sql)->all();
		foreach($records as $i => $record){
			$records[$i]['option_value'] = intval($record['option_value']);
		}
		return $records;
	}

	public function getBillingFacilities(){
		if($this->F == null)
			$this->F = MatchaModel::setSenchaModel('App.model.administration.Facility');
		$argumentSQL['SELECT'] = "id AS option_value, `name` AS option_name";
		$argumentSQL['WHERE'] = "active = '1' AND billing_location = '1'";
		$records = $this->F->buildSQL($argumentSQL)->all();
		foreach($records as $i => $record){
			$records[$i]['option_value'] = intval($record['option_value']);
		}
		return $records;
	}

	public function getUsers(){
		include_once('Person.php');
		if($this->U == null){
			$this->U = MatchaModel::setSenchaModel('App.model.administration.User');
		}
		$rows = [];
		$records = $this->U->load(['active' => 1], ['id','title','fname','mname','lname'])->all();

		foreach($records['data'] as $row){
			$row['name'] = $row['title'] . ' ' . Person::fullname($row['fname'], $row['mname'], $row['lname']);
			unset($row['title'], $row['fname'], $row['mname'], $row['lname']);
			array_push($rows, $row);
		}
		return $rows;
	}

	public function getLists(){
		if($this->CL == null)
			$this->CL = MatchaModel::setSenchaModel('App.model.administration.Lists');
		$records = $this->CL->load()->all();
		// manually add all system combos that are not stored combo_lists table
		$records[] = [
			'id' => 'activePharmacies',
			'title' => 'Active Pharmacies'
		];
		$records[] = [
			'id' => 'activeProviders',
			'title' => 'Active Providers'
		];
		$records[] = [
			'id' => 'activeInsurances',
			'title' => 'Active Insurances'
		];
		$records[] = [
			'id' => 'activeFacilities',
			'title' => 'Active Facilities'
		];
		$records[] = [
			'id' => 'billingFacilities',
			'title' => 'Billing Facilities'
		];
		return $records;
	}


	public function getRoles(){
		if($this->F == null)
			$this->F = MatchaModel::setSenchaModel('App.model.administration.AclRoles');
		return $this->F->load()->all();
	}

	public function getCalendarCategories(){
		if($this->CC == null)
			$this->CC = MatchaModel::setSenchaModel('App.model.administration.CalendarCategory');
		return $this->CC->load()->all();
	}

	public function getFloorPlanAreas(){
		if($this->FP == null)
			$this->FP = MatchaModel::setSenchaModel('App.model.administration.FloorPlans');
		return $this->FP->load([
			'active' => '1',
			'facility_id' => $_SESSION['user']['facility']
		], [
			'id',
			'title'
		])->all();
	}

	public function getAuthorizations(){
		return [
			[
				'id' => 1,
				'name' => 'Print'
			],
			[
				'id' => 2,
				'name' => 'Email'
			],
			[
				'id' => 3,
				'name' => 'Email'
			],
		];
	}

	public function getSeeAuthorizations(){
		return [
			[
				'id' => 1,
				'name' => 'none'
			],
			[
				'id' => 2,
				'name' => 'Only Mine'
			],
			[
				'id' => 3,
				'name' => 'All'
			],
		];
	}

	public function getTaxIds(){
		return [
			[
				'option_id' => 'EI',
				'title' => 'EIN'
			],
			[
				'option_id' => 'SY',
				'title' => 'SSN'
			],
		];
	}

	public function getFiledXtypes(){
		return [
			0 => [
				'id' => 1,
				'name' => 'Fieldset',
				'value' => 'fieldset'
			],
			1 => [
				'id' => 2,
				'name' => 'Field Container',
				'value' => 'fieldcontainer'
			],
			2 => [
				'id' => 3,
				'name' => 'Display Field',
				'value' => 'displayfield'
			],
			3 => [
				'id' => 4,
				'name' => 'Text Field',
				'value' => 'textfield'
			],
			4 => [
				'id' => 5,
				'name' => 'TextArea Field',
				'value' => 'textareafield'
			],
			5 => [
				'id' => 6,
				'name' => 'CheckBox Field',
				'value' => 'checkbox'
			],
			6 => [
				'id' => 7,
				'name' => 'Slelect List / Combo Box',
				'value' => 'combobox'
			],
			7 => [
				'id' => 8,
				'name' => 'Radio Field',
				'value' => 'radiofield'
			],
			8 => [
				'id' => 9,
				'name' => 'Date/Time Field',
				'value' => 'mitos.datetime'
			],
			9 => [
				'id' => 10,
				'name' => 'Date Field',
				'value' => 'datefield'
			],
			10 => [
				'id' => 11,
				'name' => 'Time Field',
				'value' => 'timefield'
			],
			11 => [
				'id' => 12,
				'name' => 'Number Field',
				'value' => 'numberfield'
			],
			12 => [
				'id' => 13,
				'name' => 'CheckBox With Family History',
				'value' => 'checkboxwithfamilyhistory'
			],
            13 => [
                'id' => 14,
                'name' => 'Referring Physician Search',
                'value' => 'referringphysicianlivetsearch'
            ]
		];
	}

	public function getPosCodes(){
		$pos = [];
		$pos[] = [
			'code' => '01',
			'title' => 'Pharmacy',
			'description' => 'A facility or location where drugs and other medically related items and services are sold, dispensed, or otherwise provided directly to patients.'
		];
//		$pos[] = array(
//			'code' => '02',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '03',
			'title' => 'School',
			'description' => 'A facility whose primary purpose is education.'
		];
		$pos[] = [
			'code' => '04',
			'title' => 'Homeless Shelter',
			'description' => 'A facility or location whose primary purpose is to provide temporary housing to homeless individuals (e.g., emergency shelters, individual or family shelters).'
		];
		$pos[] = [
			'code' => '05',
			'title' => 'Indian Health Service Free-standing Facility',
			'description' => 'A facility or location, owned and operated by the Indian Health Service, which provides diagnostic, therapeutic (surgical and non-surgical), and rehabilitation services to American Indians and Alaska Natives who do not require hospitalization.'
		];
		$pos[] = [
			'code' => '06',
			'title' => 'Indian Health Service Provider-based Facility',
			'description' => 'A facility or location, owned and operated by the Indian Health Service, which provides diagnostic, therapeutic (surgical and non-surgical), and rehabilitation services rendered by, or under the supervision of, physicians to American Indians and Alaska Natives admitted as inpatients or outpatients.'
		];
		$pos[] = [
			'code' => '07',
			'title' => 'Tribal 638 Free-standing Facility',
			'description' => 'A facility or location owned and operated by a federally recognized American Indian or Alaska Native tribe or tribal organization under a 638 agreement, which provides diagnostic, therapeutic (surgical and non-surgical), and rehabilitation services to tribal members who do not require hospitalization.'
		];
		$pos[] = [
			'code' => '08',
			'title' => 'Tribal 638 Provider-based Facility',
			'description' => 'A facility or location owned and operated by a federally recognized American Indian or Alaska Native tribe or tribal organization under a 638 agreement, which provides diagnostic, therapeutic (surgical and non-surgical), and rehabilitation services to tribal members admitted as inpatients or outpatients.'
		];
		$pos[] = [
			'code' => '09',
			'title' => 'Prison/Correctional Facility',
			'description' => 'A prison, jail, reformatory, work farm, detention center, or any other similar facility maintained by either Federal, State or local authorities for the purpose of confinement or rehabilitation of adult or juvenile criminal offenders.'
		];
//		$pos[] = array(
//			'code' => '10',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '11',
			'title' => 'Office ',
			'description' => 'Location, other than a hospital, skilled nursing facility (SNF), military treatment facility, community health center, State or local public health clinic, or intermediate care facility (ICF), where the health professional routinely provides health examinations, diagnosis, and treatment of illness or injury on an ambulatory basis.'
		];
		$pos[] = [
			'code' => '12',
			'title' => 'Home',
			'description' => 'Location, other than a hospital or other facility, where the patient receives care in a private residence.'
		];
		$pos[] = [
			'code' => '13',
			'title' => 'Assisted Living Facility',
			'description' => 'Congregate residential facility with self-contained living units providing assessment of each resident’s needs and on-site support 24 hours a day, 7 days a week, with the capacity to deliver or arrange for services including some health care and other services.  (effective 10/1/03)'
		];
		$pos[] = [
			'code' => '14',
			'title' => 'Group Home *',
			'description' => 'A residence, with shared living areas, where clients receive supervision and other services such as social and/or behavioral services, custodial service, and minimal services (e.g., medication administration).'
		];
		$pos[] = [
			'code' => '15',
			'title' => 'Mobile Unit',
			'description' => 'A facility/unit that moves from place-to-place equipped to provide preventive, screening, diagnostic, and/or treatment services.'
		];
		$pos[] = [
			'code' => '16',
			'title' => 'Temporary Lodging',
			'description' => 'A short term accommodation such as a hotel, camp ground, hostel, cruise ship or resort where the patient receives care, and which is not identified by any other POS code.'
		];
		$pos[] = [
			'code' => '17',
			'title' => 'Walk-in Retail Health Clinic',
			'description' => 'A walk-in health clinic, other than an office, urgent care facility, pharmacy or independent clinic and not described by any other Place of Service code, that is located within a retail operation and provides, on an ambulatory basis, preventive and primary care services.'
		];
		$pos[] = [
			'code' => '18',
			'title' => 'Place of Employment-Worksite',
			'description' => 'A location, not described by any other POS code, owned or operated by a public or private entity where the patient is employed, and where a health professional provides on-going or episodic occupational medical, therapeutic or rehabilitative services to the individual.'
		];
//		$pos[] = array(
//			'code' => '19',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '20',
			'title' => 'Urgent Care Facility',
			'description' => 'Location, distinct from a hospital emergency room, an office, or a clinic, whose purpose is to diagnose and treat illness or injury for unscheduled, ambulatory patients seeking immediate medical attention.'
		];
		$pos[] = [
			'code' => '21',
			'title' => 'Inpatient Hospital',
			'description' => 'A facility, other than psychiatric, which primarily provides diagnostic, therapeutic (both surgical and nonsurgical), and rehabilitation services by, or under, the supervision of physicians to patients admitted for a variety of medical conditions.'
		];
		$pos[] = [
			'code' => '22',
			'title' => 'Outpatient Hospital',
			'description' => 'A portion of a hospital which provides diagnostic, therapeutic (both surgical and nonsurgical), and rehabilitation services to sick or injured persons who do not require hospitalization or institutionalization.'
		];
		$pos[] = [
			'code' => '23',
			'title' => 'Emergency Room - Hospital',
			'description' => 'A portion of a hospital where emergency diagnosis and treatment of illness or injury is provided.'
		];
		$pos[] = [
			'code' => '24',
			'title' => 'Ambulatory Surgical Center',
			'description' => 'A freestanding facility, other than a physician\'s office, where surgical and diagnostic services are provided on an ambulatory basis.'
		];
		$pos[] = [
			'code' => '25',
			'title' => 'Birthing Center ',
			'description' => 'A facility, other than a hospital\'s maternity facilities or a physician\'s office, which provides a setting for labor, delivery, and immediate post-partum care as well as immediate care of new born infants.'
		];
		$pos[] = [
			'code' => '26',
			'title' => 'Military Treatment Facility',
			'description' => 'A medical facility operated by one or more of the Uniformed Services. Military Treatment Facility (MTF) also refers to certain former U.S. Public Health Service (USPHS) facilities now designated as Uniformed Service Treatment Facilities (USTF).'
		];
//		$pos[] = array(
//			'code' => '27',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '28',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '29',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '30',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '31',
			'title' => 'Skilled Nursing Facility',
			'description' => 'A facility which primarily provides inpatient skilled nursing care and related services to patients who require medical, nursing, or rehabilitative services but does not provide the level of care or treatment available in a hospital.'
		];
		$pos[] = [
			'code' => '32',
			'title' => 'Nursing Facility',
			'description' => 'A facility which primarily provides to residents skilled nursing care and related services for the rehabilitation of injured, disabled, or sick persons, or, on a regular basis, health-related care services above the level of custodial care to other than mentally retarded individuals.'
		];
		$pos[] = [
			'code' => '33',
			'title' => 'Custodial Care Facility',
			'description' => 'A facility which provides room, board and other personal assistance services, generally on a long-term basis, and which does not include a medical component.'
		];
		$pos[] = [
			'code' => '34',
			'title' => 'Hospice',
			'description' => 'A facility, other than a patient\'s home, in which palliative and supportive care for terminally ill patients and their families are provided.'
		];
//		$pos[] = array(
//			'code' => '35',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '36',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '37',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '38',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '39',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '40',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '41',
			'title' => 'Ambulance - Land',
			'description' => 'A land vehicle specifically designed, equipped and staffed for lifesaving and transporting the sick or injured.'
		];
		$pos[] = [
			'code' => '42',
			'title' => 'Ambulance - Air or Water',
			'description' => 'An air or water vehicle specifically designed, equipped and staffed for lifesaving and transporting the sick or injured.'
		];
//		$pos[] = array(
//			'code' => '43',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '44',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '45',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '46',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '47',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '48',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '49',
			'title' => 'Independent Clinic',
			'description' => 'A location, not part of a hospital and not described by any other Place of Service code, that is organized and operated to provide preventive, diagnostic, therapeutic, rehabilitative, or palliative services to outpatients only.  (effective 10/1/03)'
		];
		$pos[] = [
			'code' => '50',
			'title' => 'Federally Qualified Health Center',
			'description' => 'A facility located in a medically underserved area that provides Medicare beneficiaries preventive primary medical care under the general direction of a physician.'
		];
		$pos[] = [
			'code' => '51',
			'title' => 'Inpatient Psychiatric Facility',
			'description' => 'A facility that provides inpatient psychiatric services for the diagnosis and treatment of mental illness on a 24-hour basis, by or under the supervision of a physician.'
		];
		$pos[] = [
			'code' => '52',
			'title' => 'Psychiatric Facility-Partial Hospitalization',
			'description' => 'A facility for the diagnosis and treatment of mental illness that provides a planned therapeutic program for patients who do not require full time hospitalization, but who need broader programs than are possible from outpatient visits to a hospital-based or hospital-affiliated facility.'
		];
		$pos[] = [
			'code' => '53',
			'title' => 'Community Mental Health Center',
			'description' => 'A facility that provides the following services: outpatient services, including specialized outpatient services for children, the elderly, individuals who are chronically ill, and residents of the CMHC\'s mental health services area who have been discharged from inpatient treatment at a mental health facility; 24 hour a day emergency care services; day treatment, other partial hospitalization services, or psychosocial rehabilitation services; screening for patients being considered for admission to State mental health facilities to determine the appropriateness of such admission; and consultation and education services.'
		];
		$pos[] = [
			'code' => '54',
			'title' => 'Intermediate Care Facility/Mentally Retarded',
			'description' => 'A facility which primarily provides health-related care and services above the level of custodial care to mentally retarded individuals but does not provide the level of care or treatment available in a hospital or SNF.'
		];
		$pos[] = [
			'code' => '55',
			'title' => 'Residential Substance Abuse Treatment Facility',
			'description' => 'A facility which provides treatment for substance (alcohol and drug) abuse to live-in residents who do not require acute medical care. Services include individual and group therapy and counseling, family counseling, laboratory tests, drugs and supplies, psychological testing, and room and board.'
		];
		$pos[] = [
			'code' => '56',
			'title' => 'Psychiatric Residential Treatment Center',
			'description' => 'A facility or distinct part of a facility for psychiatric care which provides a total 24-hour therapeutically planned and professionally staffed group living and learning environment.'
		];
		$pos[] = [
			'code' => '57',
			'title' => 'Non-residential Substance Abuse Treatment Facility',
			'description' => 'A location which provides treatment for substance (alcohol and drug) abuse on an ambulatory basis.  Services include individual and group therapy and counseling, family counseling, laboratory tests, drugs and supplies, and psychological testing.  (effective 10/1/03)'
		];
//		$pos[] = array(
//			'code' => '58',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '59',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '60',
			'title' => 'Mass Immunization Center',
			'description' => 'A location where providers administer pneumococcal pneumonia and influenza virus vaccinations and submit these services as electronic media claims, paper claims, or using the roster billing method. This generally takes place in a mass immunization setting, such as, a public health center, pharmacy, or mall but may include a physician office setting.'
		];
		$pos[] = [
			'code' => '61',
			'title' => 'Comprehensive Inpatient Rehabilitation Facility',
			'description' => 'A facility that provides comprehensive rehabilitation services under the supervision of a physician to inpatients with physical disabilities. Services include physical therapy, occupational therapy, speech pathology, social or psychological services, and orthotics and prosthetics services.'
		];
		$pos[] = [
			'code' => '62',
			'title' => 'Comprehensive Outpatient Rehabilitation Facility',
			'description' => 'A facility that provides comprehensive rehabilitation services under the supervision of a physician to outpatients with physical disabilities. Services include physical therapy, occupational therapy, and speech pathology services.'
		];
//		$pos[] = array(
//			'code' => '63',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '64',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '65',
			'title' => 'End-Stage Renal Disease Treatment Facility',
			'description' => 'A facility other than a hospital, which provides dialysis treatment, maintenance, and/or training to patients or caregivers on an ambulatory or home-care basis.'
		];
//		$pos[] = array(
//			'code' => '66',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '67',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '68',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '69',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '70',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '71',
			'title' => 'Public Health Clinic',
			'description' => 'A facility maintained by either State or local health departments that provides ambulatory primary medical care under the general direction of a physician.  (effective 10/1/03)'
		];
		$pos[] = [
			'code' => '72',
			'title' => 'Rural Health Clinic',
			'description' => 'A certified facility which is located in a rural medically underserved area that provides ambulatory primary medical care under the general direction of a physician.'
		];
//		$pos[] = array(
//			'code' => '73',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '74',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '75',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '76',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '77',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '78',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '79',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '80',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '81',
			'title' => 'Independent Laboratory',
			'description' => 'A laboratory certified to perform diagnostic and/or clinical tests independent of an institution or a physician\'s office.'
		];
//		$pos[] = array(
//			'code' => '82',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '83',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '84',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '85',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '86',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '87',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '88',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '89',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '90',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '91',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '92',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '93',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '94',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '95',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '96',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '97',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
//		$pos[] = array(
//			'code' => '98',
//			'title' => 'Unassigned',
//			'description' => 'N/A'
//		);
		$pos[] = [
			'code' => '99',
			'title' => 'Other Place of Service',
			'description' => 'Other place of service not identified above.'
		];
		return $pos;
	}

	public function getAllergiesByType(stdClass $params){
		if($this->A == null)
			$this->A = MatchaModel::setSenchaModel('App.model.administration.Allergies');
		return $this->A->load(['allergy_type' => $params->allergy_type])->all();
	}

	public function getAllergyTypes(){
		if($this->A == null)
			$this->A = MatchaModel::setSenchaModel('App.model.administration.Allergies');
		$params = new stdClass();
		$params->group = [];
		$params->group[0] = new stdClass();
		$params->group[0]->property = 'allergy_type';
		$records = $this->A->load($params)->all();
		$records[] = ['allergy_type' => 'Drug'];
		return $records;
	}

	public function getTemplatesTypes(){
		if($this->DT == null)
			$this->DT = MatchaModel::setSenchaModel('App.model.administration.DocumentsTemplates');
		$records = $this->DT->load(['template_type' => 'documenttemplate'], ['id','title'])->all();
		return $records;
	}

	public function getThemes(){
		return [
			[
				'name' => 'Gray',
				'value' => 'ext-all-gray'
			],
			[
				'name' => 'Blue',
				'value' => 'ext-all'
			]
		];
	}

	public function getValuesByListIdAndOptionValue($listId, $optionValue) {
		if($this->CLO == null)
			$this->CLO = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		$foo = $this->CLO->load([
			'list_id' => $listId,
			'option_value' => $optionValue
		])->one();
		return $foo;
	}

	public function getDisplayValueByListIdAndOptionValue($listId, $optionValue) {
		if($this->CLO == null)
			$this->CLO = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		$foo = $this->CLO->load([
			'list_id' => $listId,
			'option_value' => $optionValue
		])->one();
		return $foo !== false ? $foo['option_name'] : $optionValue;
	}

	public function getDisplayValueByListIdAndOptionCode($listId, $optionCode) {
		if($this->CLO == null)
			$this->CLO = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		$foo = $this->CLO->load([
			'list_id' => $listId,
			'code' => $optionCode
		])->one();
		return $foo !== false ? $foo['option_name'] : $optionCode;
	}

	public function getCodeValueByListIdAndOptionValue($listId, $optionValue) {
		if($this->CLO == null)
			$this->CLO = MatchaModel::setSenchaModel('App.model.administration.ListOptions');
		$foo = $this->CLO->load([
			'list_id' => $listId,
			'option_value' => $optionValue
		])->one();
		return $foo !== false ? $foo['code'] : $optionValue;
	}

	public function getEncounterSupervisors(){
		$providers = $this->getActiveProviders();
		$supervisors = [];
		foreach($providers as $provider){
			if(ACL::hasPermissionByUid('sign_enc_supervisor', $provider['option_value'])) $supervisors[] = $provider;
		}

		return $supervisors;
	}

}
