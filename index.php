<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Amazon URL
$amazonURL = 'https://www.amazon.com/stores/DressTheYard/DressTheYard/page/CBC9241E-A816-48AB-8849-CB1F5A3F9A9F?ref_=cm_sw_r_ud_sf_stores_YZHDCZYCK5VFZWM2TX8E';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch Amazon content
    $amazonContent = fetchAmazonContent($amazonURL);

    // Check if content is fetched successfully
    if ($amazonContent !== false) {
        // Modify links to include affiliate tag
        $modifiedContent = modifyAmazonContent($amazonContent);
        
        // Save modified content to a file
        file_put_contents('amazon_content.html', $modifiedContent);
        http_response_code(200); // Set HTTP response code to 200 (OK)
        echo "Amazon content updated successfully.";
    } else {
        http_response_code(500); // Set HTTP response code to 500 (Internal Server Error)
        echo "Failed to fetch Amazon content.";
    }
} else {
    http_response_code(405); // Set HTTP response code to 405 (Method Not Allowed)
    echo "Method not allowed.";
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

// Function to modify Amazon content to include affiliate tag
function modifyAmazonContent($content) {
    // Parse the HTML content
    $dom = new DOMDocument();
    @$dom->loadHTML($content); // Suppress warnings
    
    // Find all links in the document
    $links = $dom->getElementsByTagName('a');
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        // Append affiliate tag to Amazon product links
        if (strpos($href, 'https://www.amazon.com') === 0) {
            $link->setAttribute('href', $href . '&tag=ctl0d3d-20');
        }
    }

    // Output modified Amazon content
    return $dom->saveHTML();
}
