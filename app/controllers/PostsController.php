<?php

namespace app\controllers;

use app\models\Posts;
use lithium\security\Auth;

class PostsController extends \lithium\action\Controller {
    public function index() {
        $posts = Posts::all();
        return compact('posts');
    }

    public function add() {
        if (!Auth::check('default')) {
            return $this->redirect('Sessions::add');
        }

        $success = false;

        if ($this->request->data) {
            $post = Posts::create($this->request->data);
            $success = $post->save();
        }
        return compact('success');
    }
}

?>
