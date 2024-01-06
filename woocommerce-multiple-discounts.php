<?php
/*
Plugin Name: WooCommerce Quantity Discounts
Description: Apply quantity-based discounts in WooCommerce.
Version: 1.0.0
Author: Dev Emman
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

// Hook into WooCommerce
add_action('woocommerce_cart_calculate_fees', 'apply_quantity_discounts');

function apply_quantity_discounts($cart) {
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    $total_quantity = 0;

    // Calculate the total quantity of products in the cart
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        $total_quantity += $cart_item['quantity'];
    }

    // Apply the discounts based on quantity
    if ($total_quantity <= 3) {
        $discount_percentage = 0.15; // 15% discount for 3 or below products
    } elseif ($total_quantity >= 4 && $total_quantity <= 11) {
        $discount_percentage = 0.25; // 25% discount for 4 to 11 products
    } elseif ($total_quantity >= 12) {
        $discount_percentage = 0.30; // 30% discount for 12 or above products
    } else {
        $discount_percentage = 0; // No discount for zero quantity
    }

    // Calculate the discount amount
    $discount_amount = $cart->subtotal * $discount_percentage;

    // Add the discount to the cart as a fee
    $cart->add_fee('Quantity Discount', -$discount_amount);
}
