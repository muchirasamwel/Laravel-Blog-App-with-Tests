<?php

namespace Tests\Unit;

use App\Http\Controllers\BlogController;
use App\Repositories\BlogRepository;
use Illuminate\Http\Request;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public $blogRepository;
    public $blogController;

    public function __construct()
    {
        parent::__construct();
        $this->blogRepository = new BlogRepository();
        $this->blogController = new BlogController($this->blogRepository);
    }

    public function testBlogsNotEmpty()
    {
        $morethanoneblog = ($this->blogRepository->countBlogs() > 1);
        if ($morethanoneblog) {
            dump('Test Success, Blog contain Some data');
            $this->assertTrue(true);
        } else {
            dump('Test failed, no data found in blogs');
            $this->assertTrue(false);
        }

    }

    public function testSearchOnRepository()
    {
        $title = 'New data++';
        $response = $this->blogRepository->search($title)->count();
        if ($response < 1) {
            dump("Test Search Blog with title [" . $title . "] Success result = 0 as expected");
            $this->assertTrue(true);
        } else {
            dump('Test Fetch Blog with title [' . $title . '] Failed because Expected 0 but got "' . $response . '"');
            $this->assertTrue(false);
        }
    }

    public function testBlogUpdateOnController()
    {
        $data = [
            'title' => 'new auto blog updated', 'content' => 'Updated Auto Content', 'user_id' => '1'
        ];
        $id=58;
        $data = new Request($data);
        $response =  $this->blogController->update($data,$id);
        if ($response==="Update Success") {
            dump('Test success, Update successful');
            $this->assertTrue(true);
        }
        else{
            dump('Test Failed, Insertion Had an Error');
            $this->assertTrue(false);
        }
    }

}
