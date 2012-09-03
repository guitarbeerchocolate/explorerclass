<?php
/*
Name:Marius Buivydas
Packages:Browse all classes by Marius Buivydas
Country:Lithuania
See:http://www.phpclasses.org/package/1314-PHP-Browse-a-given-directory.html
*/

require_once 'explorer.class.php';
$expl = new explorer;
$expl->Set("root_dir","./");
$expl->Set("icons_dir","icons/");
$types['image.png'] = array ('jpg', 'gif');
$types['png.png'] = array ('png');
$types['php.png'] = array ('php');
$expl->Set("types",$types);
$expl->Set("un_icon","file.png");
$expl->Set("dir_icon","directory.png");
$expl->show_dirs();
?>