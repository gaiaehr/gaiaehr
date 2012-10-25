/*
 GaiaEHR (Electronic Health Records)
 ExternalDataLoads.js
 Store
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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

Ext.define('App.store.administration.ExternalDataLoads',
{
	model : 'App.model.administration.ExternalDataLoads',
	extend : 'Ext.data.Store',
	constructor : function(config)
	{
		var me = this;
		me.proxy =
		{
			type : 'direct',
			api :
			{
				read : ExternalDataUpdate.getCodeFiles
			},
			extraParams :
			{
				codeType : config.codeType
			}
		};
		me.callParent(arguments);
	},
	remoteSort : false,
	autoLoad : false
}); 