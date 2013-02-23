Ext.define('App.model.patient.Insurance', {
	extend: 'Ext.data.Model',
	table: {
		name:'patient_insurances',
		comment:'Patient Insurances'
	},
	fields: [
        {name: 'id',                            type: 'int',comment:'ID'},
        {name: 'pid',                           type: 'int',comment:'Patient ID'},
		{name: 'createUid',                     type: 'int',comment:'create user ID'},
		{name: 'writeUid',                      type: 'int',comment:'update user ID'},
		{name: 'createDate',                    type: 'date',dateFormat:'Y-m-d H:i:s', comment:'create date'},
		{name: 'updateDate',                    type: 'date',dateFormat:'Y-m-d H:i:s', comment:'last update date'},
        {name: 'provider',                      type: 'string',comment:'provider'},
        {name: 'planName',                      type: 'string',comment:'plan name'},
        {name: 'effectiveDate',                 type: 'string',comment:'affective date'},
        {name: 'policyNumber',                  type: 'string',comment:'policy number'},
        {name: 'groupNumber',                   type: 'string',comment:'group number'},
        {name: 'subscriberTitle',               type: 'string',comment:'subscriber title'},
        {name: 'subscriberGivenName',           type: 'string',comment:'subscriber first name'},
        {name: 'subscriberMiddleName',          type: 'string',comment:'subscriber middle name'},
        {name: 'subscriberSurname',             type: 'string',comment:'subscriber last name'},
		{name: 'subscriberRelationship',        type: 'string',comment:'subscriber relationship'},
		{name: 'subscriberStreet',              type: 'string',comment:'subscriber address'},
        {name: 'subscriberCity',                type: 'string',comment:'subscriber city'},
        {name: 'subscriberState',               type: 'string',comment:'subscriber state'},
        {name: 'subscriberCountry',             type: 'string',comment:'subscriber country'},
        {name: 'subscriberPostalCode',          type: 'string',comment:'subscriber postal code'},
        {name: 'subscriberPhone',               type: 'string',comment:'subscriber phone #'},
        {name: 'subscriberEmployer',            type: 'string',comment:'subscriber employer'},
        {name: 'subscriberEmployerStreet',      type: 'string',comment:'subscriber employer address'},
        {name: 'subscriberEmployerCity',        type: 'string',comment:'subscriber employer city'},
        {name: 'subscriberEmployerState',       type: 'string',comment:'subscriber employer state'},
        {name: 'subscriberEmployerCountry',     type: 'string',comment:'subscriber employer country'},
        {name: 'subscriberEmployerPostalCode',  type: 'string',comment:'subscriber employer postal code'},
        {name: 'subscriberDob',                 type: 'string',comment:'subscriber date of birth'},
        {name: 'subscriberSS',                  type: 'string',comment:'subscriber social security'},
        {name: 'copay',                         type: 'string',comment:'default copay'},
        {name: 'type',                          type: 'string',comment:'0=inactive, 1=primary/secondary, 2=supplemental/tertiary'}
	],
	proxy : {
		type: 'direct',
		api : {
			read: Patient.getPatient,
			create: Patient.addPatient,
			update: Patient.updatePatient
		}
	}
});