<?php
// Amazon URL
$amazonURL = 'https://www.amazon.com/stores/DressTheYard/DressTheYard/page/CBC9241E-A816-48AB-8849-CB1F5A3F9A9F?ref_=cm_sw_r_ud_sf_stores_YZHDCZYCK5VFZWM2TX8E';

// Fetch Amazon content
$amazonContent = file_get_contents($amazonURL);

// Parse and modify Amazon content to add affiliate tag
$dom = new DOMDocument();
@$dom->loadHTML($amazonContent); // Suppress warnings

// Find all links in the document
$links = $dom->getElementsByTagName('a');
foreach ($links as $link) {
    $href = $link->getAttribute('href');
    if (strpos($href, 'https://www.amazon.com') === 0) {
        // Append affiliate tag to Amazon product links
        $link->setAttribute('href', $href . '&tag=ctl0d3d-20');
    }
}

// Output modified Amazon content
echo $dom->saveHTML();
?>
