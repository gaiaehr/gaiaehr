/**
 * Encounter.ejs.php
 * Encounter Panel
 * v0.0.1
 *
 * Author: Ernesto J. Rodriguez
 * Modified:
 *
 * GaiaEHR (Electronic Health Records) 2011
 *
 * @namespace Encounter.getEncounter
 * @namespace Encounter.createEncounter
 * @namespace Encounter.checkOpenEncounters
 * @namespace Encounter.closeEncounter
 * @namespace Encounter.getVitals
 * @namespace Encounter.addVitals
 */
Ext.define('App.view.patientfile.CheckoutAlertsView', {
	extend           : 'Ext.view.View',
    alias            : 'widget.checkoutalertsview',
	trackOver        : true,
    cls              : 'checkoutalert',
    itemSelector     : 'div.alert-div',
    loadMask         : true,
    singleSelect     : true,
	emptyText        : '<span style="color: #616161; font-size: 12px;">Sweet! No Alerts Found.</span>',
	initComponent: function() {
		var me = this;

        me.tpl = '  <table>' +
	        '           <tpl for=".">' +
	        '               <tr class="alert-div>' +
	        '               <div class="alert-div">' +
	        '                   <img class="alert-img" src="{icon}" />' +
	        '                   <div class="alert-msg">{alert}</div>' +
	        '               </div>' +
	        '               </tr>' +
            '           </tpl>' +
	        '       </table>';

		me.callParent(arguments);
	}

});
