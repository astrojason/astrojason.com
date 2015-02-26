<?php


class NavItem {
  public $name;

  public $href;

  public $active;

  public function __construct($name, $href, $active) {
    $this->active = $active;
    $this->href = $href;
    $this->name = $name;
  }
}