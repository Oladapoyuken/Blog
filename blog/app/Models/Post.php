<?php
    namespace App\Models;

    use Illuminate\Support\Facades\File;
    use Spatie\YamlFrontMatter\YamlFrontMatter;
    use Illuminate\Database\Eloquent\ModelNotFoundException;

    

    class Post{

        public $title;

        public $excerpt;

        public $date;

        public $body;

        public $id;

        public function __construct($id, $title, $excerpt, $date, $body){

            $this->title = $title;
            
            $this->excerpt = $excerpt;
            
            $this->date = $date;
            
            $this->body = $body;

            $this->id = $id;

        } 

        public static function all(){

            return collect(File::files(resource_path("posts")))
                ->map(fn($file) => YamlFrontMatter::parseFile($file))
                ->map(fn($document) => new Post(
                        $document->id,
                        $document->title,
                        $document->excerpt,
                        $document->date,
                        $document->body()
                    )
                )->sortByDesc('date');

                //cache()->remember('key', timer, function(){return what ever you want to remember})

            // $posts = collect($files)
            //     ->map(function($file){
            //         return YamlFrontMatter::parseFile($file);
            //     })->map(function($document){
            //         return new Post(
            //             $document->id,
            //             $document->title,
            //             $document->excerpt,
            //             $document->date,
            //             $document->body()
            //         );


            // $posts = collect($files)
            //     ->map(function ($file){
            //         $document = YamlFrontMatter::parseFile($file);
            //         return new Post(
            //             $document->id,
            //             $document->title,
            //             $document->excerpt,
            //             $document->date,
            //             $document->body()
            //         );

            //     });

        }


        public static function find($id){

            return static::all()->firstWhere('id', $id);
              
        }

        public static function findOrFail($id){
            
            $post = static::find($id);
            
            if(!$post){

                throw new ModelNotFoundException();
            }

            return $post;
        }

        
    }




?>