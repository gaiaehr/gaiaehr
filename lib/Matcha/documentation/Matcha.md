![Match::connect](../press/matcha-connect.png)
=====================

##MatchaCUP Class Documentation

It's the first class to call, all other classes depend upon it. It has all the necesary methods to handle the database:
- MySQL

```php
// MySQL Connection Settings

require_once('/Matcha/Matcha.php');

class MatchaHelper extends Matcha
{
    function __contruct()
    {
        self::connect(array(
            'host'=>'localhost',
            'port'=>'3306',
            'name'=>'appdb',
            'user'=>'appuser',
            'pass'=>'apppass',
            'app'=>'app/'
        ));
    }
}

```
