/**
 * A plugin that provides the ability to visually indicate to the user that a node is disabled.
 *
 * Notes:
 * - Compatible with Ext 4.x
 * - If the view already defines a getRowClass function, the original function will be called before this plugin.
 *
 var tree = Ext.create('Ext.tree.Panel',{
 plugins: [{
 ptype: 'nodedisabled'
 }]
 ...
 });
 *
 * @class Ext.ux.tree.plugin.NodeDisabled
 * @extends Ext.AbstractPlugin
 * @author Phil Crawford
 * @license Licensed under the terms of the Open Source <a href="http://www.gnu.org/licenses/lgpl.html">LGPL 3.0 license</a>.  Commercial use is permitted to the extent that the code/component(s) do NOT become part of another Open Source or Commercially licensed development library or toolkit without explicit permission.
 * @version 0.1 (July 1, 2011)
 * @constructor
 * @param {Object} config
 */
Ext.define('App.ux.NodeDisabled',
{
	alias : 'plugin.nodedisabled',
	extend : 'Ext.AbstractPlugin'

	//configurables
	/**
	 * @cfg {String} disabledCls
	 * The CSS class applied when the {@link Ext.data.Model} of the node has a 'disabled' field with a true value.
	 */,
	disabledCls : 'tree-node-disabled'
	/**
	 * @cfg {Boolean} preventSelection
	 * True to prevent selection of a node that is disabled. Default true.
	 */,
	preventSelection : true

	/**
	 * @cfg {Boolean} preventChecking
	 * True to prevent checking of a node that is disabled. Default true.
	 */,
	preventChecking : true

	//properties

	/**
	 * @private
	 * @param {Ext.tree.Panel} tree
	 */,
	init : function(tree)
	{
		var me = this, view = tree.getView(), origFn, origScope;

		me.callParent(arguments);

		origFn = view.getRowClass;
		if (origFn)
		{
			origScope = view.scope || me;
			Ext.apply(view,
			{
				getRowClass : function()
				{
					var v1, v2;
					v1 = origFn.apply(origScope, arguments) || '';
					v2 = me.getRowClass.apply(me, arguments) || '';
					return (v1 && v2) ? v1 + ' ' + v2 : v1 + v2;
				}
			}, null);
		}
		else
		{
			Ext.apply(view,
			{
				getRowClass : Ext.Function.bind(me.getRowClass, me)
			}, null);
		}

		if (me.preventSelection)
		{
			tree.getSelectionModel().on('beforeselect', me.onBeforeNodeSelect, me);
		}

		if (me.preventChecking)
		{
			tree.on('checkchange', me.checkchange, me);
		}
	}// eof init
	/**
	 * Returns a properly typed result.
	 * @return {Ext.tree.Panel}
	 */,
	getCmp : function()
	{
		return this.callParent(arguments);
	}//eof getCmp
	/**
	 * @private
	 * @param {Ext.data.Model} record
	 * @param {Number} index
	 * @param {Object} rowParams
	 * @param {Ext.data.Store} ds
	 * @return {String}
	 */,
	getRowClass : function(record, index, rowParams, ds)
	{
		return record.get('disabled') ? this.disabledCls : '';
	}//eof getRowClass
	/**
	 * @private
	 * @param {Ext.selection.TreeModel} sm
	 * @param {Ext.data.Model} node
	 * @return {Boolean}
	 */,
	onBeforeNodeSelect : function(sm, node)
	{
		if (node.get('disabled'))
		{
			return false;
		}
	}//eof onBeforeNodeSelect
	/**
	 * @event checkchange
	 * Fires when a node with a checkbox's checked property changes
	 * @param {Ext.data.Model} node The node who's checked property was changed
	 * @param {Boolean} checked The node's new checked state
	 */,
	checkchange : function(node, checked)
	{
		if (node.get('disabled'))
		{
			node.set('checked', !checked);
		}
	}//eof checkchange
});
//eo class

//end of file