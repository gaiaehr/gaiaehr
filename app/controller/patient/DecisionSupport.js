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

Ext.define('App.controller.patient.DecisionSupport', {
	extend: 'Ext.app.Controller',
	requires: [

	],
	refs: [
		{
			ref: 'DecisionSupportWarningPanel',
			selector: '#DecisionSupportWarningPanel'
		}
	],

	init: function(){
		var me = this;
		me.control({
			'viewport':{
				beforeencounterload: me.onBeforeEncounterLoad
			},
			'#DecisionSupportWarningPanelCloseBtn':{
				click: me.DecisionSupportWarningPanelCloseBtnClick
			}
		});

	},

	DecisionSupportWarningPanelCloseBtnClick: function(btn){
		var warning = btn.up('decisionsupportwarningpanel');
		warning.collapse();
		warning.hide();
		warning.removeAll();
	},

	onBeforeEncounterLoad: function(){
		this.getDecisionSupportAlerts();
	},

	getDecisionSupportAlerts:function(){
        var btn,
            warning,
            i;

		if(!this.getDecisionSupportWarningPanel()) return;

		warning = this.getDecisionSupportWarningPanel();
		warning.collapse();
		warning.hide();
		warning.removeAll();

		DecisionSupport.getAlerts({ pid:app.patient.pid, alertType:'P' }, function(results){
			for(i=0; i < results.length; i++){
				btn = {
					xtype: 'button',
					margin: '2 5',
					icon: (results[i].reference != '' ? 'resources/images/icons/blueInfo.png' : null),
					text: results[i].description,
					result: results[i],
					handler: function(btn){
						if(btn.result.reference != ''){
							window.open(btn.result.reference, "_blank", "toolbar=no, scrollbars=yes, resizable=yes, top=10, left=10, width=1000, height=600");
						}else{
							app.msg(_('oops'), _('no_reference_provided'), true);
						}
					}
				};

				warning.add(btn);
			}

			if(results.length > 0){
				warning.show();
				warning.expand();
			}

		});
	}

});
