<?php
/*
 * This file is part of the DPD API package.
 * (c) 2010 Portal Labs, LLC <contact@portallabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// this example creates a purchase

require "config.php";
require "../lib/DPDCartApi.class.php";

$dpd = new DPDCartApi(DPD_CART_USER, DPD_CART_KEY);

$websites = $dpd->listWebsites();
$first_product_id = null;

$website = $websites[0];
$products = $dpd->listProducts($website['id']);
$product = $products[0];

if(count($websites) == 0 || count($products) == 0)
  die("You do not have any products available");
  
echo "Creating a test purchase..";
// create a purchase
$cart = array(
  "customer" => array(
    "first_name" => "test",
    "last_name" => "customer",
    "email" => "test@example.com"
    ),
  "line_items" => array(
      array(
        "product_id" => $product['id'],
        "price" => 2.34,
      )
    ));

echo "done.\n";
  
var_export($dpd->createPurchase(2, $cart));