<?php
echo "El servidor PHP está funcionando. Archivos presentes: <pre>";
print_r(scandir('.'));
echo "</pre>";
?>