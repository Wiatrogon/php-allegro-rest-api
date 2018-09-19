<?php
ini_set('display_errors', 1);
error_reporting(1);
require_once ('src/Resource.php');
require_once ('src/Api.php');
require_once ('src/Sandbox.php');
require_once ('src/Commands.php');
echo '<p>'.date("H:i:s") .'</p><pre>';
//echo (json_encode(yaml_parse_file('src/swagger.yaml')));
$api = new Allegro\REST\Sandbox(
    '1f48c896f1b2426d89340f168f014d1c',
    'zAQN0Fzvn0XDglXzzpL3xejDjP9OG7VlgpmJDUMmGgEXzndLB91C56mDMdEJxQKJ',
    '1f48c896f1b2426d89340f168f014d1c',
    'https://www.getpostman.com/oauth2/callback',
    'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1MzczMDYyMTksInVzZXJfbmFtZSI6IjQzOTc5MTAxIiwianRpIjoiZWE3ZTZjYWQtY2FjYi00NjAyLWFiYzYtNmNlN2UzOWEwYjJiIiwiY2xpZW50X2lkIjoiMWY0OGM4OTZmMWIyNDI2ZDg5MzQwZjE2OGYwMTRkMWMiLCJzY29wZSI6WyJhbGxlZ3JvX2FwaSJdfQ.Q-gjLn_1_Sh2WSiJy8yUWOv8NJm2_HAOKamVtE-Vlsy9kU3BG8z-VyxXCIynYk95hfuD6-eBcBtDgl6ARFYMDm3osiREvAiAoqnh0K2fs7DRKSuDeS4HoKcB-5VJOy6WcEFZSjeu0hNi-YxSF4eaSYa-LcKmRtzYH2o1j34gQ-XjYi6oFkTUAtHFQBdXhcFXkPuYgXMwCzbVZ8f1fBK9xSC-ln_fwxOFw02XCP1MFwQvfRvE2H1v6NDFJKRKFxQNGxICLyL8XhQmPgZqrOzvprkZxbGmRamkcBa8ITMSPGtEMC0ZPd89Sh8AbCIgUZlCc228qAErB8mm903yXIEcYw',
    'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1Njg3OTkwMTksInVzZXJfbmFtZSI6IjQzOTc5MTAxIiwianRpIjoiZWVkNDc1MjItZDJlYS00ZGUzLTljYTUtMDkzZTVjYTExZDk1IiwiY2xpZW50X2lkIjoiMWY0OGM4OTZmMWIyNDI2ZDg5MzQwZjE2OGYwMTRkMWMiLCJzY29wZSI6WyJhbGxlZ3JvX2FwaSJdLCJhdGkiOiJlYTdlNmNhZC1jYWNiLTQ2MDItYWJjNi02Y2U3ZTM5YTBiMmIifQ.FcHMQ9FqM-RuFH-nIaYwG-1KOJMHcRgXg2lAag7jGA5cWPg16sXvDxOCXCwHzTDB_MI2b-21wlmSZcmGrzf7qypuOJMcQs3fb_AP1n-KYLrl-yOoFQpu0QA9HBNYQkJUq9zbA5anT7EdmFgcqgt5KxKE8eD3s5Jjnn6PsI_AvvrbntwKHmBRFzRO6etTppLk-O5OpLuEoMW0dcco8sLbGkYgyewz04U2DYW_NHcraW1evuMAPa2tbAnAU75ndV0StRgnGE1ogWg1ZOW6-96z-ez1cVrmulbQawgZi8icuhG4MpkzIS39onSBaZEeHOLcOW6vj-mKylRijl3s5wf7Ow'
);
$res=$api->offers->listing->get(
    array(
        'seller.id' => '43979101'
    )
);
var_dump($res);

$res=$api->sale->offers(6205584447)->get();
var_dump($res);
echo '</pre>'.date("H:i:s");