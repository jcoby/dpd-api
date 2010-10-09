<?php
/*
 * This file is part of the DPD API package.
 * (c) 2010 Portal Labs, LLC <contact@portallabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// this example lists all of your websites and the products within those websites

require "config.php";
require "../lib/DPDCartApi.class.php";

$dpd = new DPDCartApi(DPD_CART_USER, DPD_CART_KEY);

$websites = $dpd->getStorefronts();

echo "You have ".count($websites)." websites in your account:\n";

foreach($websites as $i => $website)
{
  $products = $dpd->getProducts($website["id"]);
  echo "\t\"{$website['name']}\" has ".count($products)." product(s):\n";
  
  foreach($products as $product)
  {
    echo "\t\t{$product['id']}: {$product['name']}\n";
  }
  
  echo "\n";
}