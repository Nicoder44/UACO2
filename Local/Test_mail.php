<?php
$header="MIME-Version: 1.0\r\n";
$header.='From:"UA CO2"<nicomace@univ-angers.fr>'."\n";
$header.='Content-Type:text/html; charset="UTF-8"'."\n";
$header.='Content-Transfer-Encoding: 8bit';

$message='
<html>
    <body>
        <div align="center">
        Test mec je t\'envoie un mail en php
        </div>
    </body>
</html>
';

mail("nmace44@gmail.com" , "UA CO2 vous envoie un message", $message, $header);
?>