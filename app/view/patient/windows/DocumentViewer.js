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

Ext.define('App.view.patient.windows.DocumentViewer', {
	extend     : 'App.ux.window.Window',
	title      : i18n('documents_viewer_window'),
	layout     : 'fit',
	height     : 650,
	width      : 700,
	closeAction: 'hide',
	bodyStyle  : 'background-color:#fff',
	maximizable:true,
	modal      : true,
	defaults   : {
		margin: 5
	},
	initComponent: function() {
		var me = this;

		me.listeners = {
			scope: me,
			show : me.onViewerDocumentsWinShow
		};
		me.callParent(arguments);
	},


	onViewerDocumentsWinShow  : function() {



	}
});