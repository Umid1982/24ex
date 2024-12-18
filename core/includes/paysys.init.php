<?php

function initializePaysys($BASE) {
    require_once(_PAYSYS_ . '/paysys.php');
    $all_paysys = glob(_PAYSYS_ . '/*.paysys.php');
    $PAYSYS = [];

    foreach ($all_paysys as $one) {
        require_once($one);
        $ps_name = strtolower(str_replace('.paysys.php', '', basename($one)));
        $class_name = ucfirst($ps_name) . 'Paysys';
        $PAYSYS[$ps_name] = new $class_name($BASE);
    }

    return $PAYSYS;
}
