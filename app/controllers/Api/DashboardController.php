<?php


namespace Api;

use Api\Article\CategoryController, Api\Article\ArticleController;

class DashboardController extends AstroBaseController {

  public function get() {
    $categoryController = new CategoryController();
    $articleController = new ArticleController();
    $total_articles = $articleController->articleCount();
    $articles_read = $articleController->readCount();
    $categories = $categoryController->query();

    return $this->successResponse([
      'articles_read_today' => $articleController->readToday(),
      'categories' => $categories,
      'total_articles' => $total_articles,
      'articles_read' => $articles_read
    ]);
  }
  public function transform($data) {
    return $data;
  }

}