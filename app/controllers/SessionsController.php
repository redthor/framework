<?php

namespace app\controllers;

use lithium\security\Auth;
use lithium\analysis\Debugger;

class SessionsController extends \lithium\action\Controller {

    public function add() {
        echo Debugger::export($this->request->data);
        if ($this->request->data && Auth::check('default', $this->request)) {
            return $this->redirect('/');
        }
        // Handle failed authentication attempts
    }

    /* ... */

    public function delete() {
        Auth::clear('default');
        return $this->redirect('/');
    }
}

?>
