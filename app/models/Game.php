<?

class Game extends Eloquent {

  public function __construct() {
    $this->user_id = Auth::user()->id;
  }

}
