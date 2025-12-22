<?php
echo "FCPATH: " . FCPATH . "<br>";
$path = 'assets/images/company/logo/pt-duta-wibawa-manda-putra-1761039086.jpg';
echo "Checking path: " . FCPATH . $path . "<br>";
echo "Result: " . (file_exists(FCPATH . $path) ? 'FOUND' : 'NOT FOUND');
