<?php
session_start();
include_once '../includes/config.php';

require __DIR__ . "/../../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../','.env');
$dotenv->load();
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];

\Stripe\Stripe::setApiKey($stripe_secret_key);

foreach ($_SESSION['cart'] as $product_id => $quantity){
  $stripe_payment = "SELECT product_id, product_images, product_name, price FROM products WHERE product_id = ?";
  $stmt = $conn->prepare($stripe_payment);
  $stmt ->bind_param("i", $product_id);
  $stmt ->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0){
    $row = $result ->fetch_assoc();
    $product_names = $row['product_name'];
    $price = $row['price'];
    $product_images = $row['product_images'];

    $line_items[] = 
    [  
        "quantity" => "$quantity",
        "price_data" => [
          "currency" => "usd",
          "unit_amount" => $price * 100,
          "product_data" => [
            "name" => "$product_names",
            "images" => [
              $product_images
            ]
          ]
        ]
    ];
    
  }
}
$checkout_session = \Stripe\Checkout\Session::create([
  "mode" => "payment",
  "success_url" => "http://localhost/success.php",
  "cancel_url" => "http://localhost/index.php",
  "locale" => "auto",
  "line_items" => $line_items
]);


http_response_code(303);
header("Location: " . $checkout_session->url);
