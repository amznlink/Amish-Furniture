<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Amazon URL
$amazonURL = 'https://www.amazon.com/stores/DressTheYard/DressTheYard/page/CBC9241E-A816-48AB-8849-CB1F5A3F9A9F?ref_=cm_sw_r_ud_sf_stores_YZHDCZYCK5VFZWM2TX8E';

// Fetch Amazon content
$amazonContent = fetchAmazonContent($amazonURL);

// Check if content is fetched successfully
if ($amazonContent !== false) {
    // Save content to a file
    file_put_contents('amazon_content.html', $amazonContent);
    echo "Amazon content saved successfully.";
} else {
    echo "Failed to fetch Amazon content.";
}

// Function to fetch Amazon content with handling for encoding and compression
function fetchAmazonContent($url) {
    // Fetch Amazon content
    $options = [
        'http' => [
            'header' => "Accept-Encoding: gzip\r\n",
            'timeout' => 30, // Adjust timeout as needed
        ],
    ];
    $context = stream_context_create($options);
    $amazonContent = file_get_contents($url, false, $context);

    // Check if content is retrieved successfully
    if ($amazonContent !== false) {
        // Check if content is gzipped and decompress if necessary
        if (strpos($amazonContent, "\x1f\x8b\x08") === 0) {
            $amazonContent = gzdecode($amazonContent);
        }
        return $amazonContent;
    } else {
        return false;
    }
}
?>
