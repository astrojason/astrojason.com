<?php

use App\Models\Book as Book;

class TemplateController extends BaseController {

  public function linkForm() {
    $categories = ['New', 'Unread'];
    if(Auth::user()) {
      $dbCategories = Link::groupBy('category')
        ->where('user_id', Auth::user()->id)
        ->where('category', '<>', 'Unread')
        ->get(array('category'));
      foreach($dbCategories as $category) {
        array_push($categories, $category->category);
      }
    }
    $lastElement = end($categories);
    $categoriesString = '[';
    foreach($categories as $category) {
      $categoriesString .= '\'' . $category . '\'';
      if($category != $lastElement) {
        $categoriesString .= ',';
      }
    }
    $categoriesString .= ']';
    return View::make('v1.templates.linkForm')->with('categories', $categoriesString);
  }

  public function bookForm() {
    $categories = ['New', 'Unread'];
    if(Auth::user()){
      $dbCategories = Book::groupBy('category')
        ->where('user_id', Auth::user()->id)
        ->where('category', '<>', 'Unread')
        ->get(array('category'));
      foreach($dbCategories as $category) {
        $categories[] = $category->category;
      }
      $lastElement = end($categories);
      $categoriesString = '[';
      foreach($categories as $category) {
        $categoriesString .= '\'' . $category . '\'';
        if($category != $lastElement) {
          $categoriesString .= ',';
        }
      }
      $categoriesString .= ']';
      return View::make('v1.templates.bookForm')->with('categories', $categoriesString);
    }
  }

  public function movieForm() {
    return View::make('v1.templates.movieForm');
  }

  public function gameForm() {
    $platforms = ['New'];
    if(Auth::user()){
      $dbPlatforms = Game::groupBy('platform')
        ->where('user_id', Auth::user()->id)
        ->get(array('platform'));
      foreach($dbPlatforms as $platform) {
        $platforms[] = $platform->platform;
      }
      $lastElement = end($platforms);
      $platformsString = '[';
      foreach($platforms as $platform) {
        $platformsString .= '\'' . $platform . '\'';
        if($platform != $lastElement) {
          $platformsString .= ',';
        }
      }
      $platformsString .= ']';
      return View::make('v1.templates.gameForm')->with('platforms', $platformsString);
    }
  }

  public function accountForm() {
    return View::make('v1.templates.accountForm');
  }

  public function songForm() {
    return View::make('v1.templates.songForm');
  }

  public function loader() {
    return View::make('v1.templates.loader');
  }

  public function paginator() {
    return View::make('v1.templates.paginator');
  }

  public function songModal() {
    return View::make('v1.templates.songModal');
  }

  public function bookModal() {
    return View::make('v1.templates.bookModal');
  }

  public function linkModal() {
    return View::make('v1.templates.linkModal');
  }

  public function gameModal() {
    return View::make('v1.templates.gameModal');
  }

  public function accountModal() {
    return View::make('v1.templates.accountModal');
  }

  public function movieModal() {
    return View::make('v1.templates.movieModal');
  }

  public function taskTable() {
    return View::make('v1.templates.taskTable');
  }
}
