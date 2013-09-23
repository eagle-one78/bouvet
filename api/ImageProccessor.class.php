<?php namespace App;


/**
 * ImageProccessor is a class to manage extracting images and comments from URL
 * or URL array provided by the $_POST variable
 * Validate and sanitize them and then extract the images.
 *
 * @author Sam
 */
class ImageProccessor {
  private $link;
  private $url;
  private $response;
  private $urlMatchArray = array();
  private $imageArray = array();
  private $imageLink;
  private $imageSize;
  private $imageInfoWithComment;
  private $imageInfo;
  private $imageComment;

  public function __construct() {
    $this->getImageLinks($this->url);
    echo json_encode($this->imageArray, JSON_UNESCAPED_SLASHES);
  }

  /**
   * Create a proxy to handle the URL from the $_POST super variabel, Urldecode it
   * and then get the file contents from the response URL.
   * Using regular expression to extract only links and the comments after them
   * from the returned file content.
   * Process the links.
  */
  public function getImageLinks($postUrl) {
    $postUrl = $_POST['url'];
    if(isset($postUrl)) {
      // If the url dose not exit in the $_POST stop the proccess.
      $this->link = $postUrl;
      $this->url = urldecode($this->link);

      // Avoid accessing the file system
      $this->url = 'http://' . str_replace('http://', '', $this->url);

      //Get the files content from the url
      $this->response = file_get_contents($this->url);

      //Sanitize to avoid XSS
      preg_match_all(
        '/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|][[:space:]][^(?:https?|ftp)].*/i',
        //'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', //Second choice
        $this->response, $this->urlMatchArray
      );
      $this->proccessImageLinks($this->urlMatchArray);
    }
    else {
      throw new Exception('Your link is not valid!');
    }
  }

 /**
 * Proccess the url array for the images and thier comments
 * and make some validation and sanitaize
 * then echo the links array as json object.
 * @param array $urlMatchArray
 */
  public function proccessImageLinks($urlMatchArray) {
    if (isset($urlMatchArray) && is_array($urlMatchArray)) {
      if (is_array($urlMatchArray[0])) {
        foreach ($urlMatchArray[0] as $this->imageInfoWithComment) {
          $this->imageInfo = explode(' ', $this->imageInfoWithComment, 2);
          $this->imageLink = isset($this->imageInfo[0]) ? $this->imageInfo[0] : NULL;
          $this->imageComment = isset($this->imageInfo[1]) ? $this->imageInfo[1] : NULL;
          if(@file_get_contents($this->imageLink) !== FALSE) {
            $this->imageSize = getimagesize($this->imageLink);
            if(!empty($this->imageSize)) {
              $this->imageArray[] = array(array($this->imageLink, $this->imageComment));
            }
          }
        }
      }
    }
  }
}

?>
