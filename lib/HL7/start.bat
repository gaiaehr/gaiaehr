@ECHO OFF
START /B php -d max_execution_time=0 -f  ".\HL7Server.php" -- %1 %2 %3 %4 %5 %6 &
EXIT