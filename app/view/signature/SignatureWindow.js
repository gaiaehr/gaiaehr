/**
 GaiaEHR (Electronic Health Records)
 Copyright (C) 2013 Certun, LLC.

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

Ext.define('App.view.signature.SignatureWindow', {
	extend      : 'Ext.window.Window',
	title       : _('please_sign'),
	closeAction : 'hide',
	height      : 250,
	width       : 500,
	bodyStyle   : 'background-color:#fff',
	modal       : true,
    layout		: 'fit',
	initComponent: function() {
		var me = this;

        me.html = me.signature = '<iframe id="svgSignature" src="app/view/signature/signature.svg" height="100%" width="100%" scrolling="no" frameborder="0"></iframe>';

        me.buttons = [
            {
                text: _('save'),
                scope:me,
                handler:me.signatureSave
            },
            {
                text: _('reset'),
                scope:me,
                handler:me.signatureCancel
            }
        ];

		this.callParent(arguments);

	},

    signatureSave:function(){
        var svg = document.getElementById('svgSignature').contentWindow;
    },

    signatureCancel:function(){
        var svg = document.getElementById('svgSignature').contentWindow;
        svg.clearSignature();
        //this.close();
    }



});
