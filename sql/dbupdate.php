<#1>
<?php
global $DIC;
$db = $DIC->database();
if (!$db->tableExists('xpan_config')) {
    $fields = array(
        'name' => array('type' => 'text', 'length' => 255),
        'value' => array('type' => 'text', 'length' => 255)
    );

    $db->createTable('xpan_config', $fields);
    $db->addPrimaryKey('xpan_config', array('name'));
}
?>
<#2>
<?php
// Unnecessary step because now we will use the language files
?>
<#3>
<?php
// Unnecessary step because the table rep_robj_srtr_entry will be replaced by the table xpan_order
?>
<#4>
<?php
// Unnecessary step because the table xpan_session will be removed
?>
<#5>
<?php
global $DIC;
$db = $DIC->database();
if (!$db->tableExists('xpan_order')) {
    $fields = array(
        'obj_id' => array('type' => 'integer', 'length' => 8),
        'precedence' => array('type' => 'integer', 'length' => 8),
        'session_id' => array('type' => 'text', 'length' => 255),
    );

    $db->createTable('xpan_order', $fields);
    $db->addPrimaryKey('xpan_order', array('obj_id', 'session_id'));
}
?>