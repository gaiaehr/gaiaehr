Ext.define('App.controller.patient.encounter.SOAP', {
	extend: 'Ext.app.Controller',
	refs: [
		{
			ref: 'soapPanel',
			selector: 'panel[action="patient.encounter.soap"]'
		}
	],

	init: function() {

		this.control({
			'panel[action="patient.encounter.soap"]': {
				render: this.onPanelRender
			}
		});
	},

	onPanelRender: function() {

		say('SOAP render controller test');

	}


});