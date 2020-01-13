<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Mail\MyMail;
use App\Repositories\BlogRepository;
use App\Scopes\TodaysBlog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public $blogRepository;

    public function __construct(BlogRepository $blogRepository)
    {
        $this->middleware('auth');
        $this->blogRepository = $blogRepository;
    }

    public function index()
    {
        $logged_user = Auth::user();

        $blogs = Cache::remember('blogs', 1, function () {
            return $this->blogRepository->all();
        });
        return view('manageblogs')->with(['blogs' => $blogs, 'user' => $logged_user]);
    }

    public function todaysBlog()
    {
        $logged_user = Auth::user();
        $blogs = Cache::remember('todaysblogs', 10, function () {
            return $this->blogRepository->todaysBlog();
        });
        return view('todaysblogs')->with(['blogs' => $blogs, 'user' => $logged_user]);
        // var_dump($data);
    }

    public function searchBlog(Request $request)
    {
        $title = $request->input('title');
        $logged_user = Auth::user();
        $blogs = $this->blogRepository->search($title);
        // var_dump($blogs);
        if (trim($title) == "")
            return redirect()->back();
        return view('home')->with(['blogs' => $blogs, 'user' => $logged_user]);
    }

    public function sendNotification($message)
    {
        try {
            $user = Cache::remember('user', 10, function () {// this will only be updated if expired
                return Auth::user();
            });
            $email = $user->email;
            $emaildata = new \stdClass();
            $emaildata->subject = "Blog Notification";
            $emaildata->message = $message;
            Mail::to($email)->send(new MyMail($emaildata));
            return $this->index();
        } catch (\Exception $ex) {
            $logged_user = Cache::remember('user', 10, function () {
                return Auth::user();
            });
            $blogs = Blog::all();
            Log::error('An error occured when sending Mail "Blog notification" ' . $ex);
            return view('manageblogs')->with(['blogs' => $blogs, 'user' => $logged_user, 'errors' => ['Mail send Failed']]);
        }
    }

    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {
        try {
            Blog::create($request->all());
            Cache::forget('blogs');
            return redirect('/blog');
            //return $this->sendNotification("Your Blog '" . $blog->title . "' Has been Published");
        } catch (\Exception $ex) {
            $logged_user = Auth::user();

            $blogs = Blog::all();
            Log::error('An error occured when creating a blog' . $ex);

            if (Str::contains($ex, 'Duplicate entry')) {
                return view('manageblogs')->with(['blogs' => $blogs, 'user' => $logged_user, 'errors' => ['Blog Title Already Exists']]);
            }

            return view('manageblogs')->with(['blogs' => $blogs, 'user' => $logged_user, 'errors' => ['Unexpected error occurred']]);
        }
    }

    public function show($id)
    {
        $blog = DB::table('users')->where('id', $id)->first();
        return view("home")->with(['blogs' => [$blog]]);

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('blogs')->where('id', $id)->update($request->all());
            return "Update Success";
        } catch (\Exception $ex) {
            return "Unexpected Error " . $ex;
        }
    }

    public function destroy($id)
    {
        var_dump($id);
        Blog::destroy([$id]);
        Cache::forget('blogs');
        Cache::forget('todaysblogs');
//
//        $blog = Blog::find($id);
//        $this->sendNotification("Your Blog '" . $blog->title . "' Has been Deleted");
        return redirect('/blog');
    }
}
