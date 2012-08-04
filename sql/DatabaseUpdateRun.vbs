Set objShell = CreateObject("Wscript.Shell")
objShell.Run("%comspec% /k cd C:\wamp\bin\mysql\mysql5.5.20\bin & mysql -u root")
wscript.sleep(500)
objShell.sendkeys("\u mitosdb")
objShell.sendkeys"{Enter}"
objShell.sendkeys("source C:/wamp/www/gaiaehr/sql/updates/gaiadb.sql")
objShell.sendkeys"{Enter}"