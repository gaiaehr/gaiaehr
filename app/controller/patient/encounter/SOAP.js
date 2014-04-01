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
			selector: 'panel[action="patient.encounter.soap"]'
		},
		{
			ref: 'SpeechBtn',
			selector: 'panel[action="patient.encounter.soap"] button[action=speechBtn]'
		}
	],

	init: function() {

		this.control({
			'panel[action="patient.encounter.soap"]': {
				beforerender: this.onPanelBeforeRender
			},
			'panel[action="patient.encounter.soap"] button[action=speechBtn]': {
				toggle: this.onSpeechBtnToggle
			},
			'panel[action="patient.encounter.soap"] textfield': {
				focus: this.onTextFieldFocus
			}
		});
	},




	onTextFieldFocus: function(field) {
		if(!Ext.isWebKit) return;

		this.field = field;
		this.final_transcript = field.getValue();
		this.interim_transcript = '';
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