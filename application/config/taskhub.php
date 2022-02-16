<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['progress_bar_classes']['todo'] = 'info';
$config['progress_bar_classes']['inprogress'] = 'danger';
$config['progress_bar_classes']['review'] = 'warning';
$config['progress_bar_classes']['done']  = 'success';


$config['allowed_types'] = 'gif|jpg|jpeg|png|bmp|eps|mp4|mp3|wav|3gp|avchd|avi|flv|mkv|mov|webm|wmv|mpg|mpeg|ogg|doc|docx|txt|pdf|ppt|pptx|xls|xsls|zip|7z|bz2|gz|gzip|rar|tar';

$config['type'] = array(
    'image' => array(
        'types' => array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'eps'),
        'icon' => ''
    ),
    'video' => array(
        'types' => array('mp4', '3gp', 'avchd', 'avi', 'flv', 'mkv', 'mov', 'webm', 'wmv', 'mpg', 'mpeg', 'ogg'),
        'icon' => 'assets/admin/images/video-file.png'
    ),
    'document' => array(
        'types' => array('doc', 'docx', 'txt', 'pdf', 'ppt', 'pptx'),
        'icon' => 'assets/admin/images/doc-file.png'
    ),
    'spreadsheet' => array(
        'types' => array('xls', 'xsls'),
        'icon' => 'assets/admin/images/xls-file.png'
    ),
    'archive' => array(
        'types' => array('zip', '7z', 'bz2', 'gz', 'gzip', 'rar', 'tar'),
        'icon' => 'assets/admin/images/zip-file.png'
    )
);