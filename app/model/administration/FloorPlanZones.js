/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */


Ext.define('App.model.administration.FloorPlanZones', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'id', type: 'int'},
		{name: 'floor_plan_id', type: 'int'},
		{name: 'title', type: 'string'},
		{name: 'type', type: 'string'},
		{name: 'color', type: 'string', useNull:true},
		{name: 'width', type: 'int', useNull:true},
		{name: 'height', type: 'int', useNull:true},
		{name: 'x', type: 'int'},
		{name: 'y', type: 'int'},
		{name: 'active', type: 'bool'}
	]
});