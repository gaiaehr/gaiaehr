/**
 * Generated dynamically by Matcha::Connect
 * Create date: 2014-01-08 11:27:31
 */

Ext.define('App.model.patient.ReviewOfSystems',{
    extend: 'Ext.data.Model',
    table: {
        name: 'encounter_review_of_systems',
        comment: 'Review of system'
    },
    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'pid',
            type: 'int',
	        index: true
        },
        {
            name: 'eid',
            type: 'int',
	        index: true
        },
        {
            name: 'uid',
            type: 'int'
        },
        {
            name: 'date',
            type: 'date',
            dateFormat: 'Y-m-d H:i:s'
        },
        {
            name: 'weight_change',
            type: 'bool'
        },
        {
            name: 'weakness',
            type: 'bool'
        },
        {
            name: 'fatigue',
            type: 'bool'
        },
        {
            name: 'anorexia',
            type: 'bool'
        },
        {
            name: 'fever',
            type: 'bool'
        },
        {
            name: 'chills',
            type: 'bool'
        },
        {
            name: 'night_sweats',
            type: 'bool'
        },
        {
            name: 'insomnia',
            type: 'bool'
        },
        {
            name: 'irritability',
            type: 'bool'
        },
        {
            name: 'heat_or_cold',
            type: 'bool'
        },
        {
            name: 'intolerance',
            type: 'bool'
        },
        {
            name: 'change_in_vision',
            type: 'bool'
        },
        {
            name: 'eye_pain',
            type: 'bool'
        },
        {
            name: 'family_history_of_glaucoma',
            type: 'bool'
        },
        {
            name: 'irritation',
            type: 'bool'
        },
        {
            name: 'redness',
            type: 'bool'
        },
        {
            name: 'excessive_tearing',
            type: 'bool'
        },
        {
            name: 'double_vision',
            type: 'bool'
        },
        {
            name: 'blind_spots',
            type: 'bool'
        },
        {
            name: 'photophobia',
            type: 'bool'
        },
        {
            name: 'hearing_loss',
            type: 'bool'
        },
        {
            name: 'discharge',
            type: 'bool'
        },
        {
            name: 'pain',
            type: 'bool'
        },
        {
            name: 'vertigo',
            type: 'bool'
        },
        {
            name: 'tinnitus',
            type: 'bool'
        },
        {
            name: 'frequent_colds',
            type: 'bool'
        },
        {
            name: 'sore_throat',
            type: 'bool'
        },
        {
            name: 'sinus_problems',
            type: 'bool'
        },
        {
            name: 'post_nasal_drip',
            type: 'bool'
        },
        {
            name: 'nosebleed',
            type: 'bool'
        },
        {
            name: 'snoring',
            type: 'bool'
        },
        {
            name: 'apnea',
            type: 'bool'
        },
        {
            name: 'breast_mass',
            type: 'bool'
        },
        {
            name: 'abnormal_mammogram',
            type: 'bool'
        },
        {
            name: 'biopsy',
            type: 'bool'
        },
        {
            name: 'cough',
            type: 'bool'
        },
        {
            name: 'sputum',
            type: 'bool'
        },
        {
            name: 'shortness_of_breath',
            type: 'bool'
        },
        {
            name: 'wheezing',
            type: 'bool'
        },
        {
            name: 'hemoptysis',
            type: 'bool'
        },
        {
            name: 'asthma',
            type: 'bool'
        },
        {
            name: 'copd',
            type: 'bool'
        },
        {
            name: 'thyroid_problems',
            type: 'bool'
        },
        {
            name: 'diabetes',
            type: 'bool'
        },
        {
            name: 'abnormal_blood_test',
            type: 'bool'
        },
        {
            name: 'chest_pain',
            type: 'bool'
        },
        {
            name: 'palpitation',
            type: 'bool'
        },
        {
            name: 'syncope',
            type: 'bool'
        },
        {
            name: 'pnd',
            type: 'bool'
        },
        {
            name: 'doe',
            type: 'bool'
        },
        {
            name: 'orthopnea',
            type: 'bool'
        },
        {
            name: 'peripheral',
            type: 'bool'
        },
        {
            name: 'edema',
            type: 'bool'
        },
        {
            name: 'leg_pain_cramping',
            type: 'bool'
        },
        {
            name: 'arrythmia',
            type: 'bool'
        },
        {
            name: 'heart_problem',
            type: 'bool'
        },
        {
            name: 'history_of_heart_murmur',
            type: 'bool'
        },
        {
            name: 'polyuria',
            type: 'bool'
        },
        {
            name: 'polydypsia',
            type: 'bool'
        },
        {
            name: 'dysuria',
            type: 'bool'
        },
        {
            name: 'hematuria',
            type: 'bool'
        },
        {
            name: 'frequency',
            type: 'bool'
        },
        {
            name: 'urgency',
            type: 'bool'
        },
        {
            name: 'utis',
            type: 'bool'
        },
        {
            name: 'incontinence',
            type: 'bool'
        },
        {
            name: 'renal_stones',
            type: 'bool'
        },
        {
            name: 'hesitancy',
            type: 'bool'
        },
        {
            name: 'dribbling',
            type: 'bool'
        },
        {
            name: 'stream',
            type: 'bool'
        },
        {
            name: 'nocturia',
            type: 'bool'
        },
        {
            name: 'erections',
            type: 'bool'
        },
        {
            name: 'ejaculations',
            type: 'bool'
        },
        {
            name: 'cancer',
            type: 'bool'
        },
        {
            name: 'psoriasis',
            type: 'bool'
        },
        {
            name: 'acne',
            type: 'bool'
        },
        {
            name: 'disease',
            type: 'bool'
        },
        {
            name: 'other',
            type: 'bool'
        },
        {
            name: 'anemia',
            type: 'bool'
        },
        {
            name: 'hiv',
            type: 'bool'
        },
        {
            name: 'f_h_blood_problems',
            type: 'bool'
        },
        {
            name: 'hai_status',
            type: 'bool'
        },
        {
            name: 'allergies',
            type: 'bool'
        },
        {
            name: 'bleeding_problems',
            type: 'bool'
        },
        {
            name: 'frequent_illness',
            type: 'bool'
        },
        {
            name: 'dysphagia',
            type: 'bool'
        },
        {
            name: 'heartburn',
            type: 'bool'
        },
        {
            name: 'food_intolerance',
            type: 'bool'
        },
        {
            name: 'belching',
            type: 'bool'
        },
        {
            name: 'bloating',
            type: 'bool'
        },
        {
            name: 'flatulence',
            type: 'bool'
        },
        {
            name: 'nausea',
            type: 'bool'
        },
        {
            name: 'vomiting',
            type: 'bool'
        },
        {
            name: 'jaundice',
            type: 'bool'
        },
        {
            name: 'h_o_hepatitis',
            type: 'bool'
        },
        {
            name: 'hematemesis',
            type: 'bool'
        },
        {
            name: 'diarrhea',
            type: 'bool'
        },
        {
            name: 'hematochezia',
            type: 'bool'
        },
        {
            name: 'changed_bowel',
            type: 'bool'
        },
        {
            name: 'constipation',
            type: 'bool'
        },
        {
            name: 'female_g',
            type: 'bool'
        },
        {
            name: 'female_p',
            type: 'bool'
        },
        {
            name: 'female_ap',
            type: 'bool'
        },
        {
            name: 'lmp',
            type: 'bool'
        },
        {
            name: 'female_lc',
            type: 'bool'
        },
        {
            name: 'menopause',
            type: 'bool'
        },
        {
            name: 'flow',
            type: 'bool'
        },
        {
            name: 'abnormal_hair_growth',
            type: 'bool'
        },
        {
            name: 'menarche',
            type: 'bool'
        },
        {
            name: 'symptoms',
            type: 'bool'
        },
        {
            name: 'f_h_female_hirsutism_striae',
            type: 'bool'
        },
        {
            name: 'anxiety',
            type: 'bool'
        },
        {
            name: 'depression',
            type: 'bool'
        },
        {
            name: 'psychiatric_medication',
            type: 'bool'
        },
        {
            name: 'social_difficulties',
            type: 'bool'
        },
        {
            name: 'psychiatric_diagnosis',
            type: 'bool'
        },
        {
            name: 'fms',
            type: 'bool'
        },
        {
            name: 'swelling',
            type: 'bool'
        },
        {
            name: 'Warm',
            type: 'bool'
        },
        {
            name: 'muscle',
            type: 'bool'
        },
        {
            name: 'stiffness',
            type: 'bool'
        },
        {
            name: 'aches',
            type: 'bool'
        },
        {
            name: 'arthritis',
            type: 'bool'
        },
        {
            name: 'chronic_joint_pain',
            type: 'bool'
        },
        {
            name: 'loc',
            type: 'bool'
        },
        {
            name: 'stroke',
            type: 'bool'
        },
        {
            name: 'paralysis',
            type: 'bool'
        },
        {
            name: 'tia',
            type: 'bool'
        },
        {
            name: 'numbness',
            type: 'bool'
        },
        {
            name: 'memory_problems',
            type: 'bool'
        },
        {
            name: 'seizures',
            type: 'bool'
        },
        {
            name: 'intellectual_decline',
            type: 'bool'
        },
        {
            name: 'dementia',
            type: 'bool'
        },
        {
            name: 'headache',
            type: 'bool'
        },
        {
            name: 'cons_weakness',
            type: 'bool'
        },
        {
            name: 'brest_discharge',
            type: 'bool'
        },
        {
            name: 'fem_frequency',
            type: 'bool'
        },
        {
            name: 'notes',
            type: 'string',
	        dataType: 'mediumtext'
        }
    ],
    proxy: {
        type: 'direct',
        api: {
            update: Encounter.updateReviewOfSystemsById
        }
    },
    belongsTo: {
        model: 'App.model.patient.Encounter',
        foreignKey: 'eid'
    }
});
