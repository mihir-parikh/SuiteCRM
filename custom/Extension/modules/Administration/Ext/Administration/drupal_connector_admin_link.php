<?php
$links = array();
$links['dc_drupal_connector']['link'] = array(
    // An image from /themes/SuiteP/images
    'Releases',
    'Drupal Connector configuration',
    'Drupal Connector configuration & settings',
    './index.php?module=dc_drupal_connector&action=editview'
);

// Add our new admin section to the main admin_group_header array
$admin_group_header[] = array(
    'Drupal Connector',
    // Leave empty, it is used in /include/utils/layout_utils.php
    '',
    // Set to false, it is used in /include/utils/layout_utils.php
    'false',
    $links,
    // Description
    ''
);