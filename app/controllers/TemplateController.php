<?php

class TemplateController extends BaseController {
  public function linkForm() {
    $categories = Link::groupBy('category')->get(array('category'));
    
    return View::make('templates/linkForm');
  }
} 