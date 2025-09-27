<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Excluir Conta
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Antes de excluir sua conta, faça o download de qualquer dado ou informação que você deseje reter.
        </p>
    </header>

    <button type="button" onclick="document.getElementById('delete-form').style.display = 'block'" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Excluir Conta
    </button>

    <div id="delete-form" style="display: none;" class="mt-6 p-6 bg-red-50 border border-red-200 rounded-lg">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                Tem certeza de que deseja excluir sua conta?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Uma vez que sua conta for excluída, todos os seus recursos e dados serão permanentemente excluídos. Digite sua senha para confirmar que deseja excluir permanentemente sua conta.
            </p>

            <div class="mt-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input id="password" name="password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Digite sua senha" required />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <button type="button" onclick="document.getElementById('delete-form').style.display = 'none'" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200">
                    Cancelar
                </button>

                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Excluir Conta Permanentemente
                </button>
            </div>
        </form>
    </div>
</section>
