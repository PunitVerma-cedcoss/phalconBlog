<?php

use Phalcon\Mvc\Model;

class Blogs extends Model
{
    public $id;
    public $user_email;
    public $blog_date;
    public $blog_title;
    public $blog_content;
    public $blog_image;
    public $blog_tags;
    public $blog_views;
}
