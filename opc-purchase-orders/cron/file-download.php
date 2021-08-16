<?php 
// Task scheduler example config:
// C:\xampp\php\php.exe     -> Program / Script
// file-download.php        -> Arguments / Filename
// C:\C5Files\Scripts\      -> Start in / File location


// Initialize a file URL to the variable 
$url = 'https://originalposter.info/wp-content/plugins/purchase-order-opc/csv-exports/porders.csv'; 
  
// Initialize the cURL session 
$ch = curl_init($url); 
  
// Inintialize directory name where 
// file will be save 
$dir = './'; 
  
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
  
?> 