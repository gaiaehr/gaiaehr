/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

Ext.define('Modules.imageforms.controller.ImageForm', {
	extend: 'Ext.app.Controller',
	requires: [
		'Modules.imageforms.view.EncounterImageFormsPanel',
		'App.ux.form.fields.UploadBase64'
	],
	refs: [
		{
			ref: 'ImageFormPanel',
			selector: '#imageFormPanel'
		},
		{
			ref: 'ImageFormEditBtn',
			selector: '#imageFormEditBtn'
		},
		{
			ref: 'ImageFormDefaultsCombo',
			selector: '#imageFormDefaultsCombo'
		}
	],

	form: null,
	canvas: null,
	context: null,

	clickX: [],
	clickY: [],
	clickDrag: [],
	paint: false,
	offsetTop: 0,
	offsetLeft: 0,

	init: function(){
		var me = this;

		me.control({
			'#imageFormPanel': {
				beforerender: me.onImagePanelBeforeRender,
				show: me.onImagePanelShow
			},
			'#imageFormEditBtn': {
				toggle: me.onEditToggle
			},
			'#imageFormDefaultsCombo': {
				select: me.onImageCmbSelect
			},
			'#imageFormRemoveBtn': {
				click: me.onImageRemoveClick
			},
			'#imageFormResetBtn': {
				click: me.onImageResetClick
			},
			'#imageFormSaveBtn': {
				click: me.onImageSaveClick
			},
			'#imageFormAddImageBtn': {
				click: me.onImageAddImageBtnClick
			},
			'#imageFormUploadBtn': {
				click: me.onImageFormUploadBtnClick
			},
			'#imageFormColorBtn': {
				click: me.onImageFormColorBtnClick
			}
		});
	},

	onImagePanelBeforeRender: function(panel){
		var me = this;

		panel.store.on('add', function(store, records){
			for(var i = 0; i < records.length; i++){
				me.doAddForm(records[i]);
			}
		}, me);

		panel.store.on('remove', function(store, records){
			for(var i = 0; i < records.length; i++){
				me.doRemoveForm(records[i]);
			}
		}, me);
	},

	onImagePanelShow: function(panel){
		var me = this;
		me.getImageFormPanel().removeAll();
		panel.store.load({
			filters: [
				{
					property: 'pid',
					value: app.patient.pid
				}
			],
			callback: function(records){
				for(var i = 0; i < records.length; i++){
					me.doAddForm(records[i]);
				}
			}
		})
	},

	/**
	 *
	 * @param btn
	 * @param pressed
	 */
	onEditToggle: function(btn, pressed){
		var form = btn.up('form');
		if(pressed){
			form.controller.setFormCanvasEvents(form);
		}else{
			form.controller.stopFormCanvasEvents(form);
		}
	},

	initFormCanvas: function(form){
		var record = form.getForm().getRecord();
		record.form = form;

		form.controller = this;
		form.mouse = {x: 0, y: 0};
		form.last_mouse = {x: 0, y: 0};
		form.ppts = [];
		form.paint = false;
		form.color = '#000000';

		form.iCanvas = form.getComponent(0).el;
		form.iContext = form.iCanvas.dom.getContext("2d");

		form.dCanvas = form.getComponent(1).el;
		form.dContext = form.dCanvas.dom.getContext("2d");

		form.dContext.lineWidth = 5;
		form.dContext.lineJoin = 'round';
		form.dContext.lineCap = 'round';
		form.dContext.strokeStyle = form.color;
		form.dContext.fillStyle = form.color;

		this.setFormCanvasBox(form);

		if(record.data.image != ''){
			this.loadImage(form, record.data.image);
		}

		if(record.data.drawing != ''){
			this.loadDrawing(form, record.data.drawing);
		}
	},

	setFormCanvasEvents: function(form){
		form.dCanvas.on('mouseup', this.onMouseUp, form);
		form.dCanvas.on('mouseleave', this.onMouseLeave, form);
		form.dCanvas.on('mousemove', this.onMouseMove, form);
		form.dCanvas.on('mousedown', this.onMouseDown, form);
		form.on('resize', this.setFormCanvasBox, form);
	},

	stopFormCanvasEvents: function(form){
		form.dCanvas.un('mouseup', this.onMouseUp, form);
		form.dCanvas.un('mouseleave', this.onMouseLeave, form);
		form.dCanvas.un('mousemove', this.onMouseMove, form);
		form.dCanvas.un('mousedown', this.onMouseDown, form);
		form.un('resize', this.setFormCanvasBox, form);
	},

	setFormCanvasOffSets: function(form){
		form.offsetLeft = form.dCanvas.getX();
		form.offsetTop = form.dCanvas.getY();
	},

	setFormCanvasBox: function(form){
		var w = form.body.getWidth(),
			h = form.body.getHeight();

		form.dCanvas.setWidth(w);
		form.dCanvas.setHeight(h);
		form.dContext.canvas.width = w;
		form.dContext.canvas.height = h;

		form.iCanvas.setWidth(w);
		form.iCanvas.setHeight(h);
		form.iContext.canvas.width = w;
		form.iContext.canvas.height = h;
	},

	onMouseUp: function(){
		this.paint = false;
		this.ppts = [];
	},

	onMouseLeave: function(){
		this.paint = false;
	},

	onMouseMove: function(e){
		if(this.paint){
			this.mouse.x = e.getX() - this.offsetLeft;
			this.mouse.y = e.getY() - this.offsetTop;
			this.controller.doPaint(this);
		}
	},

	onMouseDown: function(e){
		this.controller.setFormCanvasOffSets(this);
		this.paint = true;
		this.mouse.x = e.getX() - this.offsetLeft;
		this.mouse.y = e.getY() - this.offsetTop;
		this.ppts.push({x: this.mouse.x, y: this.mouse.y});
		this.controller.doPaint(this);
	},

	doPaint: function(form){
		form.ppts.push({x: form.mouse.x, y: form.mouse.y});

		if(form.ppts.length < 3){
			var b = form.ppts[0];
			form.dContext.beginPath();
			form.dContext.arc(b.x, b.y, form.dContext.lineWidth / 2, 0, Math.PI * 2, !0);
			form.dContext.fill();
			form.dContext.closePath();

			return;
		}

		// Tmp canvas is always cleared up before drawing.
		form.dContext.clearRect(0, 0, form.dCanvas.width, form.dCanvas.height);

		form.dContext.beginPath();
		form.dContext.moveTo(form.ppts[0].x, form.ppts[0].y);

		for(var i = 1; i < form.ppts.length - 2; i++){
			var c = (form.ppts[i].x + form.ppts[i + 1].x) / 2;
			var d = (form.ppts[i].y + form.ppts[i + 1].y) / 2;

			form.dContext.quadraticCurveTo(form.ppts[i].x, form.ppts[i].y, c, d);
		}

		// For the last 2 points
		form.dContext.quadraticCurveTo(
			form.ppts[i].x,
			form.ppts[i].y,
			form.ppts[i + 1].x,
			form.ppts[i + 1].y
		);
		form.dContext.stroke();
	},

	loadDrawing: function(form, src){
		if(form.drawing) delete form.drawing;
		form.drawing = new Image();
		form.drawing.src = src;
		form.drawing.onload = function(){
			form.dContext.clearRect(0, 0, form.dContext.canvas.width, form.dContext.canvas.height);
			form.dContext.drawImage(form.drawing, 0, 0, form.dContext.canvas.width, form.dContext.canvas.height);
			form.dContext.save();
		};
	},

	loadImage: function(form, src){
		if(form.image) delete form.image;

		form.image = new Image();
		form.image.src = src;

		form.image.onload = function(){

			say(form);

			form.setSize(form.image.width + 10, form.image.height + 120);
			form.controller.setFormCanvasBox(form);
			form.iContext.clearRect(0, 0, form.image.width, form.image.height);
			form.iContext.drawImage(form.image, 0, 0, form.image.width, form.image.height);
			form.iContext.save();

		};
	},

	onImageCmbSelect: function(cmb, records){
		var form = cmb.up('form'),
			record = form.getForm().getRecord(),
			image = records[0].data.value;
		record.set({image: image});
		this.loadImage(cmb.up('form'), image);
	},

	onImageRemoveClick: function(btn){
		var me = this,
			form = btn.up('form'),
			record = form.getForm().getRecord();

		if(record.data.id > 0){
			app.msg(_('oops'), _('unable_to_delete_image_msg'), true);
		}else{
			Ext.Msg.show({
				title: _('wait'),
				msg: _('delete_this_image_confirm'),
				buttons: Ext.Msg.YESNO,
				icon: Ext.Msg.QUESTION,
				scope: me,
				fn: function(b){
					if(b == 'yes'){
						me.doRemoveForm(record);
					}
				}
			});
		}
	},

	doRemoveForm:function(record){
		this.getImageFormPanel().remove(record.form, true);
		this.getImageFormPanel().store.remove(record);
	},

	onImageResetClick: function(btn){
		var form = btn.up('form'),
			record = form.getForm().getRecord();
		this.loadDrawing(form, record.data.drawing);
		form.dContext.clearRect(0, 0, form.dContext.canvas.width, form.dContext.canvas.height);
	},

	onImageSaveClick: function(btn){
		var form = btn.up('form'),
			record = form.getForm().getRecord();

		record.set({
			image: form.iCanvas.dom.toDataURL(),
			drawing: form.dCanvas.dom.toDataURL(),
			notes: form.getForm().findField('notes').getValue()
		});

		record.save();
	},

	doAddForm: function(record){
		var form = Ext.create('Modules.imageforms.view.ImageForm', {
			width: 450,
			height: 380
		});

		form.query('button[action=encounterRecordAdd]')[0].setDisabled(app.patient.encounterIsClose);
		form.loadRecord(record);
		this.getImageFormPanel().add(form);
		this.initFormCanvas(form);
	},

	onImageAddImageBtnClick: function(){
		this.getImageFormPanel().store.add({
			pid: app.patient.pid,
			eid: app.patient.eid,
			create_uid: app.user.id,
			create_date: new Date()
		});
	},

	onImageFormUploadBtnClick: function(btn){
		var me = this,
			form = btn.up('form'),
			upload = Ext.widget('uploadbase64field');

		upload.on('uploadready', function(field, base64){
			me.loadImage(form, base64);
		}, me);
		upload.showAt(btn.el.getX(), btn.el.getY());
	},

	onImageFormColorBtnClick: function(btn){
		var me = this,
			form = btn.up('form'),
			picker = Ext.widget('window', {
				modal: true,
				items: [
					{
						xtype: 'colorpicker',
						value: form.color,
						listeners: {
							scope: form,
							select: me.onColorSelect
						}
					}
				]
			});

		picker.show(btn.el);
	},

	onColorSelect: function(field, color){
		this.color = '#' + color;
		this.dContext.strokeStyle = this.color;
		this.dContext.fillStyle = this.color;
		field.up('window').close();
	}

});