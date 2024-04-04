<?php
// Define the file path
$filePath = 'modified_amazon_content.html';

// Check if the file needs to be updated (once a day)
if (!file_exists($filePath) || filemtime($filePath) < strtotime('yesterday')) {
    // Amazon URL
    $amazonURL = 'https://www.amazon.com/stores/DressTheYard/DressTheYard/page/CBC9241E-A816-48AB-8849-CB1F5A3F9A9F?ref_=cm_sw_r_ud_sf_stores_YZHDCZYCK5VFZWM2TX8E';

    // Fetch Amazon content
    $amazonContent = file_get_contents($amazonURL);

    // Check if content is retrieved successfully
    if ($amazonContent !== false) {
        // Specify the correct encoding (UTF-8)
        $amazonContent = mb_convert_encoding($amazonContent, 'HTML-ENTITIES', "UTF-8");

        // Save modified content to a file
        file_put_contents($filePath, $amazonContent);
    } else {
        echo "Failed to fetch Amazon content.";
    }
}
