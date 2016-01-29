Ext.define("App.ux.form.fields.plugin.PasswordStrength", {
	extend : "Ext.AbstractPlugin",
	alias  : "plugin.passwordstrength",
	colors : [
		"ffcccc",
		"ffcc99",
		"ffff99",
		"99ccff",
		"99ff99"
	],

	init: function(cmp) {
		var me = this;

		cmp.on("change", me.onFieldChange, me);
	},

	onFieldChange: function(field, newVal, oldVal) {
		if (newVal === "") {
			field.inputEl.setStyle({
				"background-color" : null,
				"background-image" : null
			});
			field.score = 0;
			return ;
		}
		var me    = this,
			score = me.scorePassword(newVal);

		field.score = score;

		me.processValue(field, score);
	},

	processValue: function(field, score) {
		var me     = this,
			colors = me.colors,
			color;

		if (score < 16) {
			color = colors[0]; //very weak
		} else if (score > 15 && score < 25) {
			color = colors[1]; //weak
		} else if (score > 24 && score < 35) {
			color = colors[2]; //mediocre
		} else if (score > 34 && score < 45) {
			color = colors[3]; //strong
		} else {
			color = colors[4]; //very strong
		}

		field.inputEl.setStyle({
			"background-color" : "#" + color,
			"background-image" : "none"
		});
	},

	scorePassword: function(passwd) {
		var score = 0;

		if (passwd.length < 5) {
			score += 3;
		} else if (passwd.length > 4 && passwd.length < 8) {
			score += 6;
		} else if (passwd.length > 7 && passwd.length < 13) {
			score += 12;
		} else if (passwd.length > 12) {
			score += 18;
		}

		if (passwd.match(/[a-z]/)) {
			score += 1;
		}

		if (passwd.match(/[A-Z]/)) {
			score += 5;
		}

		if (passwd.match(/\d+/)) {
			score += 5;
		}

		if (passwd.match(/(.*[0-9].*[0-9].*[0-9])/)) {
			score += 5;
		}

		if (passwd.match(/.[!,@,#,$,%,^,&,*,?,_,~]/)) {
			score += 7;
		}

		if (passwd.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)) {
			score += 7;
		}

		if (passwd.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
			score += 3;
		}

		if (passwd.match(/([a-zA-Z])/) && passwd.match(/([0-9])/)) {
			score += 3;
		}

		if (passwd.match(/([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/)) {
			score += 3;
		}

		return score;
	}
});