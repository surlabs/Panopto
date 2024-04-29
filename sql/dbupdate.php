<#1>
<?php
global $DIC;
$db = $DIC->database();
if (!$db->tableExists('xpan_settings')) {
    $fields = array(
        'obj_id' => array('type' => 'integer', 'length' => 8),
        'is_online' => array('type' => 'integer', 'length' => 1),
        'folder_ext_id' => array('type' => 'integer', 'length' => 8)
    );

    $db->createTable('xpan_settings', $fields);
    $db->addPrimaryKey('xpan_settings', array('obj_id'));
}
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
// Unnecessary step because the table rep_robj_srtr_entry always seems to be empty
?>
<#4>
<?php
// Unnecessary step because all necessary xpan_setting columns have been added in step one and the columns needed for rework or future upgrades will be added from step 5.
?>
<#5>
<?php
// Current Version
?>