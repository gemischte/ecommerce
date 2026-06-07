<?php

namespace App\Services;

use mysqli;
use App\Utils\Logger;

class CartService
{
    private mysqli $conn;
    private array $cart;
    
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
        $this->cart = &$_SESSION['cart'];
    }

    private function sanitize_int($value): int
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public function add_product_to_cart($product_id, $quantity)
    {
        $product_id = $this->sanitize_int($product_id);
        $quantity = $this->sanitize_int($quantity);

        if($product_id<=0 || $quantity <= 0){
            return;
        }

        $max_qty = 5;
        $current_qty = $this->cart[$product_id] ?? 0;
        $cart_qty = $current_qty + $quantity;
        $this->cart[$product_id] = min($cart_qty, $max_qty);
    }

    public function remove_cart_product($product_id)
    {
        $product_id = $this->sanitize_int($product_id);
        unset($this->cart[$product_id]);
    }

    public function delete_cart_qty($product_id, $quantity)
    {
        $product_id = $this->sanitize_int($product_id);
        $quantity = $this->sanitize_int($quantity);

        if ($product_id > 0 && $quantity > 0 && isset($this->cart[$product_id])) {
            $this->cart[$product_id] -= $quantity;
        }

        if ($this->cart[$product_id] <= 0) {
            unset($this->cart[$product_id]);
        }
    }

    /**
     * @param float $price Unit price
     * @param int $qty Quantity
     * @param float $tax Tax rate,default 5%
     * @return array{subtotal: float, tax: float, total: float}
     */
    public function calc_cart_totals($price, $qty, $tax = 0.05)
    {

        $subtotal = $price * $qty;
        $tax = $subtotal * $tax;
        $total = $subtotal + $tax;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ];
    }

    /**
     * @param int $product_id Product Id
     * @param int $quantity Product Quantity
     * @return void
     */
    public function update_product_stock($product_id, $purchase_qty)
    {
        $update = "UPDATE products SET stock = stock - ? WHERE product_id = ? AND stock >= ?";
        $stmt = $this->conn->prepare($update);
        if (!$stmt) {
            Logger::error("Prepare failed:" . $this->conn->error);
            return;
        }
        $stmt->bind_param('iii', $purchase_qty, $product_id, $purchase_qty);
        $stmt->execute();
        if (!$stmt) {
            Logger::error("Execute failed:" . $stmt->error);
            return;
        }
    }
}
