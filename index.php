<?php
// Amazon URL
$amazonURL = 'https://www.amazon.com/stores/DressTheYard/DressTheYard/page/CBC9241E-A816-48AB-8849-CB1F5A3F9A9F?ref_=cm_sw_r_ud_sf_stores_YZHDCZYCK5VFZWM2TX8E';

// Fetch Amazon content
$amazonContent = file_get_contents($amazonURL);

// Modify links to include affiliate tag
$modifiedContent = str_replace('https://www.amazon.com', 'https://www.amazon.com&tag=ctl0d3d-20', $amazonContent);

// Save modified content to a file
file_put_contents('modified_amazon_content.html', $modifiedContent);
