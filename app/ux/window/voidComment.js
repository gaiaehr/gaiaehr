/**
 * GaiaEHR (Electronic Health Records)
 * voidCommentWindow.js
 * UX
 * Copyright (C) 2012 TRA NextGen, Inc.
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

Ext.define('App.ux.window.voidComment',{
    extend:'Ext.window.Window',
    xtype: 'voidcommentwindow',
    itemId: 'VoidCommentWindow',
    alias:'widget.voidwindow',
    title: _('void_comment'),
    items:[
        {
            xtype:'form',
            id:'VOIDComment',
            bodyPadding: 3,
            width: 400,
            height: 250,
            layout: 'anchor',
            defaultType: 'textfield',
            items: [{
                name: 'void_comment',
                allowBlank: false,
                hideLabel: true,
                grow: true,
                growMax: 600
            }]
        }
    ],
    buttons:[
        {
            xtype:'button',
            action:'save',
            text: _('save')
        },
        {
            xtype:'button',
            action:'cancel',
            text: _('cancel')
        }
    ]
});
