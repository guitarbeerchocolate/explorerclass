<?php
//*****************************************************************
//** Author: Marius Buivydas                                     **
//** E-mail: mairo@takas.lt                                      **
//** Created: 2003-09-07                                         **
//** Last update: 2004-01-30                                     **
//**                                                             **
//**                                                             **
//**  This class is meant to browse a given directory.           **
//**  It allows you to view or download the directory files      **
//**  or other files from sub-directories                        **
//**                                                             **
//*****************************************************************



class explorer {

var $types; 
//array, which keys are the name of file (icon)
//and value - extensions which suit this icon

var $un_icon;
//this variable saves name of unknown file type icon
var $dir_icon;
//this variable saves name of directory icon
var $root_dir;
//this variable saves the path of directory which will be seen as root directory.
var $icons_dir;
//this variable saves the path of icons directory (where icons will be kept).


Function Set($varname,$value) { 
        $this->$varname=$value;
} 


function get_file_type($filename) {
    $filename=strtolower($filename);// file name is changed to lowercase
    ereg( ".*\.([a-zA-z0-9]{0,5})$", $filename, $regs ); //gets extension of file
    $f_ext = $regs[1];
	
	switch ($f_ext) //known file types
	 {
	   case "jpg": $cont_type="image/jpeg"; break;
	   case "jpeg": $cont_type="image/jpeg"; break;
	   case "gif": $cont_type="image/gif"; break;
	   case "png": $cont_type="image/png"; break;
	   case "txt": $cont_type="text/plain"; break;
       case "html": $cont_type="text/html"; break;
       case "htm": $cont_type="text/html"; break;
       case "doc": $cont_type="application/msword";break;
       case "exe": $cont_type="application/octet-stream";break;
       case "pdf": $cont_type="application/pdf";break;
       case "ai": $cont_type="application/postscript";break;
       case "eps": $cont_type="application/postscript";break;
       case "ps": $cont_type="application/postscript";break;
       case "xls": $cont_type="application/vnd.ms-excel";break;
       case "ppt": $cont_type="application/vnd.ms-powerpoint";break;
       case "zip": $cont_type="application/zip";break;
       case "mid": $cont_type="audio/midi";break;
       case "kar": $cont_type="audio/midi";break;
       case "mp3": $cont_type="audio/mpeg";break;
       case "wav": $cont_type="audio/x-wav";break;
       case "bmp": $cont_type="image/bmp";break;
       case "tiff": $cont_type="image/tiff";break;
       case "tif": $cont_type="image/tiff";break;
       case "asc": $cont_type="text/plain";break;
       case "rtf": $cont_type="text/rtf";break;
       case "mpeg": $cont_type="video/mpeg";break;
       case "mpg": $cont_type="video/mpeg";break;
       case "mpe": $cont_type="video/mpeg";break;
       case "avi": $cont_type="video/x-msvideo";break;

	 }
	
    foreach ($this->types as $k => $v) {
        if (in_array($f_ext, $v)) {
            return array($k,$cont_type); //returns the type and icon of recognized file 
            break;
        }
    }
    return array($this->un_icon,$cont_type); //returns the type and icon of unrecognized file
}


function show_dirs() {

$source=$_GET['source']; // it can be directory or file on which is clicked
$type=$_GET['type']; //this is the type of object on which was clicked (it can be file or dir (directory)
$path=$_GET['path']; //this is the virtual path of directory which we have left.
$kryptis=$_GET['kryptis']; //this is direction of action. It can be pirmyn (forward) if we open directory and other value this variable can be atgal (back) if we press on .. (we will go back from directory).
$cont_type=$_GET['cont_type']; //type of the file

$source=stripslashes($source);
$path=stripslashes($path);

if ($type=="file")
  {
   if ($cont_type=="")
     {
       header("Content-type: application/force-download");
       header("Content-Disposition: attachment; filename=$source");
	 }
   else
     {
       header("Content-type: $cont_type");
      // @readfile($this->root_dir.$path.$source);
       $fp = fopen($this->root_dir.$path.$source, 'r');
       fpassthru($fp);
	   die();
     }
  }
if (preg_match("/\/\.\.\//",$path)) // protection, that it would be impossible to explore other (not allowed) directories
 {
   $path="/";
 }
if ($source=="") 
  {
    $path="/";
    $root=true;
  }
else
  {
    if ($type=="dir") //(type==directory)
	  {
        if ($kryptis=="pirmyn")//(direction==forward) clicked on directory
		  {
            $path=$path.$source."/";
          }
        elseif($kryptis=="atgal") //(direction == "back") clicked on ..
		  {
            preg_match_all("/^\/(.+)/", $path, $out);// strips first char "/"
            $path=$out[1][0];
            preg_match_all("/(.+)\/.+\/$/", $path, $out);// strips from the path the last directory
            if ($out[1][0]!="")
              {$path="/".$out[1][0]."/";}
            else 
              {
			   $path="/".$out[1][0];
  			   $root=true;
			  }
		  }
      }
  }
  explorer::set("root_dir",$this->root_dir.$path);
  if (is_dir($this->root_dir)) 
     {
       if ($dh = opendir($this->root_dir)) 
	     {
           while (($file = readdir($dh)) !== false) 
		     {
			    if (filetype($this->root_dir .$file)=="dir")
				  {$turinys["tipas"][]="dir";}//array["type"][]="directory"
			    else
				  {$turinys["tipas"][]="file";}//array["type"][]="file"
			    $turinys["pavadinimas"][]=$file;//array["name"][]=$file
             }
           closedir($dh);
       	   array_multisort($turinys["tipas"], SORT_ASC,$turinys["pavadinimas"]);
		   if (!$root)
		     {$i=1;}
		   else
		     {$i=2;}
           for ($i;$i<count($turinys["tipas"]);$i++)
		     {
		       if ($turinys["tipas"][$i]=="dir")
			     {
				   echo "<img src=\"".$this->icons_dir.$this->dir_icon."\">";
				   if ($i==1 and $turinys["pavadinimas"][$i]=="..")
				     {echo "&nbsp; <a href=\"?source=".$turinys["pavadinimas"][$i]."&amp;type=dir&amp;kryptis=atgal&amp;path=$path\">".$turinys["pavadinimas"][$i]."</a> <br>";}
				   else
		   	         {echo "&nbsp; <a href=\"?source=".$turinys["pavadinimas"][$i]."&amp;type=dir&amp;kryptis=pirmyn&amp;path=$path\">".$turinys["pavadinimas"][$i]."</a> <br>";}
				 }
			   else
			     {
				   $file_type=explorer::get_file_type($turinys["pavadinimas"][$i]);
				   $icon=$file_type[0];
				   $cont_type=$file_type[1];
				   echo "<img src=\"".$this->icons_dir."$icon\">";
			       echo "&nbsp; <a href=\"?source=".$turinys["pavadinimas"][$i]."&amp;type=file&amp;path=$path&amp;cont_type=$cont_type\">".$turinys["pavadinimas"][$i]."</a> <br>";
			    }
		     }
	     }
     }

} 


}


?>