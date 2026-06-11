<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$target = '/home/u843588372/domains/nexobizz.com/public_html/storage/app/public';
$link = '/home/u843588372/domains/nexobizz.com/public_html/public/storage';

if (symlink($target, $link)) {
    echo "Symlink created successfully!";
} else {
    echo "Failed to create symlink.";
}
?>