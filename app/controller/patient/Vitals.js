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

Ext.define('App.controller.patient.Vitals', {
	extend: 'Ext.app.Controller',
	refs: [
		{
			ref: 'VitalsPanel',
			selector: 'vitalspanel'
		},
		{
			ref: 'VitalsBlocksPanel',
			selector: 'vitalspanel #vitalsBlocks'
		},
		{
			ref: 'VitalsBlocksPanel',
			selector: 'vitalspanel #vitalsBlocks'
		},
		{
			ref: 'VitalsHistoryGrid',
			selector: 'vitalspanel #historyGrid'
		},
		{
			ref: 'VitalsAddBtn',
			selector: 'vitalspanel #vitalAddBtn'
		},
		{
			ref: 'VitalSignBtn',
			selector: 'vitalspanel #vitalSignBtn'
		},
		//
		{
			ref: 'VitalTempFField',
			selector: '#vitalTempFField'
		},
		{
			ref: 'VitalTempCField',
			selector: '#vitalTempCField'
		},
		{
			ref: 'VitalHeightInField',
			selector: '#vitalHeightInField'
		},
		{
			ref: 'VitalHeightCmField',
			selector: '#vitalHeightCmField'
		},
		{
			ref: 'VitalWeightKgField',
			selector: '#vitalWeightKgField'
		},
		{
			ref: 'VitalWeightLbsField',
			selector: '#vitalWeightLbsField'
		},

		// blocks
		{
			ref: 'BpBlock',
			selector: 'vitalspanel #bpBlock'
		},
		{
			ref: 'TempBlock',
			selector: 'vitalspanel #tempBlock'
		},
		{
			ref: 'WeighBlock',
			selector: 'vitalspanel #weighBlock'
		},
		{
			ref: 'HeightBlock',
			selector: 'vitalspanel #heightBlock'
		},
		{
			ref: 'BmiBlock',
			selector: 'vitalspanel #bmiBlock'
		},
		{
			ref: 'NotesBlock',
			selector: 'vitalspanel #notesBlock'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'viewport': {
				beforeencounterload: me.onAppBeforeEncounterLoad
			},
			'vitalspanel #historyGrid': {
				selectionchange: me.onHistoryGridSelectionChange,
				beforeselect: me.onHistoryGridBeforeSelect,
				beforeedit: me.onHistoryGridBeforeEdit,
				validateedit: me.onHistoryGridValidEdit,
				edit: me.onHistoryGridEdit
			},
			'vitalspanel #vitalAddBtn': {
				click: me.onVitalAddBtnClick
			},
			'vitalspanel #vitalSignBtn': {
				click: me.onVitalSignBtnClick
			},


			/** conversions **/
			'#vitalTempFField':{
				keyup:me.onVitalTempFFieldKeyUp
			},
			'#vitalTempCField':{
				keyup:me.onVitalTempCFieldKeyUp
			},
			'#vitalHeightInField':{
				keyup:me.onVitalHeightInFieldKeyUp
			},
			'#vitalHeightCmField':{
				keyup:me.onVitalHeightCmFieldKeyUp
			},
			'#vitalWeightLbsField':{
				keyup:me.onVitalWeightLbsFieldKeyUp
			},
			'#vitalWeightKgField':{
				keyup:me.onVitalWeightKgFieldKeyUp
			}
		});
	},

	onAppBeforeEncounterLoad: function(record){
		if(this.getVitalsHistoryGrid()){
			if(record.vitalsStore){
				this.doReconfigureGrid(record.vitalsStore);
			}else{
				this.doReconfigureGrid(Ext.getStore('ext-empty-store'));
			}

		}
	},

	onHistoryGridSelectionChange: function(grid, records){
		var me = this,
			btn = me.getVitalSignBtn();

		this.doUpdateBlocks(records);
		if(records.length == 0 || records[0].data.auth_uid > 0){
			btn.disable();
		}else{
			btn.enable();
		}
	},

	onHistoryGridBeforeSelect: function(sm, record){
		var selected = sm.getSelection().length;

		if(selected > 0 && record.data.auth_uid > 0){
			say('entre false');
			app.msg(_('oops'),_('multi_select_signed_records_not_authorized'), true);
			return false;
		}

	},

	onHistoryGridValidEdit: function(plugin, context){
		var me = this,
			form = plugin.editor.getForm(),
			w = me.isMetric() ? form.findField('weight_kg').getValue() : form.findField('weight_lbs').getValue(),
			h = me.isMetric() ? form.findField('height_cm').getValue() : form.findField('height_in').getValue(),
			bmi = me.bmi(w, h),
			bmiStatus = me.bmiStatus(bmi);

		context.record.set({
			bmi: bmi,
			bmi_status: bmiStatus
		});
	},

	onHistoryGridEdit: function(plugin, context){
		this.doUpdateBlocks([context.record])
	},

	onHistoryGridBeforeEdit: function(plugin, context){
		if(context.record.data.auth_uid != 0){
			app.msg(_('oops'), _('this_record_can_not_be_modified_because_it_has_been_signed_by') + ' ' + context.record.data.authorized_by, true);
			return false;
		}
		return true;
	},

	onVitalAddBtnClick: function(btn){
		var grid = btn.up('grid'),
			store = grid.getStore(),
			records;

		grid.editingPlugin.cancelEdit();
		records = store.add({
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			date: new Date()
		});
		grid.editingPlugin.startEdit(records[0], 1);
	},

	onVitalSignBtnClick: function(){
		var me = this,
			grid = me.getVitalsHistoryGrid(),
			sm = grid.getSelectionModel(),
			records = sm.getSelection();

		app.fireEvent('beforevitalssigned', records);

		app.passwordVerificationWin(function(btn, password){
			if(btn == 'ok'){
				User.verifyUserPass(password, function(provider, response){
					if(response.result){
						for(var i = 0; i < records.length; i++){
							records[i].set({
								auth_uid: app.user.id
							});
						}
						records[0].store.sync({
							callback: function(){
								app.msg('Sweet!', _('vitals_signed'));
//								me.getProgressNote();
								app.AuditLog('Patient vitals authorized');

								app.fireEvent('vitalssigned', records);
							}
						});
					}else{
						Ext.Msg.show({
							title: 'Oops!',
							msg: _('incorrect_password'),
							buttons: Ext.Msg.OKCANCEL,
							icon: Ext.Msg.ERROR,
							fn: function(btn){
								if(btn == 'ok'){
									me.onVitalSignBtnClick();
								}
							}
						});
					}
				});
			}
		});

	},

	doUpdateBlocks: function(records){
		var me = this;
		if(records.length > 0){
			me.getBpBlock().update(me.getBlockTemplate('bp', records[0]));
			if(me.isMetric()){
				me.getTempBlock().update(me.getBlockTemplate('temp_c', records[0]));
				me.getWeighBlock().update(me.getBlockTemplate('weight_kg', records[0]));
				me.getHeightBlock().update(me.getBlockTemplate('height_cm', records[0]));
			}else{
				me.getTempBlock().update(me.getBlockTemplate('temp_f', records[0]));
				me.getWeighBlock().update(me.getBlockTemplate('weight_lbs', records[0]));
				me.getHeightBlock().update(me.getBlockTemplate('height_in', records[0]));
			}
			me.getBmiBlock().update(me.getBlockTemplate('bmi', records[0]));
			me.getNotesBlock().update(me.getBlockTemplate('other_notes', records[0]));
		}else{
			me.getBpBlock().update(me.getBlockTemplate('bp', false));
			if(me.isMetric()){
				me.getTempBlock().update(me.getBlockTemplate('temp_c', false));
				me.getWeighBlock().update(me.getBlockTemplate('weight_kg', false));
				me.getHeightBlock().update(me.getBlockTemplate('height_cm', false));
			}else{
				me.getTempBlock().update(me.getBlockTemplate('temp_f', false));
				me.getWeighBlock().update(me.getBlockTemplate('weight_lbs', false));
				me.getHeightBlock().update(me.getBlockTemplate('height_in', false));
			}
			me.getBmiBlock().update(me.getBlockTemplate('bmi', false));
			me.getNotesBlock().update(me.getBlockTemplate('other_notes', false));
		}
	},

	getBlockTemplate: function(property, record){
		var title = '',
			value = '',
			extra = '',
			symbol = '',
			align = 'center';

		if(record !== false){
			if(property == 'bp'){
				title = _(property);
				value = (record.data.bp_systolic + '/' + record.data.bp_diastolic);
				value = value == 'null/null' || value == '/' ? '--/--' : value;
				extra = _('systolic') + '/' + _('diastolic');

			}else if(property == 'temp_c' || property == 'temp_f'){
				title = _('temp');
				symbol = property == 'temp_c' ? '&deg;C' : '&deg;F';
				value = record.data[property] == null || record.data[property] == '' ? '--' : record.data[property] + symbol;
				extra = record.data.temp_location == '' ? '--' : record.data.temp_location;

			}else if(property == 'weight_lbs' || property == 'weight_kg'){
				title = _('weight');
				//				symbol = property == 'weight_lbs' ? ' lbs' : ' kg';
				value = record.data[property] == null || record.data[property] == '' ? '--' : record.data[property] + symbol;
				extra = property == 'weight_lbs' ? 'lbs/oz' : 'Kg';

			}else if(property == 'height_in' || property == 'height_cm'){
				title = _('height');
				symbol = property == 'height_in' ? ' in' : ' cm';
				value = record.data[property] == null || record.data[property] == '' ? '--' : record.data[property] + symbol;

			}else if(property == 'bmi'){
				title = _(property);
				value = record.data[property] == null || record.data[property] == '' ? '--' : record.data[property];
				extra = record.data.bmi_status == '' ? '--' : record.data.bmi_status;

			}else if(property == 'other_notes'){
				title = _('notes');
				value = record.data[property] == null || record.data[property] == '' ? '--' : record.data[property];
				align = 'left'
			}
		}else{
			if(property == 'temp_c' || property == 'temp_f'){
				title = _('temp');
			}else if(property == 'weight_lbs' || property == 'weight_kg'){
				title = _('weight');
			}else if(property == 'height_in' || property == 'height_cm'){
				title = _('height');
			}else if(property == 'other_notes'){
				title = _('notes');
				align = 'left'
			}else{
				title = _(property);
			}
			value = property == 'bp' ? '--/--' : '--';
			extra = '--';
		}

		return '<p class="title">' + title + '</p><p class="value" style="text-align: ' + align + '">' + value + '</p><p class="extra">' + extra + '</p>';
	},

	doReconfigureGrid: function(store){
		var me = this;
		store.sort([
			{
				property: 'date',
				direction: 'DESC'
			}
		]);
		store.on('write', me.onVitalStoreWrite, me);
		me.getVitalsHistoryGrid().reconfigure(store);
		me.getVitalsHistoryGrid().getSelectionModel().select(0);
	},

	onVitalStoreWrite:function(store, operation, e){
		app.fireEvent('vitalwrite', store, operation, e);
	},

	onVitalTempFFieldKeyUp:function(field){
		field.up('form').getForm().getRecord().set({temp_c: this.fc(field.getValue())});
	},

	onVitalTempCFieldKeyUp:function(field){
		field.up('form').getForm().getRecord().set({temp_f: this.cf(field.getValue())});
	},

	onVitalHeightInFieldKeyUp:function(field){
		field.up('form').getForm().getRecord().set({height_cm: this.incm(field.getValue())});
	},

	onVitalHeightCmFieldKeyUp:function(field){
		field.up('form').getForm().getRecord().set({height_in: this.cmin(field.getValue())});
	},

	onVitalWeightLbsFieldKeyUp:function(field){
		field.up('form').getForm().getRecord().set({weight_kg: this.lbskg(field.getValue())});
	},

	onVitalWeightKgFieldKeyUp:function(field){
		field.up('form').getForm().getRecord().set({weight_lbs: this.kglbs(field.getValue())});
	},


	/** Conversions **/

	/**
	 * Convert Celsius to Fahrenheit
	 * @param v
	 */
	cf: function(v){
		return Ext.util.Format.round((9 * v / 5 + 32), 1);
	},

	/**
	 * Convert Fahrenheit to Celsius
	 * @param v
	 */
	fc: function(v){
		return Ext.util.Format.round(((v - 32) * 5 / 9), 1);
	},

	/**
	 * Convert Lbs to Kg
	 * @param v
	 */
	lbskg: function(v){
		var lbs = v[0] || 0,
			oz = v[1] || 0,
			kg = 0,
			res;
		if(lbs > 0) kg = kg + (lbs / 2.2046);
		if(oz > 0) kg = kg + (oz / 35.274);
		return Ext.util.Format.round(kg, 1);
	},

	/**
	 * Convert Kg to Lbs
	 * @param v
	 */
	kglbs: function(v){
		return Ext.util.Format.round((v * 2.2046), 1);
	},

	/**
	 * Convert Inches to Centimeter
	 * @param v
	 */
	incm: function(v){
		return Math.floor(v * 2.54);
	},

	/**
	 * Convert Centimeter to Inches
	 * @param v
	 */
	cmin: function(v){
		return  Ext.util.Format.round((v / 2.54), 0);
	},

	/**
	 * Get BMI from weight and height
	 * @param weight
	 * @param height
	 * @returns {*}
	 */
	bmi: function(weight, height){
		var bmi = '',
			foo = weight.split('/');

		if(foo.length > 1){
			weight = eval(foo[0]) + (foo[1] / 16);
		}

		if(weight > 0 && height > 0){
			if(!this.isMetric()){
				bmi = weight / (height * height) * 703;
			}else{
				bmi = weight / ((height / 100) * (height / 100));
			}
		}

		return bmi.toFixed ? bmi.toFixed(1) : bmi;
	},

	bmiStatus:function(bmi){
		var status = '';
		if(bmi == '') return '';
		if(bmi < 15){
			status = _('very_severely_underweight')
		}else if(bmi >= 15 && bmi < 16){
			status = _('severely_underweight')
		}else if(bmi >= 16 && bmi < 18.5){
			status = _('underweight')
		}else if(bmi >= 18.5 && bmi < 25){
			status = _('normal')
		}else if(bmi >= 25 && bmi < 30){
			status = _('overweight')
		}else if(bmi >= 30 && bmi < 35){
			status = _('obese_class_1')
		}else if(bmi >= 35 && bmi < 40){
			status = _('obese_class_2')
		}else if(bmi >= 40){
			status = _('obese_class_3')
		}
		return status;
	},

	/**
	 * return true if units of measurement is metric
	 */
	isMetric:function(){
		return g('units_of_measurement') == 'metric';
	}

});