<?php

namespace Tests\Feature;

use App\Http\Controllers\BlogController;
use App\Repositories\BlogRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestResponse;
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
    public function testRoutingWithoutAuth()
    {
        $response = $this->get('/');
        $response->assertRedirect('login');
        $this->followRedirects($response)
            ->assertSee('Login')
            ->assertSee('Forgot Your Password?');
        dump('Un authenticated user is redirected to login ');
    }
    protected function followRedirects(TestResponse $response)
    {
        while ($response->isRedirect()) {
            $response = $this->get($response->headers->get('Location'));
        }

        return $response;
    }

    public function testAuthUserCanReachHome()
    {
        $response = $this->post('/login', [
            'email' => 'sam@gmail.com',
            'password' => 'samkan',
        ]);
        $response->assertRedirect('/');
        $response->assertDontSee('login');
        $this->followRedirects($response)
            ->assertSee('Sam');

        dump('Authenicated user can reach Home page');

    }
    public function testDeleteAuthorisation(){
        $user = new User([
            'id' => 2,
            'name' => 'sam',
            'email'=>'sam@gmail.com',
            'password' => bcrypt($password = 'samkan'),
        ]);
        $this->be($user);
        $response=$this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Manage Your Blog');
        $response->assertDontSee('Delete');
        $response->assertSee('New data');
        dump('This user does not see options to delete posts because he has not added any post');
    }



}
