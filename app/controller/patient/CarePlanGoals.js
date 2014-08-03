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

Ext.define('App.controller.patient.CarePlanGoals', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'CarePlanGoalsGrid',
			selector: 'careplangoalsgrid'
		},
		{
			ref: 'CarePlanGoalsNewWindow',
			selector: 'careplangoalsnewwindow'
		},
		{
			ref: 'CarePlanGoalsNewForm',
			selector: '#CarePlanGoalsNewForm'
		},
		{
			ref: 'CarePlanGoalPlanDateField',
			selector: '#CarePlanGoalPlanDateField'
		},
		{
			ref: 'NewCarePlanGoalBtn',
			selector: '#NewCarePlanGoalBtn'
		},
		{
			ref: 'SoapPanelForm',
			selector: '#soapPanel form'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'viewport': {
				'beforeencounterload': me.onBeforeOpenEncounter
			},
			'careplangoalsgrid': {
				'itemdblclick': me.onCarePlanGoalsGridItemDblClick
			},
			'#CarePlanGoalSearchField': {
				'select': me.onCarePlanGoalSearchFieldSelect
			},
			'#NewCarePlanGoalBtn': {
				'click': me.onNewCarePlanGoalBtnClick
			},
			'#CarePlanGoalsNewFormCancelBtn': {
				'click': me.onCarePlanGoalsNewFormCancelBtn
			},
			'#CarePlanGoalsNewFormSaveBtn': {
				'click': me.onCarePlanGoalsNewFormSaveBtn
			},
			'#CarePlanGoalPlanDateContainer > button': {
				'click': me.onCarePlanGoalPlanDateContainerButtonsClick
			}
		});
	},

	onCarePlanGoalPlanDateContainerButtonsClick: function(btn){
		var now = new Date(),
			field = this.getCarePlanGoalPlanDateField(),
			date;

		switch (btn.action){
			case '1D':
				date = Ext.Date.add(now, Ext.Date.DAY, 1);
				break;
			case '1W':
				date = Ext.Date.add(now, Ext.Date.DAY, 7);
				break;
			case '2W':
				date = Ext.Date.add(now, Ext.Date.DAY, 14);
				break;
			case '1M':
				date = Ext.Date.add(now, Ext.Date.MONTH, 1);
				break;
			case '3M':
				date = Ext.Date.add(now, Ext.Date.MONTH, 3);
				break;
			case '6M':
				date = Ext.Date.add(now, Ext.Date.MONTH, 6);
				break;
			case '1Y':
				date = Ext.Date.add(now, Ext.Date.YEAR, 1);
				break;
		}

		field.setValue(date || now);

	},

	onCarePlanGoalsGridItemDblClick: function(grid, record){
		var me = this;
		me.getCarePlanGoalsNewWindow().setTitle(record.data.goal + ' [' + record.data.goal_code + ']');
		me.getCarePlanGoalsNewWindow().show(me.getCarePlanGoalsGrid().el);
		me.getCarePlanGoalsNewForm().getForm().loadRecord(record);
	},

	onCarePlanGoalSearchFieldSelect: function(cmb, records){
		var me = this,
			form = me.getCarePlanGoalsNewForm().getForm(),
			record = form.getRecord();

		record.set({
			'goal_code': records[0].data.ConceptId,
			'goal_code_type': records[0].data.CodeType
		});
	},

	onBeforeOpenEncounter: function(encounter){
		this.getCarePlanGoalsGrid().getStore().load({
			filters:[
				{
					property: 'eid',
					value: encounter.data.eid
				}
			]
		});
	},

	onNewCarePlanGoalBtnClick: function(btn){
		var me = this,
			grid = btn.up('grid'),
			store = grid.getStore(),
			records;

		records = store.add({
			pid: app.patient.pid,
			eid: app.patient.eid,
			uid: app.user.id,
			created_date: new Date()
		});

		me.getCarePlanGoalsNewWindow().setTitle(i18n('new_goal'));
		me.getCarePlanGoalsNewWindow().show(me.getCarePlanGoalsGrid().el);
		me.getCarePlanGoalsNewForm().getForm().loadRecord(records[0]);

	},

	onCarePlanGoalsNewFormSaveBtn:function(btn){
		var me = this,
			form = me.getCarePlanGoalsNewForm().getForm(),
			record = form.getRecord(),
			values = form.getValues();

		if(form.isValid()){

			record.set(values);
			record.store.sync({
				success:function(){
					app.msg(i18n('sweet'), i18n('record_saved'));
				},
				failure:function(){
					app.msg(i18n('oops'), i18n('record_error'), true);
				}
			});

			me.getCarePlanGoalsNewForm().getForm().reset();
			me.getCarePlanGoalsNewWindow().close();
		}

	},

	onCarePlanGoalsNewFormCancelBtn:function(btn){
		var me = this;
		me.getCarePlanGoalsGrid().getStore().rejectChanges();
		me.getCarePlanGoalsNewForm().getForm().reset();
		me.getCarePlanGoalsNewWindow().close();

	}


});