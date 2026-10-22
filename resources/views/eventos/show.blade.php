@extends('layouts.app')

@section('title', $evento->titulo . ' — FalaQ')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-6 max-w-7xl mx-auto">
    <!-- Formulário de Envio de Pergunta (Ticket #007 & #008) -->
    <div class="md:col-span-5 mb-4">
        <div class="bg-gray-900 shadow-sm rounded-lg p-5 border border-gray-800">
            <h4 class="text-xl font-bold text-white mb-4">💬 Faça sua Pergunta</h4>
            
            <form action="{{ route('eventos.perguntas.store', $evento->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="texto" class="block text-sm font-medium text-gray-400 mb-2">Texto da Pergunta</label>

                    {{-- Ticket #007: old('texto') inserido e borda condicional vermelha --}}
                    <textarea name="texto" id="texto" rows="4" 
                              class="w-full px-3 py-2 bg-gray-800 text-white placeholder-gray-500 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('texto') border-red-500 focus:ring-red-500 @else border-gray-700 @enderror"
                              placeholder="Digite sua dúvida ou comentário para o palestrante...">{{ old('texto') }}</textarea>

                    {{-- Ticket #007: Mensagem de erro em texto vermelho exibida abaixo do campo --}}
                    @error('texto')
                        <p class="text-red-500 text-sm font-bold mt-2">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                
                {{-- Ticket #008: Botão azul com efeito hover estilizado no Tailwind --}}
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Enviar Pergunta
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Perguntas (Ticket #008 - Estilização do Mural) -->
    <div class="md:col-span-7">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-xl font-bold text-white m-0">📋 Perguntas do Evento</h4>
            <span class="text-gray-400 text-sm">Total no Banco: {{ $evento->perguntas()->count() }}</span>
        </div>

        @forelse($perguntas as $pergunta)
            {{-- Ticket #008: Cards de perguntas imitando balões de chat escuros separados por margem inferior (mb-4) --}}
            <div class="bg-gray-900 border-l-4 border-blue-500 rounded-r-lg p-4 shadow-sm relative mb-4 border-t border-r border-b border-gray-800">
                <p class="text-lg text-gray-200 mb-2 leading-relaxed">{{ $pergunta->texto }}</p>
                
                <div class="flex justify-between items-center text-gray-400 text-xs mt-3 pt-2 border-t border-gray-800">
                    <div class="flex items-center space-x-2">
                        <span>Status: <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-950 text-green-400 border border-green-800">{{ $pergunta->status ?? 'Pendente' }}</span></span>
                        <span>• Por: {{ $pergunta->user->name ?? 'Anônimo' }}</span>
                    </div>

                    {{-- Ticket #012: UI de Votos e Ranking no Telão --}}
                    <div class="flex items-center space-x-3">
                        <!-- Contador de Votos Virtual -->
                        <span class="px-2 py-1 text-xs font-bold rounded bg-gray-800 text-blue-400 border border-gray-700">
                            ▲ {{ $pergunta->votos_count ?? 0 }} {{ Str::plural('voto', $pergunta->votos_count ?? 0) }}
                        </span>

                        <!-- Formulário do Botão de Upvote Interativo -->
                        <form action="{{ route('perguntas.votar', $pergunta->id) }}" method="POST" class="inline">
                            @csrf
                            @auth
                                @if($pergunta->votos->contains(auth()->id()))
                                    {{-- Botão destacado indicando que o usuário já votou (removerá o voto no clique) --}}
                                    <button type="submit" class="bg-blue-600 text-white font-semibold py-1 px-2 text-xs rounded hover:bg-blue-700 transition duration-200" title="Clique para remover seu voto">
                                        Votado
                                    </button>
                                @else
                                    {{-- Botão padrão de Upvote não clicado --}}
                                    <button type="submit" class="bg-transparent border border-blue-500 text-blue-500 font-semibold py-1 px-2 text-xs rounded hover:bg-blue-500 hover:text-white transition duration-200">
                                        Votar
                                    </button>
                                @endif
                            @else
                                {{-- Link para quem não está logado --}}
                                <a href="{{ route('login') }}" class="bg-transparent border border-gray-700 text-gray-500 font-semibold py-1 px-2 text-xs rounded hover:border-gray-500 hover:text-gray-400 transition duration-200">
                                    Votar
                                </a>
                            @endauth
                        </form>

                        <span>{{ $pergunta->created_at->format('d/m/Y H:i') }}</span>
                        
                        {{-- Botão de Exclusão direto em HTML para evitar quebras por falta de pastas --}}
                        <form action="{{ route('perguntas.destroy', $pergunta->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta pergunta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white font-semibold py-1 px-2 text-xs rounded hover:bg-red-700 transition duration-200">
                                Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gray-900 text-center p-6 rounded-lg text-gray-400 border border-dashed border-gray-800">
                Nenhuma pergunta disponível neste evento ainda.
            </div>
        @endforelse

        <!-- Links de Paginação do Laravel -->
        @if(method_exists($perguntas, 'links'))
        <div class="flex justify-center mt-6">
            {{ $perguntas->links() }}
        </div>
    @endif
</div>
</div>
@endsection

