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

Ext.define('App.controller.patient.ItemsToReview', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'ItemsToReviewPanel',
			selector: '#ItemsToReviewPanel'
		},
		{
			ref: 'ItemsToReviewImmuGrid',
			selector: '#ItemsToReviewPanel #ItemsToReviewImmuGrid'
		},
		{
			ref: 'ItemsToReviewAllergiesGrid',
			selector: '#ItemsToReviewPanel #ItemsToReviewAllergiesGrid'
		},
		{
			ref: 'ItemsToReviewActiveProblemsGrid',
			selector: '#ItemsToReviewPanel #ItemsToReviewActiveProblemsGrid'
		},
		{
			ref: 'ItemsToReviewMedicationsGrid',
			selector: '#ItemsToReviewPanel #ItemsToReviewMedicationsGrid'
		},
		{
			ref: 'ReviewSmokingStatusCombo',
			selector: '#ItemsToReviewPanel #reviewsmokingstatuscombo'
		}

	],

	init: function(){
		var me = this;
		me.control({
			'#ItemsToReviewPanel':{
				activate: me.storesLoad
			},
			'#encounterRecordAdd':{
				click: me.onReviewAll
			}
		});

	},

	storesLoad: function(){
		var me = this,
			params = {
				filters: [
					{
						property: 'pid',
						value: app.patient.pid
					}
				]
			};

		me.getReviewSmokingStatusCombo().reset();
		me.getItemsToReviewImmuGrid().getStore().load(params);
		me.getItemsToReviewAllergiesGrid().getStore().load(params);
		me.getItemsToReviewActiveProblemsGrid().getStore().load(params);
		me.getItemsToReviewMedicationsGrid().getStore().load(params);

		me.smokeStatusStore = app.getController('patient.Social').smokeStatusStore;

		/**
		 * add the callback function to handle the Smoking Status
		 */
		params.callback = function(){
			if(this.last()){
				me.getReviewSmokingStatusCombo().setValue(this.last().data.status);
			}
		};
		me.smokeStatusStore.load(params);

	},

	onReviewAll: function(){

		if(this.getReviewSmokingStatusCombo().isValid()){

			var encounter = this.getController('patient.encounter.Encounter').getEncounterRecord();

			encounter.set({
				review_active_problems: true,
				review_allergies: true,
				review_dental: true,
				review_immunizations: true,
				review_medications: true,
				review_smoke: true,
				review_surgery: true
			});

			encounter.save({
				success: function(){
					app.msg('Sweet!', _('items_to_review_save_and_review'));
				},
				failure: function(){
					app.msg('Oops!', _('items_to_review_entry_error'));
				}
			});
		}
	}



});