<?php
echo "MYSQLHOST: " . (getenv('MYSQLHOST') ?: 'NULL getenv') . "<br>";
echo "MYSQLHOST _ENV: " . ($_ENV['MYSQLHOST'] ?? 'NULL _ENV') . "<br>";
echo "MYSQLHOST _SERVER: " . ($_SERVER['MYSQLHOST'] ?? 'NULL _SERVER') . "<br>";
echo "MYSQLPORT: " . (getenv('MYSQLPORT') ?: 'NULL') . "<br>";
