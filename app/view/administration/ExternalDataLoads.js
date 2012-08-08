/**
 * External Data Loads
 * v0.0.1
 *
 * Author: Ernesto J Rodriguez
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 */
Ext.define('App.view.administration.ExternalDataLoads', {
	extend       : 'App.classes.RenderPanel',
	id           : 'panelUpdateCodes',
	pageTitle    : 'External Data Loads',
	/**
	 * define the layout 'accordion'
	 * and few more configs
	 */
	pageLayout   : {
		type       : 'accordion',
		animate    : true,
		activeOnTop: true
	},
	initComponent: function() {
		var me = this;
		/**
		 * var stores is used to hold all the stores inside this class
		 * this way, if I want to reload all the stores at once, I can do it
		 * using a for loop
		 *
		 * see function loadStores()
		 *
		 * @type {Array}
		 */
		me.stores = [];

		me.stores.push(me.icd9Store = Ext.create('App.store.administration.ExternalDataLoads', {codeType: 'ICD9'}));
		me.stores.push(me.icd10Store = Ext.create('App.store.administration.ExternalDataLoads', {codeType: 'ICD10'}));
		me.stores.push(me.rxnormStore = Ext.create('App.store.administration.ExternalDataLoads', {codeType: 'RXNORM'}));
		me.stores.push(me.snomedStore = Ext.create('App.store.administration.ExternalDataLoads', {codeType: 'SNOMED'}));

		/**
		 * Since all the grid are very similar I created a function that return a grid
		 */
		me.icd9Grid = me.getCodeGrid('Available ICD9 Data', me.icd9Store);
		me.icd10Grid = me.getCodeGrid('Available ICD10 Data', me.icd10Store);
		me.rxnormGrid = me.getCodeGrid('Available RxNorm Data', me.rxnormStore);
		me.snomedGrid = me.getCodeGrid('Available SNOMED Data', me.snomedStore);

		/**
		 * Same thing with the forms
		 */
		me.icd9Form = me.getCodeForm('ICD9');
		me.icd10Form = me.getCodeForm('ICD10');
		me.rxnormForm = me.getCodeForm('RXNORM');
		me.snomedForm = me.getCodeForm('SNOMED');

		/**
		 * Here are the panels used inside the accordion layout
		 */
		me.icd9 = Ext.create('Ext.form.Panel', {
			title : 'Update ICD9',
			layout: 'border',
			items : [ me.icd9Grid, me.icd9Form ]
		});

		me.icd10 = Ext.create('Ext.panel.Panel', {
			title : 'Update ICD10',
			layout: 'border',
			items : [ me.icd10Grid, me.icd10Form ]
		});

		me.rxnorm = Ext.create('Ext.panel.Panel', {
			title : 'Update RxNorm',
			layout: 'border',
			items : [ me.rxnormGrid, me.rxnormForm ]
		});

		me.snomed = Ext.create('Ext.panel.Panel', {
			title : 'Update SNOMED',
			layout: 'border',
			items : [ me.snomedGrid, me.snomedForm ]
		});

		me.pageBody = [ me.icd9, me.icd10, me.rxnorm, me.snomed ];
		me.callParent(arguments);
	},

	getCodeForm: function(action) {
		var me = this;
		return Ext.create('Ext.form.Panel', {
			bodyPadding: 10,
			region     : 'center',
			action     : action,
			frame      : true,
			bodyStyle  : 'background-color:white',
			bodyBorder : true,
			margin     : '5 0 5 0',
			items      : [
				{
					xtype: 'fieldset',
					title: 'Current Version Installed',
					html : 'None'
				},
				{
					xtype: 'fieldset',
					title: 'Installation Details',
					html : me.getInstallationDetails(action)
				},
				{
					xtype     : 'filefield',
					name      : 'filePath',
					buttonText: 'Select file...',
					emptyText : 'Data File',
					width     : 350,
					labelWidth: 50,
					allowBlank: false
				}
			],
			api        : {
				submit: Codes.updateCodesWithUploadFile
			},
			buttons    : [
				{
					text   : 'Update',
					action : action,
					scope  : me,
					handler: me.uploadFile
				}
			]
		});
	},

	getCodeGrid: function(title, store) {
		var me = this;
		return Ext.create('Ext.grid.Panel', {
			title  : title,
			store  : store,
			region : 'west',
			width  : 500,
			margin : '5 0 5 0',
			padding: 0,
			split  : true,
			columns: me.getDefaultColumns()
		});
	},

	getDefaultColumns: function() {
		return [
			{
				header   : 'Date',
				dataIndex: 'date',
				width    : 98
			},
			{
				header   : 'Version',
				dataIndex: 'version',
				width    : 98
			},
			{
				header   : 'File',
				dataIndex: 'basename',
				width    : 300
			}
		];
	},

	getInstallationDetails: function(action) {
		if(action == 'ICD9') {
			return 'Lorem ipsum dolor sit amet, porta nam suscipit sed id, ' +
				'vestibulum velit tortor velit viverra, non enim justo, ' +
				'purus nisl risus nibh, cras magnis sed erat magna ' +
				'tristique commodo. Dapibus ut nulla amet massa congue leo. ' +
				'Integer phasellus congue urna pellentesque. Vestibulum quis, ' +
				'placerat suscipit quis porta malesuada, ut elementum venenatis ' +
				'suscipit nunc. Mauris fringilla suspendisse lectus faucibus, ' +
				'purus nec, libero sociis lobortis, eu et leo mauris velit. ' +
				'Magnis tellus blandit fringilla, morbi mauris commodo, nec morbi ac non'
		} else if(action == 'ICD10') {
			return 'Lorem ipsum dolor sit amet, porta nam suscipit sed id, ' +
				'vestibulum velit tortor velit viverra, non enim justo, ' +
				'purus nisl risus nibh, cras magnis sed erat magna ' +
				'tristique commodo. Dapibus ut nulla amet massa congue leo. ' +
				'Integer phasellus congue urna pellentesque. Vestibulum quis, ' +
				'placerat suscipit quis porta malesuada, ut elementum venenatis ' +
				'suscipit nunc. Mauris fringilla suspendisse lectus faucibus, '
		} else if(action == 'RXNORM') {
			return 'Lorem ipsum dolor sit amet, porta nam suscipit sed id, ' +
				'placerat suscipit quis porta malesuada, ut elementum venenatis ' +
				'suscipit nunc. Mauris fringilla suspendisse lectus faucibus, ' +
				'purus nec, libero sociis lobortis, eu et leo mauris velit. ' +
				'Magnis tellus blandit fringilla, morbi mauris commodo, nec morbi ac non'
		} else if(action == 'SNOMED') {
			return 'Lorem ipsum dolor sit amet, porta nam suscipit sed id, ' +
				'vestibulum velit tortor velit viverra, non enim justo, ' +
				'purus nec, libero sociis lobortis, eu et leo mauris velit. ' +
				'Magnis tellus blandit fringilla, morbi mauris commodo, nec morbi ac non'
		}

	},

	uploadFile: function(btn, e) {
		var me = this,
			form = btn.up('form').getForm();

		if(form.isValid()) {
			form.submit({
				waitMsg: 'Uploading And Updating Code Database...',
				scope  : me,
				params : {
					codeType: btn.action
				},
				success: function(fp, o) {
					say(o.result);
				},
				failure: function(fp, o) {
					say(o.result);
				}
			});
		}
	},

	loadStores: function() {
		var me = this;
		for(var i = 0; i < me.stores.length; i++) {
			me.stores[i].load({params: {pid: me.pid}});
		}
	},

	/**
	 * This function is called from MitosAPP.js when
	 * this panel is selected in the navigation panel.
	 * place inside this function all the functions you want
	 * to call every this panel becomes active
	 */
	onActive: function(callback) {
		this.loadStores();
		callback(true);
	}
}); //ens servicesPage class