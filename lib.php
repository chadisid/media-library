<?php
include "config.php";


function empty_temp_files($temp) {
    $files = glob($temp.'/*'); // get all file names
    foreach($files as $file){ // iterate files
        unlink($file); // delete file
    }
}

function create_m3u_from_file($file, $temp) {
        $actual_link = "http://$GLOBALS[ROOT_URI]";
        $filename = basename($file);
        $hash = md5($filename);
        symlink($file,'temp/'.$hash);
        #create text to write into m3u file
        $content = "#EXTM3U\n\n";
        $content .= "#EXTINF:-1, $filename\n";
        $content .= "$actual_link".str_replace(' ','%20',"temp/$hash");
        echo "<li><a href='$actual_link"."temp/$hash.m3u'>";
        #look for an image
        $image = '';
        $s = dirname($file).'/'.pathinfo($file)['filename'].'*';
        $images = glob($s) ;
        foreach($images as $imgfile) {
            if (in_array(pathinfo($imgfile)['extension'], $GLOBALS['IMAGES_EXTENSION']))
                $image = $imgfile;
        }
        if ($image == '') $image = $GLOBALS['default_movie_img'];
        else {
            #make symlink
            $imgext = pathinfo($image)['extension'];
            symlink($image, "temp/$hash.$imgext");
            $image = "temp/$hash.$imgext";
        }
        #output
        echo "<img width='300' height='300' src='$image'/>$filename</a><br></li>";
        File_put_contents('temp/'.$hash.'.m3u',$content);
}

function create_m3u_from_dir($dir, $temp) {
        $actual_link = "http://$GLOBALS[ROOT_URI]";
        $dirname = basename($dir);
        $hash = md5($dirname);
        symlink($dir,"temp/$hash");
        $content = "#EXTM3U\n\n";
        $files = glob("$dir/*");
        foreach($files as $file) {
            $filename = basename($file);
            $content .= "#EXTINF:-1, $filename\n";
            $content .= "$actual_link".str_replace(' ','%20',"temp/$hash/$filename\n");
        }
        #look for an image
        $images = glob("$dir*");
        $image = '';
        foreach($images as $imgfile) {
            if (!is_dir($imgfile)) 
                if (in_array(pathinfo($imgfile)['extension']
                    ,$GLOBALS['IMAGES_EXTENSION']))
                    $image = $imgfile;
        }
        if ($image == '') $image = $GLOBALS['default_folder_img'];
        echo "<li><a href='$actual_link"."temp/$hash.m3u'>";
        echo "<img src='$image' />$dirname</a></li><br>";
        file_put_contents('temp/'.$hash.'.m3u',$content);
}

function private_zone() {
    session_start();
    if (!isset($_SESSION['username']))
        header("location: login.php");
}
function public_zone() {
    session_start();
    if (isset($_SESSION['username']))
        header("location: index.php");
}
?>