<?php
  require_once 'ImageProccessor.class.php';
  use App\ImageProccessor;

  /**
   * Create a class instance ofthe Image Proccessor class
   * And get the images and thier comments from the URLprovided from
   * the AJAX request
   */
  $imageProccessor = new ImageProccessor();
  $imageProccessor->getImageLinks($_POST['url']);

?>
