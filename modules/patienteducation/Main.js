/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

Ext.define('Modules.patienteducation.Main', {
	extend : 'Modules.Module',
	init : function()	{
		var me = this;

        app.checkoutWindow.on('render', function(win){

            win.getDockedItems('toolbar[dock="bottom"]')[0].insert(0,[{
                xtype:'button',
                text:i18n('patient_education'),
                handler:me.onPatientEducation
            },'->']);

        });

		me.callParent();
	},

    onPatientEducation:function(btn){

        var win = btn.up('window'),
            pid = win.pid,
            eid = win.eid;

        say(pid);
        say(eid);
        say('patient education pressed');


    }
}); 