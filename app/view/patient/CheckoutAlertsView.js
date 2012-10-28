/*
 GaiaEHR (Electronic Health Records)
 CheckoutAlertsView.js
 Checkout Alerts View
 Copyright (C) 2012 Ernesto Rodriguez

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.define('App.view.patient.CheckoutAlertsView',
{
	extend : 'Ext.view.View',
	alias : 'widget.checkoutalertsview',
	trackOver : true,
	cls : 'checkoutalert',
	itemSelector : 'div.alert-div',
	loadMask : true,
	singleSelect : true,
	emptyText : '<span style="color: #616161; font-size: 12px;">Sweet! ' + i18n('no_alerts_found') + '.</span>',
	initComponent : function()
	{
		var me = this;

		me.tpl = '  <table>' + '           <tpl for=".">' + '               <tr class="alert-div>' + '               <div class="alert-div">' + '                   <img class="alert-img" src="{icon}" />' + '                   <div class="alert-msg">{alert}</div>' + '               </div>' + '               </tr>' + '           </tpl>' + '       </table>';

		me.callParent(arguments);
	}
});
