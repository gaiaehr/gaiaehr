Ext.define('App.controller.patient.encounter.SOAP', {
	extend: 'Ext.app.Controller',


	// defaults
	recognition: null,
	speechAction: null,
	recognizing: false,
	isError: false,

	final_transcript: '',
	interim_transcript: '',


	refs: [
		{
			ref: 'Viewport',
			selector: 'viewport'
		},
		{
			ref: 'SoapPanel',
			selector: '#soapPanel'
		},
		{
			ref: 'SoapForm',
			selector: '#soapPanel #soapForm'
		},
		{
			ref: 'SoapProcedureWindow',
			selector: '#soapProcedureWindow'
		},
		{
			ref: 'SoapProcedureForm',
			selector: '#soapProcedureWindow > form'
		},
		{
			ref: 'TemplatesTreePanel',
			selector: '#soapPanel #templatesTreePanel'
		},
		{
			ref: 'SpeechBtn',
			selector: '#soapPanel button[action=speechBtn]'
		},
		{
			ref: 'EncounterProgressNotesPanel',
			selector: '#EncounterProgressNotesPanel'
		}
	],

	init: function() {

		this.control({
			'#soapPanel': {
				beforerender: this.onPanelBeforeRender,
				activate: this.onPanelActive,
				deactivate: this.onPanelDeActive
			},
			'#soapPanel button[action=speechBtn]': {
				toggle: this.onSpeechBtnToggle
			},
			'#soapForm > fieldset > textarea': {
				focus: this.onSoapTextFieldFocus
			},
			'#soapProcedureWindow > form > textarea': {
				focus: this.onProcedureTextFieldFocus
			}
		});
	},

	onPanelActive: function(){
		var me = this;
		Ext.Function.defer(function(){
			me.getEncounterProgressNotesPanel().expand();
		}, 200);
	},

	onPanelDeActive: function(){
		var me = this;
		Ext.Function.defer(function(){
			me.getEncounterProgressNotesPanel().collapse();
		}, 200);
	},

	onSoapTextFieldFocus: function(field) {
		this.loadTemplatesByCategory(field.name);

		if(!Ext.isWebKit) return;
		this.field = field;
		this.final_transcript = field.getValue();
		this.interim_transcript = '';
	},

	onProcedureTextFieldFocus: function(field) {
		this.loadTemplatesByCategory(field.name);

		if(!Ext.isWebKit) return;
		this.field = field;
		this.final_transcript = field.getValue();
		this.interim_transcript = '';
	},

	loadTemplatesByCategory:function(category){

		if(this.getTemplatesTreePanel().collapsed === false){
			var templates = this.getTemplatesTreePanel();

			templates.setTitle(i18n(category) + ' ' + i18n('templates'));
			if(templates.action != category){
				templates.getSelectionModel().deselectAll();
				templates.getStore().load({
					params: {
						category: category
					}
				});
			}
			templates.action = category;
		}
	},

	onPanelBeforeRender: function(panel) {
		if(!Ext.isWebKit) return;

		var btn = [{
			xtype:'button',
			action: 'speechBtn',
			iconCls: 'speech-icon-inactive',
			enableToggle: true,
			minWidth: null
		}, { xtype: 'tbfill' }];

		panel.down('form').getDockedItems('toolbar[dock="bottom"]')[0].insert(0, btn);
	},

	onSpeechBtnToggle: function(btn, pressed) {
		if(pressed){
			this.initSpeech();
		}else{
			this.stopSpeech();
		}
	},

	stopSpeech:function(){
		this.recognition.stop();
		this.final_transcript = '';
		this.interim_transcript = '';
		delete this.recognition;
	},

	initSpeech:function(){
		var me = this;
		if(me.recognition) me.stopSpeech();
		me.final_transcript  = me.field.getValue();
		me.recognition = new webkitSpeechRecognition();
		me.recognition.continuous = true;
		me.recognition.interimResults = true;
		me.recognition.lang = app.user.localization;

		me.recognition.onstart = function(){
			say('onstart');
			me.recognizing = true;
			me.setRecordButton(true);
		};

		me.recognition.onerror = function(event){
			me.isError = true;
			me.setRecordButton(false);
		};

		me.recognition.onend = function(){
			me.setRecordButton(false);
		};

		me.recognition.onresult = function(event){
			me.interim_transcript = '';
			for(var i = event.resultIndex; i < event.results.length; ++i){
				if(event.results[i].isFinal){
					me.final_transcript += event.results[i][0].transcript;
				}else{
					me.interim_transcript += event.results[i][0].transcript;
				}
			}

			me.field.setValue(me.final_transcript);
		};

		me.recognition.start();
	},

	setRecordButton:function(recording){
		this.getSpeechBtn().setIconCls( recording ? 'speech-icon-active' : 'speech-icon-inactive');
	}

});