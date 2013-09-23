<?php
require_once('ImageProccessor.test.class.php');
  use App\ImageProccessor;

  /**
   * Testing class for the Image proccesor APP
   */
  class ImageProccessorTest extends PHPUnit_Framework_TestCase {

    /**
     * @test Get image links function
     * If the $_POST has the key 'url'
     * If the regexp to extract the links working
     */
    public function testGetImageLinks() {
      $imageProccessor = new ImageProccessor;
      //test if the $_POST array has the url key
      $url = 'http://www.bouvet.se/images/logo-bouvet.png';
      $imageProccessor->getImageLinks(array($url));
      $this->assertRegExp(
        '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i',
        'http://www.bouvet.se/images/logo-bouvet.png'
      );
    }

    /**
     * @test Proccess image links function
     */
    public function testProccessImageLinks() {
      $imageProccessor = new ImageProccessor;
      $urlMatchArray = array(
          'http://www.bouvet.se/images/logo-bouvet.png',
          'http://www.bouvet.se/images/logo-bouvet.png'
         );
      $imageProccessor->proccessImageLinks($urlMatchArray);
      //Test if the array not empty
      $this->assertNotEmpty($urlMatchArray);
    }
  }

?>
