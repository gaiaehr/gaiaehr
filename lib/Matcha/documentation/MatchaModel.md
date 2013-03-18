![Match::connect](../press/matcha-connect.png)
=====================

##MatchaModel Class Documentation
Handles the Sencha Models (files or dynnamic)


```php
require_once('/Matcha/Matcha.php');

private $User = NULL;

//dataProvider for Sencha
class Data extends MatchaHelper
{
    public function __construct()
    {
        $this->User = MatchaModel::setSenchaModel('App.model.administration.User');
    }
}
```

The code will read a Sencha (.js) model file and parse it, and will create the data base is does not exist, the table
if does not exist and their columns if does not exist, all this in one call. Also will store a serialized structure of
the table on the database (Memory Store). This way will be much faster to access it another time.


