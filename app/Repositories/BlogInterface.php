<?php


namespace App\Repositories;

interface BlogInterface
{
    public function all();
    public function todaysblog();
    public function delete($title);
    public function search($post_id);
}
