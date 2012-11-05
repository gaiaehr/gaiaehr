/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 * @namespace Patient.getPatientsByPoolArea
 */
Ext.define('App.model.areas.PoolArea', {
	extend   : 'Ext.data.Model',
	fields   : [
		{name: 'pid', type: 'int'},
		{name: 'eid', type: 'int'},
		{name: 'name', type: 'string'},
		{name: 'shortName', type: 'string'},
		{name: 'photoSrc', type: 'string'},
		{name: 'poolArea', type: 'string'},
		{name: 'floorPlanId', type: 'int'},
		{name: 'zoneId', type: 'int'},
		{name: 'patientZoneId', type: 'int'},
		{name: 'priority', type: 'string'}
	],
	proxy    : {
		type       : 'direct',
		api        : {
			read: PoolArea.getPatientsByPoolAreaAccess
		}
	}
});