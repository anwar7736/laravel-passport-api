<?php

namespace App\Utils;

class Util {
    public function upload_image($folder_name = null, $image = null)
    {
        if($folder_name != null && $image != null)
        {
            $new_image_name = rand().'.'.$image->extension();
            $image->move(public_path($folder_name), $new_image_name);
            return $new_image_name;
        }
       
    } 
    
    public function delete_image($folder_name = null, $image_name = null)
    {
        if($folder_name != null && $image_name != null)
        {
            $image_path = public_path($folder_name."/".$image_name);
            if(file_exists($image_path))
            {
                unlink($image_path);
                return 1;
            }

        }
       
    } 
}
