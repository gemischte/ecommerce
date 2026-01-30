<?php

namespace APP\Payments\Stripe;

require_once __DIR__ . '/../../../core/init.php';

use App\Security\Csrf;
use App\Services\CartService;
use Dotenv\Dotenv;
use mysqli;

$CartService = new CartService($conn);

class StripeGateway
{
    public static function handleCheckout(mysqli $conn)
    {
        $CartService = new CartService($conn);
        $dotenv = Dotenv::createImmutable(__DIR__ . '../../../../', '.env');
        $dotenv->load();
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $stripe_payment = "SELECT product_id, product_images, product_name, price 
                FROM products WHERE product_id = ?";
            $stmt = $conn->prepare($stripe_payment);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $CartService->update_product_stock($product_id, $quantity);

            if ($result->num_rows > 0) 
            {
                $row = $result->fetch_assoc();
                $product_names = $row['product_name'];
                $price = $row['price'];
                $product_images = $row['product_images'];

                $line_items[] =
                    [
                        "quantity" => $quantity,
                        "price_data" => [
                            "currency" => "usd",
                            "unit_amount" => $price * 100,
                            "product_data" => [
                                "name" => $product_names,
                                "images" => [
                                    $product_images
                                ]
                            ]
                        ]
                    ];
            }

            $checkout_session = \Stripe\Checkout\Session::create([
                "mode" => "payment",
                "success_url" => WEBSITE_URL . "success.php",
                "cancel_url" => WEBSITE_URL . "views/checkout.php",
                "locale" => "auto",
                "line_items" => $line_items
            ]);

            http_response_code(303);
            header("Location: " . $checkout_session->url);
        }
    }
}

if (basename($_SERVER['SCRIPT_FILENAME']) === 'StripeGateway.php') {
    StripeGateway::handleCheckout($conn);
}