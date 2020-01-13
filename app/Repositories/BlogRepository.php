<?php
namespace App\Repositories;
use App\Blog;
Class BlogRepository implements BlogInterface
{
    public function search($title)
    {
        return Blog::ofSearch($title)->get();
    }

    /**
     * Get's all Blogs.
     *
     * @return mixed
     */
    public function all()
    {
        return Blog::all();
    }

    public function delete($post_id)
    {
        Blog::destroy($post_id);
    }

    public function todaysBlog()
    {
        return Blog::ofToday()->get();
    }
    public function countBlogs(){
        return Blog::all()->count();
    }
}
