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
namespace modules\reportcenter\dataProvider;

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

include_once('Reports.php');
include_once(ROOT . '/classes/MatchaHelper.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/Encounter.php');
include_once(ROOT . '/dataProvider/i18nRouter.php');

class ClinicMeasures extends Reports {
	private $db;
	private $user;
	private $patient;
	private $encounter;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct() {
		parent::__construct();
		$this ->db = new \MatchaHelper();
		$this ->user = new \User();
		$this ->patient = new \Patient();
		$this ->encounter = new \Encounter();

		return;
	}

	public function getAmbulatoryClinicalQualityMeasures() {
		$acqm = array();
		$acqm['NQF0001'] = 'Asthma Assesment';
		$acqm['NQF0002'] = 'Pharyngitis - Children';
		$acqm['NQF0004'] = 'Alcohol and Drug Dependence';
		$acqm['NQF0012'] = 'Prenatal Care: HIV Screening';
		$acqm['NQF0013'] = 'Hypertension: Blood Pressure Measurement';
		$acqm['NQF0014'] = 'Prenatal Care: Anti-D immune Globulin';
		$acqm['NQF0018'] = 'Controlling High Blood Pressure';
		$acqm['NQF0024'] = 'Youth Weight Assesment';
		$acqm['NQF0027'] = 'Tobacco Use Cessation';
		$acqm['NQF0028'] = 'Prevenitive Care: Tobacco Use Assesment and Cessation';
		$acqm['NQF0031'] = 'Breast Cancer Screening';
		$acqm['NQF0033'] = 'Chlamydia Screening for Women';
		$acqm['NQF0034'] = 'Colorectal Cancer Screening';
		$acqm['NQF0036'] = 'Appropriate Medications for Asthma';
		$acqm['NQF0038'] = 'Childhood Immunization Status';
		$acqm['NQF0041'] = 'Influenza Immunization';
		$acqm['NQF0043'] = 'Pnemonia Vaccination';
		$acqm['NQF0047'] = 'Asthma Pharmacologic Therapy';
		$acqm['NQF0052'] = 'Use of Imaging Study: Low Back Pain';
		$acqm['NQF0055'] = 'Diabetes: Eye Exam';
		$acqm['NQF0056'] = 'Diabetes: Foot Exam';
		$acqm['NQF0059'] = 'Diabetes Control: Hemoglobin A1c > 9.0%';
		$acqm['NQF0061'] = 'Diabetic Patients who elevated mmhg V 140/90';
		$acqm['NQF0062'] = 'Nephropathy Screening- Urine';
		$acqm['NQF0064'] = 'Diabetes Control: LDL < 100mg/dl';
		$acqm['NQF0067'] = 'Antiplatelet Therapy';
		$acqm['NQF0068'] = 'Ischemic Vascular Disease: Asparin or other Antithrombotic';
		$acqm['NQF0070'] = 'Coronoary Artery Disease: Beta Blocker Therapy Post Myocadial Infarction';
		$acqm['NQF0073'] = 'Blood Pressure Management: Ischemic Valve Disease';
		$acqm['NQF0074'] = 'Corinary Artery Disease: Lipid Lowering Therapy';
		$acqm['NQF0075'] = 'IVD: Complete Lipid Panel and LDL Control';
		$acqm['NQF0081'] = 'Heart Failure: ACE / ARB Therapy For LVSD (LVEF < 40%)';
		$acqm['NQF0083'] = 'Heart Failure: Beta Blocker for LVSD';
		$acqm['NQF0084'] = 'Heart Failure: Warfarin Therapy';
		$acqm['NQF0086'] = 'Primary Open Angle Glaucoma';
		$acqm['NQF0088'] = 'Diabetic Retinopathy: Macular Edema';
		$acqm['NQF0089'] = 'Diabetes Management: Retinopathy Screening';
		$acqm['NQF0105'] = 'Depression Management';
		$acqm['NQF0385'] = 'Colon Cancer: Chemotherapy';
		$acqm['NQF0387'] = 'Breast Cancer: Hormonal Therapy';
		$acqm['NQF0389'] = 'Prostate Cancer: Avoid overuse of Bone Scan';
		$acqm['NQF0421'] = 'Adult Weight Screening';
		$acqm['NQF0575'] = 'Diabetes Control: Hemoglobin A1c < 8.0%';
		return $acqm;
	}

	public function getStandardMeasures() {
		$sm = array();
		$sm[] = 'Adult Weight Screening and Follow-Up';
		$sm[] = 'Cancer Screening: Colon Cancer Screening';
		$sm[] = 'Cancer Screening: Mammogram';
		$sm[] = 'Cancer Screening: Pap Smear';
		$sm[] = 'Cancer Screening: Prostate Cancer Screening';
		$sm[] = 'Diabetes: Eye Exam';
		$sm[] = 'Diabetes: Foot Exam';
		$sm[] = 'Diabetes: Hemoglobin A1C';
		$sm[] = 'Diabetes: Urine Microalbumin';
		$sm[] = 'Hypertension: Blood Pressure Measurement';
		$sm[] = 'Influenza Immunization for Patients >= 50 Years Old';
		$sm[] = 'Coumadin Management - INR Monitoring';
		$sm[] = 'Pneumonia Vaccination Status for Older Adults';
		$sm[] = 'Tobacco Cessation Intervention';
		$sm[] = 'Tobacco Use Assessment';
		$sm[] = 'Weight Assessment and Counseling for Children and Adolescents';
		$sm[] = 'Measurement: Weight';
		$sm[] = 'Education: Weight';
		$sm[] = 'Education: Nutrition';
		$sm[] = 'Education: Exercise';
		$sm[] = 'Measurement: BMI';
	}

}

//$e = new Clinical();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//print_r($e->getClinical('','','','2010-03-08','2013-03-08',0,10,'',''));
