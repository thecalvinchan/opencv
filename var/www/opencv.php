<?php

    function generateCSV($glass_user, $pictures) {
        //$pictures is an associative array
        //$id => array[$picture_url]
        $extension = '.jpg';
        $prefix = '/tmp/originals/'.$glass_user.'/';
        try {
            mkdir($prefix, 0755, true);
            mkdir('/tmp/data/',0755,true);
            mkdir('/tmp/resized/'.$glass_user.'/',0755,true);
            //echo "Created folder for user $glass_user";
        } catch (Exception $e) {
            echo "Error: $e";
        }
        $csv = '';
        //Curls each photo and saves it
        //Generates CSV string
        foreach ($pictures as $fb_id => $pics) {
            $picture_num = 0;
            $crop_string = '';
            foreach ($pics as $picture) {
                $filename = $prefix . $glass_user . $fb_id . '_' . $picture_num . $extension;
                //echo $filename;
                $ch = curl_init();
                $fp = fopen($filename,'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_URL, $picture);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $rawdata = curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                $crop_string .= ' ' . $filename;
                $picture_num++;
            }
            $results = shell_exec('python /home/ubuntu/face/face_detect.py --owner ' . $glass_user . $crop_string);
            //echo $results;
            foreach (json_decode($results) as $file) {
                $csv .= $file . ';' . $fb_id . "\n";
            }
        }
        $csv_file = fopen('/tmp/data/' . $glass_user . '_data.csv','wb');
        fwrite($csv_file,$csv);
        fclose($csv_file);
        shell_exec('rm -r /tmp/originals/'.$glass_user.'/');
        //shell_exec('rm -r /tmp/resized/'.$glass_user.'/');
        generateClassifier($glass_user,'/tmp/data/'.$glass_user.'_data.csv');
    }

    function generateClassifier($glass_user, $csv_link) {
        shell_exec('/home/ubuntu/face/./classifier ' . $csv_link . ' /tmp/data/classifier_' . $glass_user . '.yml');
        shell_exec('rm /tmp/data/'.$glass_user.'_data.csv');
        shell_exec('rm -r /tmp/resized/'.$glass_user.'/');
    }

    //generateCSV('1372209588',$test_data);
?>
