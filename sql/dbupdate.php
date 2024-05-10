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
        'ref_id' => array('type' => 'integer', 'length' => 8),
        'position' => array('type' => 'integer', 'length' => 8),
        'session_id' => array('type' => 'text', 'length' => 255),
    );

    $db->createTable('xpan_order', $fields);
    $db->addPrimaryKey('xpan_order', array('ref_id', 'session_id'));
}

if ($db->tableExists('rep_robj_srtr_entry')) {
    $rows = $db->query('SELECT * FROM rep_robj_srtr_entry');

    foreach ($rows as $row) {
        $db->insert('xpan_order', array(
            'ref_id' => $row['ref_id'],
            'position' => $row['precedence'],
            'session_id' => $row['session_id']
        ));
    }

    $db->dropTable('rep_robj_srtr_entry');
}
?>