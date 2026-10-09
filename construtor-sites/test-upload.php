<?php
echo "Upload endpoint test<br>";
echo "Upload dir exists: " . (file_exists(__DIR__ . '/uploads') ? 'YES' : 'NO') . "<br>";
echo "Upload dir writable: " . (is_writable(__DIR__ . '/uploads') ? 'YES' : 'NO') . "<br>";