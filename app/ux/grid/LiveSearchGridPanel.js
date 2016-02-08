/**
 * A GridPanel class with live search support.
 * @author Nicolas Ferrero
 */
Ext.define('App.ux.grid.LiveSearchGridPanel', {
	extend: 'Ext.grid.Panel',

	xtype: 'gridlivesearch',
	requires: [
		'Ext.toolbar.TextItem',
		'Ext.form.field.Checkbox',
		'Ext.form.field.Text',
		'Ext.ux.statusbar.StatusBar'
	],

	/**
	 * @private
	 * search value initialization
	 */
	searchValue: null,

	/**
	 * @private
	 * The row indexes where matching strings are found. (used by previous and next buttons)
	 */
	indexes: [],

	/**
	 * @private
	 * The row index of the first search, it could change if next or previous buttons are used.
	 */
	currentIndex: null,

	/**
	 * @private
	 * The generated regular expression used for searching.
	 */
	searchRegExp: null,

	/**
	 * @private
	 * Case sensitive mode.
	 */
	caseSensitive: false,

	/**
	 * @private
	 * Regular expression mode.
	 */
	regExpMode: false,

	/**
	 * @cfg {String} matchCls
	 * The matched string css classe.
	 */
	matchCls: 'x-livesearch-match',

	defaultStatusText: 'Nothing Found',

	// Component initialization override: adds the top and bottom toolbars and setup headers renderer.
	initComponent: function(){
		var me = this;

		me.callParent(arguments);

		me.addDocked({
			xtype: 'toolbar',
			dock: 'top',
			items: [
				_('search'),
				{
					xtype: 'textfield',
					name: 'searchField',
					hideLabel: true,
					width: 200,
					listeners: {
						change: {
							fn: me.onTextFieldChange,
							scope: this,
							buffer: 100
						}
					}
				},
				'-',
				{
					xtype: 'button',
					text: '&lt;',
					tooltip: 'Find Previous Row',
					handler: me.onPreviousClick,
					scope: me
				},
				{
					xtype: 'button',
					text: '&gt;',
					tooltip: 'Find Next Row',
					handler: me.onNextClick,
					scope: me
				},
				'-',
				'->',
				'-',
				{
					xtype: 'tbtext',
					text: me.defaultStatusText,
					action: 'searchStatus',
					scope: me
				}
			]
		});
	},

	// afterRender override: it adds textfield and statusbar reference and start monitoring keydown events in textfield input
	afterRender: function(){
		var me = this;
		me.callParent(arguments);
		me.textField = me.down('textfield[name=searchField]');
		me.statusBar = me.down('tbtext[action=searchStatus]');
	},
	// detects html tag
	tagsRe: /<[^>]*>/gm,

	// DEL ASCII code
	tagsProtect: '\x0f',

	// detects regexp reserved word
	regExpProtect: /\\|\/|\+|\\|\.|\[|\]|\{|\}|\?|\$|\*|\^|\|/gm,

	/**
	 * In normal mode it returns the value with protected regexp characters.
	 * In regular expression mode it returns the raw value except if the regexp is invalid.
	 * @return {String} The value to process or null if the textfield value is blank or invalid.
	 * @private
	 */
	getSearchValue: function(){
		var me = this,
			value = me.textField.getValue();

		if(value === ''){
			return null;
		}
		if(!me.regExpMode){
			value = value.replace(me.regExpProtect, function(m){
				return '\\' + m;
			});
		}else{
			try{
				new RegExp(value);
			}catch(error){
				me.statusBar.setText(error.message);
				return null;
			}
			// this is stupid
			if(value === '^' || value === '$'){
				return null;
			}
		}

		return value;
	},

	/**
	 * Finds all strings that matches the searched value in each grid cells.
	 * @private
	 */
	onTextFieldChange: function(){
		var me = this,
			count = 0;

		me.view.refresh();
		// reset the statusbar
		me.statusBar.setText(me.defaultStatusText);

		me.searchValue = me.getSearchValue();
		me.indexes = [];
		me.currentIndex = null;

		if(me.searchValue !== null){
			me.searchRegExp = new RegExp(me.searchValue, 'g' + (me.caseSensitive ? '' : 'i'));

			me.store.each(function(record, idx){

				var fly = Ext.fly(me.view.getNode(record)),
					td, cell, matches, cellHTML, is_special;

				if(fly == null) return;

				td = fly.down('td');

				while(td){

					if(td == null){
						break;
					}

					is_special = false;

					cell = td.down('.x-grid-cell-inner');
					matches = cell.dom.innerHTML.match(me.tagsRe);
					cellHTML = cell.dom.innerHTML.replace(me.tagsRe, me.tagsProtect);

					if(matches){
						for(var i = 0; i < matches.length; i++){
							if(matches[i].indexOf('x-grid-row-checker') !== -1){
								is_special = true;
								break;
							}
						}
					}


					if(!is_special){
						// populate indexes array, set currentIndex, and replace wrap matched string in a span
						cellHTML = cellHTML.replace(me.searchRegExp, function(m){
							count += 1;
							if(Ext.Array.indexOf(me.indexes, record) === -1){
								me.indexes.push(record);
							}
							if(me.currentIndex === null){
								me.currentIndex = record;
							}

							return '<span class="' + me.matchCls + '">' + m + '</span>';
						});

						// restore protected tags
						Ext.each(matches, function(match){
							cellHTML = cellHTML.replace(me.tagsProtect, match);
						});
						// update cell html
						cell.dom.innerHTML = cellHTML;
					}


					td = td.next();
				}

			}, me);

			// results found
			if(me.currentIndex !== null){
				//me.getSelectionModel().select(me.currentIndex);
				me.statusBar.setText(count + ' matche(s) found.');
			}
		}

		// no results found
		if(me.currentIndex === null){
			//me.getSelectionModel().deselectAll();
		}

		// force textfield focus
		me.textField.focus();
	},

	/**
	 * Selects the previous row containing a match.
	 * @private
	 */
	onPreviousClick: function(){
		var me = this,
			idx;

		if((idx = Ext.Array.indexOf(me.indexes, me.currentIndex)) !== -1){
			me.currentIndex = me.indexes[idx - 1] || me.indexes[me.indexes.length - 1];
			me.getSelectionModel().select(me.currentIndex);
		}
	},

	/**
	 * Selects the next row containing a match.
	 * @private
	 */
	onNextClick: function(){
		var me = this,
			idx;

		if((idx = Ext.Array.indexOf(me.indexes, me.currentIndex)) !== -1){
			me.currentIndex = me.indexes[idx + 1] || me.indexes[0];
			me.getSelectionModel().select(me.currentIndex);
		}
	},

	/**
	 * Switch to case sensitive mode.
	 * @private
	 */
	caseSensitiveToggle: function(checkbox, checked){
		this.caseSensitive = checked;
		this.onTextFieldChange();
	},

	/**
	 * Switch to regular expression mode
	 * @private
	 */
	regExpToggle: function(checkbox, checked){
		this.regExpMode = checked;
		this.onTextFieldChange();
	}
});