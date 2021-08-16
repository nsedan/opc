<?php 

$path = 'https://originalposter.com/wp-content/plugins/csv-wc-orders-opc/csv-exports/'; 
$contents = file_get_contents( $path );
preg_match_all('/<a[^>]*href=[\"|\'](.*)[\"|\']/Ui', $contents, $out, PREG_PATTERN_ORDER);

// Step through all href's
foreach ( $out[1] as $v ){
    // Output a link to the path
    if (strpos($v, 'sale') !== false){
        // Initialize a file URL to the variable 
        $url = $path.$v;
        
        // Initialize the cURL session 
        $ch = curl_init($url); 
        
        // Inintialize directory name where 
        // file will be save 
        $dir = '../sorders/'; 
        
        // Use basename() function to return 
        // the base name of file  
        $file_name = basename($url); 
        
        // Save file into file location 
        $save_file_loc = $dir . $file_name; 
        
        // Open file  
        $fp = fopen($save_file_loc, 'wb'); 
        
        // It set an option for a cURL transfer 
        curl_setopt($ch, CURLOPT_FILE, $fp); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        
        // Perform a cURL session 
        curl_exec($ch); 
        
        // Closes a cURL session and frees all resources 
        curl_close($ch); 
        
        // Close file 
        fclose($fp); 
    }
}

  
?> 