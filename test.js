var overrides = {
	b4StartDrag:function() {
		if(!this.el) {
			this.el = Ext.get(this.getEl());
		}
		this.originalXY = this.el.getXY();
	},
	onInvalidDrop : function() {
		this.invalidDrop = true;

	},
	endDrag : function() {
		if(this.invalidDrop === true) {
			this.el.removeCls('dropOK');
			var animCfgObj = {
				easing  : 'elasticOut',
				duration: 1,
				scope   : this,
				callback: function() {
					this.el.dom.style.position = '';
				}
			};
			this.el.moveTo(this.originalXY[0], this.originalXY[1], animCfgObj);
			delete this.invalidDrop;
		}
	}
	,
	onDragEnter : function(evtObj, targetElId) {
		if(targetElId != this.el.dom.parentNode.id) {
			this.el.addCls('dropOK');
		}
		else {
			this.onDragOut();
		}
	}
	,
	onDragOut : function(evtObj, targetElId) {
		this.el.removeCls('dropOK');
	}
	,
	onDragDrop : function(evtObj, targetElId) {
		var dropEl = Ext.get(targetElId);
		if(this.el.dom.parentNode.id != targetElId) {
			var element = this.el;
			element.replaceCls('swimlane-icon ', 'bigswimlane-icon');
			dropEl.appendChild(this.el);
			this.onDragOut(evtObj, targetElId);
		}
		else {
			this.onInvalidDrop();
		}
	}
};

var toolbarElements = Ext.get('toolbarLeft').select('div');
Ext.each(toolbarElements.elements, function(el) {
	var dd = Ext.create('Ext.dd.DD', el, 'drop_target', {
		isTarget: false
	});
	var dropTarget_origin = Ext.create('Ext.dd.DDTarget', 'toolbarLeft', 'drop_target');
	var dropTarget = Ext.create('Ext.dd.DDTarget', 'editor_Panel', 'drop_target');
	//Apply the overrides object to the newly created instance of DD
	Ext.apply(dd, overrides);
});