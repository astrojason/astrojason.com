<?php

/**
 * User: jasonsylvester
 * Date: 12/11/15
 * Time: 10:36 AM
 */
abstract class AstroData {

  /**
   * @var integer
   */
  public $userId;

  /**
   * @var array
   */
  public $results;

  /**
   * @var array
   */
  public $errors;

  /**
   * @var integer
   */
  public $errorCode;

  /**
   * @return null
   */
  abstract function getData();
}
//
//class AstroCurl extends AstroData {
//
//  public function getData()
//  {
//    $this->data = 'Data retrieved from AstroCurl';
//    $this->success = true;
//  }
//}
//
//class AstroDataImplementation {
//
//  public function getMyData(AstroData $dataProvider) {
//    $dataProvider->getData();
//    if($dataProvider->success) {
//      var_dump($dataProvider->data);
//    } else {
//      var_dump($dataProvider->errors);
//    }
//  }
//
//}
//
//$controller = new AstroDataImplementation();
//
//$controller->getMyData(new AstroCurl());
//
//$controller->getMyData(new AstroSql());
//
//class AstroError extends AstroData {
//  public function getData()
//  {
//    $this->errors = ['There was a problem'];
//  }
//}
//
//$controller->getMyData(new AstroError());