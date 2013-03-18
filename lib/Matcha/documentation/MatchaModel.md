![Match::connect](../press/matcha-connect.png)
=====================

##MatchaModel Class Documentation
Handles the Sencha Models (files or dynnamic)

Example Sencha Model (.js) file:
```javascript
Ext.define('App.model.administration.User',{
	extend : 'Ext.data.Model',
	table: {
		name: 'users',
		comment: 'User accounts'
	},
	fields: [
        {name: 'id',                type: 'int',    comment: 'User Account ID'},

		{name: 'create_uid',        type: 'int',    comment: 'create user ID'},
		{name: 'update_uid',        type: 'int',    comment: 'update user ID'},
		{name: 'create_date',       type: 'date',   comment: 'create date',         dateFormat:'Y-m-d H:i:s'},
		{name: 'update_date',       type: 'date',   comment: 'last update date',    dateFormat:'Y-m-d H:i:s'},

		{name: 'username',          type: 'string', comment: 'username'},
		{name: 'password',          type: 'string', comment: 'password',  dataType: 'blob'},

		{name: 'fname',             type: 'string', comment: 'first name'},
		{name: 'mname',             type: 'string', comment: 'middle name'},
		{name: 'lname',             type: 'string', comment: 'last name'},

		{name: 'email',             type: 'string', comment: 'email'},
	],
	proxy: {
		type: 'direct',
		api: {
			read: User.getUsers,
			create: User.addUser,
			update: User.updateUser
		}
	},
	hasMany: [
		{
			model: 'App.model.Phones',
			name: 'phones',
			primaryKey: 'id',
			foreignKey: 'use_id'
		},
		{
			model: 'App.model.Address',
			name: 'address',
			primaryKey: 'id',
			foreignKey: 'use_id'
		}
	]
});
```

```php
require_once('/Matcha/Matcha.php');

private $User = NULL;

//dataProvider for Sencha
class UserData extends Matcha
{
    public function __construct()
    {
        $this->User = MatchaModel::setSenchaModel('App.model.administration.User');
    }
}
```

The code will read a Sencha (.js) model file and parse it, and will create the data base if does not exist, the table
if does not exist and their columns if does not exist, all this in one call. Also will store a serialized structure of
the table on the database (Memory Store). This way will be much faster to access it another time.

The model "App.model.administration.User" is located in "[root]/App/model/administration/User.js", Why this? because we
want it to keep like Sencha does that's the reason.

But keep in mind, if you are using Sencha with Direct, you better use a condition to check if the variable is already
set. Like the following code:

```php
require_once('/Matcha/Matcha.php');

private $User = NULL;

//dataProvider for Sencha
class UserData extends Matcha
{
    public function __construct()
    {
        if($this->User == NULL) $this->User = MatchaModel::setSenchaModel('App.model.administration.User');
    }
}
```

This because Sencha Direct can call several methods in one request.
