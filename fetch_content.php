<?php
// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the request is for more content
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'fetchMoreContent') {
    // Fetch and output Amazon content dynamically
    fetchAndOutputAmazonContent();
} else {
    echo "Invalid request.";
}

// Function to fetch Amazon content with handling for encoding and compression
function fetchAmazonContent($url) {
    // Fetch Amazon content
    $options = [
        'http' => [
            'header' => "Accept-Encoding: gzip\r\n",
            'timeout' => 10, // Reduced timeout for faster response
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

// Function to fetch and output Amazon content dynamically
function fetchAndOutputAmazonContent() {
    // Amazon URL
    $amazonURL = 'https://www.amazon.com/stores/DressTheYard/DressTheYard/page/CBC9241E-A816-48AB-8849-CB1F5A3F9A9F?ref_=cm_sw_r_ud_sf_stores_YZHDCZYCK5VFZWM2TX8E';

    // Fetch Amazon content
    $amazonContent = fetchAmazonContent($amazonURL);

    // Check if content is fetched successfully
    if ($amazonContent !== false) {
        // Modify links to include affiliate tag
        $modifiedContent = modifyAmazonContent($amazonContent);
        echo $modifiedContent;
    } else {
        echo "Failed to fetch Amazon content.";
    }
}
