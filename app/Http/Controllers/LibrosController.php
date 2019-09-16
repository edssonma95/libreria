<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Inventory;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibrosController extends Controller
{
    public function add(Request $request)
    {
        $data = json_decode($request->get('data'));
        $context = $request->get('context');

        DB::beginTransaction();
        try { 

            $book = $context == "add" ? new Libro() : Libro::find($data->id);
            $book->name = $data->name;
            $book->description = $data->description;
            $book->relase_date = Carbon::parse($data->relase_date)->toDateString();
            $book->author_id = $data->author_id;
            $book->editorial_id = $data->editorial_id;  
            
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $nombre = date("d_m_Y-H-i-s", strtotime(str_replace('/', '-', Carbon::now()))) . ($file->getClientOriginalName());
                $book->cover = $request->file('cover')->storeAs('public/covers', $nombre);
            }
            $book->save();
            if(is_object($book))
            {
                $inventory = $context == "add" ? new Inventory() : Inventory::where('book_id', $data->id)->first();
                $inventory->book_id = $book->id;
                $inventory->quantity = $data->quantity;
                $inventory->save();                                 
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => "Libro " .'"'. $book->name .'"'. $context == "add" ? " agregado" : " actualizado" . " con éxito",
                'book' => $book
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();

            return response()->json([
                'status' => 400,
                'message' => "Ha ocurrido un error",
                'error' => $e
            ]);
        }
    }

    public function getList(Request $request)
    {
        $size = $request->get('size');
        $page = $request->get('currentPage');
        $query = json_decode($request->get('query'));
        
        $books = null;
        
        //aqui se que no es la mejor manera de hacerlo pero por alguna razón al hacerlo de otra forma no me regresaba 
        //lo que necesitaba y no tenía mucho tiempo para hacerlo bien

        if($query != null || $query != "")
        {
            switch ($query->type) {
                case 'Libro':
                $books = Libro::with('Author','Inventory','Editorial')->where('id', $query->id)->get();
                    break;
                case 'Autor':
                $books = Libro::with('Author','Inventory','Editorial')->where('author_id', $query->id)->get();
                    break;
                case 'Editorial':
                $books = Libro::with('Author','Inventory','Editorial')->where('editorial_id', $query->id)->get();
                break;
            }
        }
        else
            $books = Libro::with('Author','Inventory','Editorial')->orderBy('created_at','desc')->get();
        
        return response()->json([
            "total" => $books->count(),
            "books" => $books->forPage($page, $size)
        ]);
    }

    public function deleteBook($id)
    {
        $book = Libro::find($id);
        Inventory::where('book_id', $book->id)->first()->delete();
        $book->delete();
    }

    public function searchBook($query)
    {
        $authors = Libro::select('authors.id','authors.name',DB::raw('"Autor" as type'))->leftJoin("authors","books.author_id","authors.id")
        ->where("authors.name", "LIKE", "%".$query."%")->get();
        $editorial = Libro::select('editoriales.id','editoriales.name',DB::raw('"Editorial" as type'))->leftJoin("editoriales","books.editorial_id","editoriales.id")
        ->where("editoriales.name", "LIKE", "%".$query."%")->get();
        $books = Libro::select('books.id','books.name',DB::raw('"Libro" as type'))->where("name", "LIKE", "%".$query."%")->get();

        $suggestions = $authors->merge($editorial)->merge($books);

        return $suggestions;
    }
}
