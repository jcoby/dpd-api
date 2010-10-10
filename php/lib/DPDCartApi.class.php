<?php

/*
 * This file is part of the DPD API package.
 * (c) 2010 Portal Labs, LLC <contact@portallabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class DPDCartApi
{
  var $user = null;
  var $key = null;
  var $base_url = "getdpd.com/api2.php";
  var $protocol = "https";
  var $success = true;
  
  function DPDCartApi($user, $key)
  {
    $this->user = $user;
    $this->key = $key;
  }
  
  function ping()
  {
    return $this->doApiRequest('', array(), 'GET');
  }
  
  function listWebsites()
  {
    $response = $this->doApiRequest('/websites', array(), 'GET');
    return $response;
  }
  
  function getWebsite($id)
  {
    return $this->doApiRequest("/websites/{$id}", array(), "GET");
  }
  
  function listProducts($storefront_id=null)
  {
    $response = $this->doApiRequest('/product', array('storefront_id' => $storefront_id), 'GET');
    return $response['products'];
  }
  
  function getProduct($id)
  {
    $response = $this->doApiRequest("/product/show", array("id" => $id));
    return $response['product'];
  }
  
  function doApiRequest($action, $params, $method='POST')
  {
    $this->success = false;
    
    $url = "{$this->protocol}://{$this->base_url}/{$action}";
    
    if($method == 'GET' && count($params) > 0)
      $url.= "?".http_build_query($params);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_REFERER, "");
    curl_setopt($ch, CURLOPT_USERPWD, "{$this->user}:{$this->key}");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    switch(strtoupper($method)) 
    {
      case 'POST':
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($params));
      break;
      case 'DELETE':
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
      break;
      case 'PUT':
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        //curl_setopt ($ch, CURLOPT_HTTPHEADER, array ("Content-Type: application/x-www-form-urlencoded\n"));
      break;
    }
    //curl_setopt($ch, CURLOPT_CAINFO, "cacert.pem");
    
    $raw = curl_exec($ch);
    if($raw === false)
      return false;
      //throw new Exception(curl_error($ch), null);
      
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if(function_exists('json_decode'))
    {
      $response = json_decode($raw, true);
    }
    else if(class_exists("Moxiecode_JSON"))
    {
      $json = new Moxiecode_JSON();
      $response = $json->decode($raw);
    }
    else
    {
      die("DPD Cart API error: No JSON parser is available");
    }
    
    if($http_code != '200')
      return false;
    
    $this->success = true;
    return $response;
  }
}
