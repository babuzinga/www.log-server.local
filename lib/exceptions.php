<?php
class ExceptionError extends Exception {
  public $title;
  public $comment;

  public function __construct($title = '', $comment = '') {
    $this->title = $title;
    $this->comment = $comment;
    parent::__construct($title);
  }
}

class ExceptionNotFound extends ExceptionError {
  public function __construct($title = 'Страница не найдена', $comment = '') {
    parent::__construct($title, $comment);
  }
}

class ExceptionForbidden extends ExceptionError {
  public function __construct($title = 'Доступ ограничен', $comment = '') {
    parent::__construct($title, $comment);
  }
}

class ExceptionNotAvailable extends Exception {
  public function __construct() {
    parent::__construct('');
  }
}