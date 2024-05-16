<#1>
<?php
global $DIC;
$db = $DIC->database();
if (!$db->tableExists('xpan_config')) {
    $fields = array(
        'name' => array('type' => 'text', 'length' => 255),
        'value' => array('type' => 'text', 'length' => 4000)
    );

    $db->createTable('xpan_config', $fields);
    $db->addPrimaryKey('xpan_config', array('name'));
}
if (!$db->tableExists('xpan_objects')) {
    $fields = array(
        'obj_id' => array('type' => 'integer', 'length' => 8),
        'is_online' => array('type' => 'integer', 'length' => 1),
        'folder_ext_id' => array('type' => 'integer', 'length' => 8),
    );

    $db->createTable('xpan_objects', $fields);
    $db->addPrimaryKey('xpan_objects', array('obj_id'));
}
?>
<#2>
<#3>
<#4>
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
<#6>
<?php
global $DIC;
$db = $DIC->database();
if ($db->tableExists('xpan_settings')) {
    $db->renameTable('xpan_settings', 'xpan_objects');
}
?>