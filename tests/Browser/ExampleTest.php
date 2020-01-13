<?php

namespace Tests\Browser;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
   // use DatabaseMigrations;

    public function testRegister(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Register')
                ->type('name', 'sam')
                ->type('email', 'samkan@gmail.com')
                ->type('password', 'samkanpalma')
                ->type('password_confirmation', 'samkanpalma')
                ->press('Register')
                ->assertSee('The email has already been taken');
               // ->assertPathIs('/home');
            dump("Registration Test success, Email already used ");
        });
    }

    public function testLoginProcess()
    {
         $user = new User([
            'id' => 2,
            'name' => 'sam',
            'email'=>'samkan@gmail.com',
            'password' => bcrypt($password = 'samkanpalma'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->assertSee('Login')
                ->type('email', $user->email)
                ->type('password', 'samkanpalma')
                ->press('Login')
                ->assertPathIs('/');
            dump('Login Test passed, Login successful');
        });
    }
    public function testCreationOfBlog(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/blog')
                ->assertSee('Manage Your Blog')
                ->type('title', 'Bot Generated Title')
                ->type('content', 'I guess this bot is not mallicious to kill my project.')
                ->press('Add Blog')
                ->assertPathIs('/blog')
                ->assertDontSee('Blog Title Already Exists');
            dump('Blog Creation Test Success');
        });
    }
    public function testCreatedBlogIsTodaysBlog(){
        $this->browse(function (Browser $browser) {
            $browser->visit('/today')
                ->assertPathIs('/today')
                ->assertSee('Bot Generated Title');

            dump('Blog Creation Test confirmation Success');
        });
    }
}
