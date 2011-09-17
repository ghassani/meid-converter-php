<?php 
require_once('MEID.php');
require_once('MetroSPC.php');

$converter = new MetroSpcCalculator('268435458915701359');

echo 'MEID: 268435458915701359'."\n";
echo 'MEID: A000001DEF956F'."\n";
echo 'pESN Should Be: 12809680498'."\n";
echo 'SPC Should Be: 418965'."\n";

echo 'Result: '."\n";
print_r($converter->calculate());

?>