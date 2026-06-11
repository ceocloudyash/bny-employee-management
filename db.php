<?php

echo "<pre>";
print_r($_ENV);
echo "</pre>";

exit();<?php

echo "<pre>";

echo "GETENV HOST = ";
var_dump(getenv('DB_HOST'));

echo "\n\n_ENV HOST = ";
var_dump($_ENV['DB_HOST'] ?? null);

echo "\n\n_SERVER HOST = ";
var_dump($_SERVER['DB_HOST'] ?? null);

echo "\n\nAll ENV:\n";
print_r($_ENV);

echo "\n\nAll SERVER:\n";
print_r($_SERVER);

echo "</pre>";

exit;