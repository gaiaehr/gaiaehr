Ext.define('App.controller.patient.encounter.SOAP', {
	extend: 'Ext.app.Controller',

	// defaults
	recognition: null,
	speechAction: null,
	recognizing: false,
	isError: false,

	final_transcript: '',
	interim_transcript: '',

	field: {
		name: 'subjective'
	},

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
			ref: 'SnippetsTreePanel',
			selector: '#soapPanel #SnippetsTreePanel'
		},
		{
			ref: 'SpeechBtn',
			selector: '#soapPanel button[action=speechBtn]'
		},
		{
			ref: 'EncounterProgressNotesPanel',
			selector: '#EncounterProgressNotesPanel'
		},
		{
			ref: 'SoapDxCodesField',
			selector: '#SoapDxCodesField'
		},

		// templates specialties combo
		{
			ref: 'SoapTemplateSpecialtiesCombo',
			selector: '#SoapTemplateSpecialtiesCombo'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'viewport': {
				'beforeencounterload': me.onOpenEncounter,
				'encounterbeforesync': me.onEncounterBeforeSync
			},
			'#soapPanel': {
				beforerender: me.onPanelBeforeRender
				//activate: me.onPanelActive,
				//deactivate: me.onPanelDeActive
			},
			'#soapPanel #soapForm': {
				render: me.onPanelFormRender
			},
			'#soapPanel button[action=speechBtn]': {
				toggle: me.onSpeechBtnToggle
			},
			'#soapForm > fieldset > textarea': {
				focus: me.onSoapTextFieldFocus
			},
			'#soapProcedureWindow > form > textarea': {
				focus: me.onProcedureTextFieldFocus
			},
			'#SoapTemplateSpecialtiesCombo': {
				select: me.onSoapTemplateSpecialtiesComboChange,
				change: me.onSoapTemplateSpecialtiesComboChange
			}
		});
	},

	onSoapTemplateSpecialtiesComboChange: function(cmb){
		this.loadSnippets();
	},

	onOpenEncounter: function(encounter){
		this.getSoapTemplateSpecialtiesCombo().setValue(encounter.data.specialty_id);
	},

	onEncounterBeforeSync: function(panel, store, form){
		if(form.owner.itemId == 'soapForm'){
			this.getSoapDxCodesField().sync();
		}
	},

	//onPanelActive: function(){
	//	var me = this;
	//	Ext.Function.defer(function(){
	//		me.getEncounterProgressNotesPanel().expand();
	//	}, 200);
	//},
	//
	//onPanelDeActive: function(){
	//	var me = this;
	//	Ext.Function.defer(function(){
	//		me.getEncounterProgressNotesPanel().collapse();
	//	}, 200);
	//},

	onSoapTextFieldFocus: function(field){
		this.field = field;
		this.loadSnippets();

		if(!Ext.isWebKit) return;
		this.final_transcript = field.getValue();
		this.interim_transcript = '';
	},

	onProcedureTextFieldFocus: function(field){
		this.field = field;
		this.loadSnippets();

		if(!Ext.isWebKit) return;
		this.final_transcript = field.getValue();
		this.interim_transcript = '';
	},

	loadSnippets: function(){
		var me = this;

		if(me.getSnippetsTreePanel().collapsed === false){
			var templates = me.getSnippetsTreePanel(),
				specialty_id = me.getSoapTemplateSpecialtiesCombo().getValue(),
				action = me.field.name + '-' + specialty_id;

			if(templates.action != action){

				templates.setTitle(_(me.field.name) + ' ' + _('templates'));
				templates.action = me.field.name + '-' + specialty_id;

				templates.getSelectionModel().deselectAll();
				templates.getStore().load({
					filters: [
						{
							property: 'category',
							value: me.field.name
						},
						{
							property: 'specialty_id',
							value: me.getSoapTemplateSpecialtiesCombo().getValue()
						},
						{
							property: 'parentId',
							value: 'root'
						}
					]
				});

			}

		}
	},

	onPanelBeforeRender: function(panel){
		if(!Ext.isWebKit) return;

		var btn = [
			{
				xtype: 'button',
				action: 'speechBtn',
				iconCls: 'speech-icon-inactive',
				enableToggle: true,
				minWidth: null
			},
			{ xtype: 'tbfill' }
		];

		panel.down('form').getDockedItems('toolbar[dock="bottom"]')[0].insert(0, btn);
	},

	onPanelFormRender: function(panel){
		Ext.widget('careplangoalsnewwindow', {
			constrainTo: panel.el.dom
		});
	},

	onSpeechBtnToggle: function(btn, pressed){
		if(pressed){
			this.initSpeech();
		}else{
			this.stopSpeech();
		}
	},

	stopSpeech: function(){
		this.recognition.stop();
		this.final_transcript = '';
		this.interim_transcript = '';
		delete this.recognition;
	},

	initSpeech: function(){
		var me = this;
		if(me.recognition) me.stopSpeech();
		me.final_transcript = me.field.getValue();
		me.recognition = new webkitSpeechRecognition();
		me.recognition.continuous = true;
		me.recognition.interimResults = true;
		me.recognition.lang = app.user.localization;

		me.recognition.onstart = function(){
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

	setRecordButton: function(recording){
		this.getSpeechBtn().setIconCls(recording ? 'speech-icon-active' : 'speech-icon-inactive');
	}

});
