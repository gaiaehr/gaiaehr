/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/15/12
 * Time: 4:30 PM
 *
 * @namespace Immunization.getImmunizationsList
 * @namespace Immunization.getPatientImmunizations
 * @namespace Immunization.addPatientImmunization
 */
Ext.define('App.view.patientfile.DocumentViewerWindow', {
	extend     : 'Ext.window.Window',
	title      : 'Documents Viewer Window',
	layout     : 'fit',
	height     : 650,
	width      : 500,
	closeAction: 'hide',
	bodyStyle  : 'background-color:#fff',
	modal      : true,
	defaults   : {
		margin: 5
	},
	mixins     : ['App.classes.RenderPanel'],

	initComponent: function() {
		var me = this;

		me.listeners = {
			scope: me,
			show : me.onViewerDocumentsWinShow
		};
		me.callParent(arguments);
	},


	onViewerDocumentsWinShow  : function() {



	}
});