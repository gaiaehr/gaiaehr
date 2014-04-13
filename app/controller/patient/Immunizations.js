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

Ext.define('App.controller.patient.Immunizations', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'ImmunizationPanel',
			selector: 'patientimmunizationspanel'
		},
		{
			ref: 'ImmunizationsGrid',
			selector: 'patientimmunizationspanel #patientImmunizationsGrid'
		},
		{
			ref: 'CvxGrid',
			selector: 'patientimmunizationspanel #cvxGrid'
		},
		{
			ref: 'CvxMvxCombo',
			selector: 'cvxmanufacturersforcvxcombo'
		},
		{
			ref: 'AddImmunizationBtn',
			selector: 'patientimmunizationspanel #addImmunizationBtn'
		},
		{
			ref: 'ReviewImmunizationsBtn',
			selector: 'patientimmunizationspanel #reviewImmunizationsBtn'
		},
		{
			ref: 'SubmitVxuBtn',
			selector: 'patientimmunizationspanel #submitVxuBtn'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'patientimmunizationspanel':{
				activate: me.onPatientImmunizationsPanelActive
			},
			'patientimmunizationspanel #patientImmunizationsGrid':{
				selectionchange: me.onPatientImmunizationsGridSelectionChange
			},
			'patientimmunizationspanel #cvxGrid':{
				expand: me.onCvxGridExpand
			},
			'patientimmunizationspanel #submitVxuBtn':{
				click: me.onSubmitVxuBtnClick
			},
			'patientimmunizationspanel #reviewImmunizationsBtn':{
				click: me.onReviewImmunizationsBtnClick
			},
			'patientimmunizationspanel #addImmunizationBtn':{
				click: me.onAddImmunizationBtnClick
			},
			'form #immunizationsearch':{
				select: me.onImmunizationSearchSelect
			}
		});
	},

	onImmunizationSearchSelect:function(combo, record){
		var form =  combo.up('form').getForm();

		this.getCvxMvxCombo().getStore().load({
			params:{
				cvx_code: record[0].data.cvx_code
			}
		});
		form.getRecord().set({
			code: record[0].data.cvx_code,
			code_type: 'CVX'
		});
	},

	onCvxGridExpand:function(grid){
		grid.getStore().load();
	},

	onPatientImmunizationsGridSelectionChange:function(sm, selected){
		this.getSubmitVxuBtn().setDisabled(selected.length == 0);
	},

	onPatientImmunizationsPanelActive:function(){
		this.loadPatientImmunizations();
	},

	onSubmitVxuBtnClick:function(){
		var me = this,
			selected = me.getImmunizationsGrid().getSelectionModel().getSelection(),
			immunizations = [];

		me.vxuWindow = me.getVxuWindow();

		for(var i=0; i < selected.length; i++){
			immunizations.push(selected[i].data);
		}

		me.vxuWindow.getComponent('list').update(immunizations);
	},

	onReviewImmunizationsBtnClick:function(){

	},

	onAddImmunizationBtnClick:function(){
		var grid = this.getImmunizationsGrid(),
			store = grid.getStore();

		grid.editingPlugin.cancelEdit();
		store.insert(0, {
			created_uid: app.user.id,
			uid: app.user.id,
			pid: app.patient.pid,
			eid: app.patient.eid,
			create_date: new Date(),
			begin_date: new Date()

		});
		grid.editingPlugin.startEdit(0, 0);
	},

	loadPatientImmunizations:function(){
		var store = this.getImmunizationsGrid().getStore();
		store.clearFilter(true);
		store.filter([
			{
				property: 'pid',
				value: app.patient.pid
			}
		]);
	},

	getVxuWindow: function(){
		var me = this;
		return Ext.widget('window',{
			title: i18n('submit_hl7_vxu'),
			closable: false,
			modal: true,
			bodyStyle:'background-color:white',
			defaults:{
				xtype:'container',
				border:false,
				margin:10
			},
			items:[
				{
					html: i18n('please_verify_the_information')+':',
					margin: '10 10 0 10'
				},
				{
					width:700,
					minHeight:50,
					maxHeight:200,
					itemId:'list',
					margin:'0 10 20 10',
					styleHtmlContent: true,
					tpl: new Ext.XTemplate(
						'<ul>',
						'<tpl for=".">',     // interrogate the kids property within the data
						'   <li>CVX:{code} - {vaccine_name} {administer_amount} {administer_units} {date_administered}</li>',
							'</tpl>' +
							'</ul>'
					)
				}
			],
			buttons:[
				me.vxuFrom = Ext.create('App.ux.combo.ActiveFacilities',{
					fieldLabel: i18n('send_from'),
					emptyText: i18n('select'),
					labelWidth: 60,
					store: Ext.create('App.store.administration.HL7Recipients',{
						filters:[
							{
								property:'active',
								value:true
							}
						]
					})
				}),
				me.vxuTo = Ext.widget('combobox',{
					xtype:'combobox',
					fieldLabel: i18n('send_to'),
					emptyText: i18n('select'),
					allowBlank: false,
					forceSelection: true,
					labelWidth: 60,
					displayField: 'recipient_application',
					valueField: 'id',
					store: Ext.create('App.store.administration.HL7Recipients',{
						filters:[
							{
								property:'active',
								value:true
							}
						]
					})
				}),
				{
					text: i18n('send'),
					scope: me,
					handler: me.doSendVxu
				},
				{
					text:i18n('cancel'),
					handler:function(){
						me.vxuWindow.close();
					}
				}
			]
		}).show();
	},

	doSendVxu:function(){
		var me = this,
			sm = me.getImmunizationsGrid().getSelectionModel(),
			foo = sm.getSelection(),
			params = {},
			immunizations = [];

		if(me.vxuTo.isValid()){

			for(var i=0; i < foo.length; i++){
				immunizations.push(foo[i].data.id);
			}

			params.pid = me.pid;
			params.from = me.vxuFrom.getValue();
			params.to = me.vxuTo.getValue();
			params.immunizations = immunizations;

			me.vxuWindow.el.mask(i18n('sending'));

			HL7Messages.sendVXU(params, function(provider, response){
				me.vxuWindow.el.unmask();
				if(response.result.success){
					app.msg(i18n('sweet!'), i18n('message_sent'));
				}else{
					app.msg(i18n('oops!'), i18n('message_error'), true);
				}
				me.vxuWindow.close();
				sm.deselectAll();
			});
		}
	}

});