<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PostController extends Controller
{
    private function findBySlug($slug)
    {
        $post = Post::where("slug", $slug)->first();

        if (!$post) {
            abort(404);
        }

        return $post;
    }

    private function generateSlug($text){
        $toReturn=null;
        $counter = 0;

        do {
            // generiamo uno slug partendo dal titolo
            $slug = Str::slug($text);

            // se il counter é maggiore di 0, concateno il suo valore allo slug
            if ($counter > 0) {
                $slug .= "-" . $counter;
            }

            // controllo a db se esiste già uno slug uguale
            $slug_esiste = Post::where("slug", $slug)->first();

            if ($slug_esiste) {
                // se esiste, incremento il contatore per il ciclo successivo
                $counter++;
            } else {
                // Altrimenti salvo lo slug nei dati del nuovo post
                $toReturn = $slug;
            }
        } while ($slug_esiste);

        return $toReturn;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = Auth::user();
        //se l'utente ha ruolo admin
        if ($user->role === "admin") 
        {   //vede tutti i post in ordine di creazione discendente
            $posts = Post::orderBy("created_at", "desc")->paginate(5);
        } else {
            //altrimenti vede solo i suoi
            $posts = $user->posts;
        }

        return view("admin.posts.index", compact("posts"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validazione dati
        $validatedData = $request->validate([
            "title"=>"required|min:10",
            "content"=>"required|min:10",
        ]);
    
        //Salvataggio dati a DB
        $post=new Post();
        $post->fill($validatedData);
        $post->slug = $this->generateSlug($post->title);
        $post->user_id = Auth::user()->id;
        $post->save();

        //redirect su la view show

        return redirect()->route ("admin.posts.show", $post->slug);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $post = $this->findBySlug($slug);

        return view ("admin.posts.show", compact("post"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function create()
    {
        // $categories = Category::all();
        // $tags = Tag::all();

        return view("admin.posts.create");
    }
     public function edit($slug)
    {
        
        $post = $this->findBySlug($slug);

        return view ("admin.posts.edit", compact("post"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {   
        $validatedData = $request->validate([
            "title" => "required|min:10",
            "content" => "required|min:10",
        ]);
        $post = $this->findBySlug($slug);

        if ($validatedData["title"] !== $post->title) {
            // genero un nuovo slug
            $post->slug = $this->generateSlug($validatedData["title"]);

        $post->update($validatedData);

        return redirect()->route("admin.posts.show", $post->slug);
    
    }   
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)

    {
        $post = $this->findBySlug($slug);
        $post->delete();
        return redirect()->route("admin.posts.index");
    }
}
