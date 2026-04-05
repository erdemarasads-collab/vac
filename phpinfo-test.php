<?php
echo "PHP Version: " . phpversion() . "<br>";
echo "PDO drivers: ";
print_r(PDO::getAvailableDrivers());
echo "<br>";
echo "pdo_mysql loaded: " . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "<br>";
echo "mysqli loaded: " . (extension_loaded('mysqli') ? 'YES' : 'NO') . "<br>";
