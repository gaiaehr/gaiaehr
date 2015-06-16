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

Ext.define('App.controller.administration.TemplatePanels', {
	extend: 'Ext.app.Controller',

	requires: [],

	refs: [
		{
			ref: 'TemplatePanelsWindow',
			selector: '#TemplatePanelsWindow'
		},
		{
			ref: 'TemplatePanelsGrid',
			selector: '#TemplatePanelsGrid'
		},
		{
			ref: 'TemplatePanelsCombo',
			selector: '#TemplatePanelsCombo'
		},
		{
			ref: 'SoapTemplatesBtn',
			selector: '#SoapTemplatesBtn'
		},
		{
			ref: 'encounterPanel',
			selector: '#encounterPanel'
		},
		{
			ref: 'soapPanel',
			selector: '#soapPanel'
		},
		{
			ref: 'soapForm',
			selector: '#soapForm'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'viewport': {
				encounterload: me.onEncounterLoad
			},
			'#soapPanel': {
				activate: me.onSoapPanelActivate,
				afterrender: me.onSoapPanelAfterRender
			},
			'#TemplatePanelsCombo': {
				select: me.onTemplatePanelsComboSelect
			},
			'#SoapTemplatesBtn': {
				click: me.onSoapTemplatesBtnClick
			},
			'#TemplatePanelsAddBtn': {
				click: me.onTemplatePanelsAddBtnClick
			},
			'#TemplatePanelsCancelBtn': {
				click: me.onTemplatePanelsCancelBtnClick
			}
		});

	},

	onEncounterLoad: function(encounter){

		if(!this.getTemplatePanelsWindow()){
			Ext.create('App.view.patient.windows.TemplatePanels');
		}

		var me = this,
			store = me.getTemplatePanelsCombo().getStore();

		store.load({
			filters: [
				{
					property: 'specialty_id',
					value: encounter.get('specialty_id')
				},
				{
					property: 'active',
					value: 1
				}
			]
		});
	},

	onSoapPanelAfterRender: function(){
		this.getSoapForm().getDockedItems('toolbar[dock="bottom"]')[0].insert(0,{
			xtype: 'button',
			text: _('templates'),
			itemId: 'SoapTemplatesBtn'
		});
	},

	onSoapPanelActivate: function(){
		var hasTemplates = this.getTemplatePanelsCombo().getStore().data.items.length > 0,
			btn = this.getSoapTemplatesBtn();

		if(hasTemplates){
			btn.disabled = false;
			btn.setDisabled(false);
			btn.setTooltip(_('clinical_templates'));
		}else{
			btn.disabled = true;
			btn.setDisabled(true);
			btn.setTooltip(_('no_templates_found'));
		}

	},

	onSoapTemplatesBtnClick: function(){
		this.doTemplatePanelsWindowShow();
	},

	onTemplatePanelsComboSelect: function(cmb, records){
		var me = this,
			grid = me.getTemplatePanelsGrid(),
			sm = grid.getSelectionModel(),
			store = records[0].templates();

		grid.reconfigure(store);
		store.load({
			callback: function(){
				sm.selectAll();
			}
		});
	},

	doTemplatePanelsWindowShow: function(){
		this.getTemplatePanelsGrid().getStore().removeAll();
		this.getTemplatePanelsCombo().reset();
		return this.getTemplatePanelsWindow().show();
	},

	onTemplatePanelsAddBtnClick: function(){
		var me = this,
			cmb = me.getTemplatePanelsCombo(),
			records = me.getTemplatePanelsGrid().getSelectionModel().getSelection();

		if(!cmb.isValid()) return;

		if(records.length === 0){
			app.msg(_('oops'), _('no_templates_to_add'), true);
			return;
		}

		Ext.Msg.show({
			title: _('wait'),
			msg: _('add_templates_message'),
			buttons: Ext.Msg.YESNO,
			icon: Ext.Msg.QUESTION,
			fn: function(btn){
				if(btn == 'yes'){
					me.doAddTemplates(records);
					me.getTemplatePanelsWindow().close();
				}
			}
		});
	},

	doAddTemplates: function(templates){

		for(var i = 0; i < templates.length; i++){

			var type = templates[i].get('template_type'),
				data = eval('(' + templates[i].data.template_data + ')');

			if(!data) {
				say('Error: data eval issue -- ' + templates[i].data.template_data);
				continue;
			}

			switch (type){

				case 'LAB':
					App.app.getController('patient.LabOrders').doAddOrderByTemplate(data);
					break;
				case 'RAD':
					App.app.getController('patient.RadOrders').doAddOrderByTemplate(data);
					break;
				case 'RX':
					App.app.getController('patient.RxOrders').doAddOrderByTemplate(data);
					break;
				default:
					say('Error: no template_type found -- ' + type);
					continue;
					break;
			}
		}
	},

	onTemplatePanelsCancelBtnClick: function(){
		this.getTemplatePanelsWindow().close();
	}

});