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

Ext.define('Modules.imageforms.view.ImageForm', {
    extend   : 'Ext.form.Panel',
    width:462,
    height:487,
    style:'float:left',
    margin:'0 10 10 0',
    initComponent: function() {
        var me = this;



        me.tbar = [
            {
                text:'Add Note',
                iconCls:'icoAdd'
            },
            '->',
            Ext.create('Modules.imageforms.view.FormBackgroundImagesCombo',{
                listeners:{
                    scope:me,
                    change:me.onFormSelected
                }
            }),
            '-',
            {
                text:'Upload Image'
            },
            '-',
            {
                xtype:'tool',
                type:'close',
                scope:me,
                handler:me.removeForm
            }
        ];

        me.callParent(arguments);
    },

    onFormSelected:function(btn, newValue){
        var me = this;
        if(me.img){
            me.img.setSrc(newValue);
        }else{
            me.img = me.add(Ext.create('Ext.Img',{src: newValue}));
        }


    },

    removeForm:function(){
        this.destroy();
    }
});