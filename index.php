<?php
/*
Name:Marius Buivydas
Packages:Browse all classes by Marius Buivydas
Country:Lithuania
See:http://www.phpclasses.org/package/1314-PHP-Browse-a-given-directory.html
*/

require_once 'classes/autoload.php';
$expl = new explorer;
$config = new config;
$expl->Set("root_dir",$config->values->ROOT_DIR);
$expl->Set("icons_dir",$config->values->ICON_DIR);
$types['image.png'] = array ('jpg', 'gif');
$types['png.png'] = array ('png');
$types['php.png'] = array ('php');
$expl->Set("types",$types);
$expl->Set("un_icon",$config->values->UN_ICON);
$expl->Set("dir_icon",$config->values->DIR_ICON);
$expl->show_dirs();
?>