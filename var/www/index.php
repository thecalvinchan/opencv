<?php
    function generateCSV($glass_user, $pictures) {
        //$pictures is an associative array
        //$id => array[$picture_url]
        $extension = '.jpg';
        $prefix = '/tmp/';
        if (!mkdir($prefix . $glass_user, 0755, true)) {
            die("Failed to create folders\n");
        } else {
            echo "Created folder for user $glass_user";
        }
        $csv = ''
        //Curls each photo and saves it
        //Generates CSV string
        foreach ($pictures as $fb_id => $pics) {
            $picture_num = 0;
            foreach ($pics as $picture) {
                $filename = $prefix . $glass_user . $fb_id . '_' . $picture_num . $extension;
                $ch = curl_init();
                $fp = fopen($filename,'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_URL, $picture);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $rawdata = curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                $csv .= $filename . ';' . $fb_id . "\n";
                $picture_num++;
            }
        }
        $csv_file = fopen($prefix . $glass_user . '_' . 'data.csv','wb');
        fwrite($csv_file,$csv);
        fclose($csv_file);
    }
?>
