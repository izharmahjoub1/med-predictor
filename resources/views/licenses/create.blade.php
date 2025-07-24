@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Créer une licence</h1>
    <form method="POST" action="{{ route('licenses.store') }}" class="max-w-lg space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium">Nom</label>
            <input type="text" name="name" id="name" class="border border-gray-300 rounded px-3 py-2 w-full" required>
        </div>
        <div>
            <label for="type" class="block text-sm font-medium">Type</label>
            <select name="type" id="type" class="border border-gray-300 rounded px-3 py-2 w-full" required>
                <option value="Joueur">Joueur</option>
                <option value="Staff">Staff</option>
                <option value="Médical">Médical</option>
            </select>
        </div>
        <div>
            <label for="status" class="block text-sm font-medium">Statut</label>
            <select name="status" id="status" class="border border-gray-300 rounded px-3 py-2 w-full" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
        <a href="{{ route('licenses.index') }}" class="ml-4 text-gray-600 hover:underline">Annuler</a>
    </form>
</div>
@endsection 