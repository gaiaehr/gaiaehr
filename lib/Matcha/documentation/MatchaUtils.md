![Match::connect](../press/matcha-connect.png)
=====================

##MatchaUtils Class Documentation
Method to do all kind of stuff, like manage Arrays the easy way.
Also supports plugins, Matcha already supports:
- Carbon (Manages Date and Time)
- FirePHP (It's used by MatchaAudit)
- ChromePHP (It's used by MatchaAudit)
- Browse Detect (It's used by MatchaAudit)

```php

printf("Right now is %s", MatchaUtils::Carbon->now()->toDateTimeString());

```