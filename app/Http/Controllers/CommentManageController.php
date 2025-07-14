<?php
    
    namespace App\Http\Controllers;
class CommentManageController extends Controller {
    public function index() {
        return view('admin-panel.comment-manage-article');
    }
}