<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Evento; 
use App\Models\Pergunta;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PerguntaController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Evento $evento)
    {
        $request->validate([
            'texto' => 'required|string|max:1000',
        ], [
            'texto.required' => 'O campo de texto da pergunta é obrigatório.'
        ]);

        $pergunta = new Pergunta();
        $pergunta->texto = $request->texto;
        $pergunta->evento_id = $evento->id;
        $pergunta->user_id = auth()->id(); 
        $pergunta->is_public = true;      
        $pergunta->save();

        return redirect()->route('eventos.show', $evento->id)
            ->with('success', 'Sua pergunta foi enviada com sucesso!');
    }

    public function destroy($id)
    {
        $pergunta = Pergunta::findOrFail($id);

        if (auth()->id() != $pergunta->user_id && auth()->id() != $pergunta->evento->user_id) {
            abort(403, 'This action is unauthorized.');
        }

        $pergunta->delete();

        return redirect()->back()->with('success', 'Pergunta excluída com sucesso!');
    }
    public function votar(Pergunta $pergunta): RedirectResponse
    {
        $pergunta->votos()->toggle(auth()->id());

        return back()->with('status', 'Voto atualizado com sucesso!');
    }

}