<?php

class TemplateController extends BaseController {

  public function loader() {
    return View::make('v1.templates.loader');
  }

  public function paginator() {
    return View::make('v1.templates.paginator');
  }

}
