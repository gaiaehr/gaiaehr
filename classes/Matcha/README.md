![Alt text](/press/matcha-connect.png)
=====================

Matcha::connect microORM v0.0.1
[Matcha::Connect](http://www.matchaconnect.com/) is a Open source Bi-directional microORM for Sencha in PHP:
This is a set of classes that will help Sencha ExtJS and PHP developers deliver fast and powerful
applications in no time at the same time easy to understand.

If Sencha ExtJS is a webGUI Framework of the future, think Matcha bi-microORM as the bridge between the
Client-Server GAP of the future.

Matcha Class:
Class that manage connections between MySQL and Sencha, PHP will be the Business Logic and Data Abstraction
Layer, also covers the creation of databases, tables and columns.

MatchaAudit Class:
A extra feature if you want that Matcha records every injection to the database
this includes INSERTS, UPDATE and ALTER. Perfect to Medic and Accounting applications.

MatchaCUP Class:
The precious tea, this class will handler all your CRUD (Create, Read, Update and Delete) to your database

MatchaErrorHandler Class:
The Error Exception class, all Matcha classes will throw their errors in this class.

MatchaThread Class:
If you want Matcha be multi-thread this is the class to use, careful some compilation to the PHP language has to be
done.

MatchaModel Class:
This class is the brain of the bi-directional microORM, this creates both models. The database table and columns
also creates Sencha Model .js files dynamically. If you have already a Sencha Model (.js) file this class will
create the database-table and it's columns, if you want to create or do dynamic Sencha Models on the fly don't
worry, this class can create the Sencha Model (.js) file and also the database-table columns for you.

History:
Taking some ideas from different microORM's and full featured ORM's we bring you this cool Class.
Born in the fields of GaiaEHR we needed a way to develop the application more faster, 
Gino Rivera suggested the use of an microORM for fast development and the development began. 
We tried to use some already developed and well known ORM's on the space of PHP, but none satisfied 
our needs. So Gino Rivera suggested the development of our own microORM for GaiaEHR (a long way to run).

But despite the long run, it returned to be more logical to get ideas from the well known ORM's 
and how Sencha manage their models and create a microORM for GaiaEHR, so this is the result.

Some ideas where from RedBeanPHP, Propel and Sencha ExtJS frameworks.
[Read more](http://www.matchaconnect.com/).
