DPD API
=======

The DPD API allows you to easily integrate your website with DPD.

What's Included
---------------

The DPD cart API currently lets you list your storefronts (websites) and products.

Requirements
------------

PHP 4.3 or better; 5.2 or better recommended. The `Moxiecode_JSON` class is required if using PHP 4.3.  The cURL PHP extension is required for all versions of PHP.

Usage
-----

    <?php
    require_once "DPDCartApi.class.php";
    
    $dpd = new DPDCartApi("your username", "your key");
    $websites = $dpd->getStorefronts();
    $products = $dpd->getProducts($websites[0]["id"]);
    
    var_export($products);
    ?>