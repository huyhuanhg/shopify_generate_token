<?php
session_start();

$shop = $_GET['shop'];
$app = $_GET['app'];

if (!$app || !$shop) {
    exit();
}

$config = file_get_contents('./app.json');

$config = json_decode($config, true);


$appConfig = $config[array_search($app, array_column($config, "app_name"))];

// Set variables for our request
if (!$appConfig['SHOPIFY_API_KEY'] || !$appConfig['SHOPIFY_API_SECRET']) {
    exit();
}

$_SESSION['SHOPIFY_API_KEY'] = $appConfig['SHOPIFY_API_KEY'];
$_SESSION['SHOPIFY_API_SECRET'] = $appConfig['SHOPIFY_API_SECRET'];


$api_key = $appConfig['SHOPIFY_API_KEY'];

$scopes = implode(',', $appConfig['SCOPES']);

$redirect_uri = "http://localhost/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();
