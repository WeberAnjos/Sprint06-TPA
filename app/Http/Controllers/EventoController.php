<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventoFormRequest;
use App\Models\Evento;
use App\Models\Pergunta;
use App\Http\Requests\StorePerguntaRequest;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::all();
        return view('eventos.index', compact('eventos'));
    }

    // Garanta que o parâmetro do método é (Evento $evento) com o tipo correto
    public function show(Evento $evento)
    {
        $perguntas = $evento->perguntas()
            ->withCount('votos')
            ->with('votos')
            ->orderByDesc('votos_count')
            ->paginate(10); // O ponto e vírgula deve ficar aqui, sem texto extra



        // Passa o objeto $evento populado e a paginação de $perguntas para a view
        return view('eventos.show', compact('evento', 'perguntas'));
    }
 
    public function storePergunta(StorePerguntaRequest $request, $id)
    {
        $evento = Evento::findOrFail($id);

        Pergunta::create([
            'evento_id' => $evento->id,
            'user_id' => Auth::user()->id,
            'texto'     => $request->input('texto'),
            'status'    => 'pendente',
        ]);

        return redirect()->route('eventos.show', $evento->id)
            ->with('sucesso', 'Sua pergunta foi enviada com sucesso!');
    }

    public function create(){
        return view('eventos.create');
    }

    public function store(EventoFormRequest $request){
        $evento = $request->user()->eventos()->create($request->validated());
        return redirect()->route('eventos.show', $evento->id);
    }
}
