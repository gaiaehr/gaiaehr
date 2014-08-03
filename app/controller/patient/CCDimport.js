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

Ext.define('App.controller.patient.CCDimport', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'CcdImportWindow',
			selector: 'ccdimportwindow'
		},
		{
			ref: 'CcdImportWindow',
			selector: 'ccdimportwindow'
		},

		// grids...
		{
			ref: 'CcdImportMedicationsGrid',
			selector: '#CcdImportMedicationsGrid'
		},
		{
			ref: 'CcdImportAllergiesGrid',
			selector: '#CcdImportAllergiesGrid'
		},
		{
			ref: 'CcdImportProceduresGrid',
			selector: '#CcdImportProceduresGrid'
		},
		{
			ref: 'CcdImportActiveProblemsGrid',
			selector: '#CcdImportActiveProblemsGrid'
		},
		{
			ref: 'CcdImportOrderResultsGrid',
			selector: '#CcdImportOrderResultsGrid'
		},
		{
			ref: 'CcdImportEncountersGrid',
			selector: '#CcdImportEncountersGrid'
		},

		// buttons...
		{
			ref: 'CcdImportWindowImportBtn',
			selector: '#CcdImportWindowImportBtn'
		},
		{
			ref: 'CcdImportWindowCloseBtn',
			selector: '#CcdImportWindowCloseBtn'
		},
		{
			ref: 'CcdImportWindowPatientSearchField',
			selector: '#CcdImportWindowPatientSearchField'
		}
	],

	init: function(){
		var me = this;

		me.control({
			'ccdimportwindow': {
				show: me.onCcdImportWindowShow
			},
			'#CcdImportWindowImportBtn': {
				click: me.onCcdImportWindowImportBtnClick
			},
			'#CcdImportWindowCloseBtn': {
				click: me.onCcdImportWindowCloseBtnClick
			},
			'#CcdImportWindowPatientSearchField': {
				select: me.onCcdImportWindowPatientSearchFieldSelect
			}
		});
	},

	onCcdImportWindowShow: function(win){
		this.doLoadCcdData(win.ccdData);
	},

	doLoadCcdData: function(data){
		say(data);

		if(data){

		}

	},

	onCcdImportWindowImportBtnClick: function(){

	},

	onCcdImportWindowCloseBtnClick: function(){
		this.getCcdImportWindow().close();
	},

	onCcdImportWindowPatientSearchFieldSelect: function(cmb, records){


		say(records);
	}



});