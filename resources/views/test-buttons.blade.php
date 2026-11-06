@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Button Test Page</h1>
    
    <!-- Test Gallery Button -->
    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Gallery Management Test</h2>
        @livewire('gallery-management')
    </div>
    
    <!-- Test Client Button -->
    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Client Management Test</h2>
        @livewire('client-management')
    </div>
    
    <!-- Test Contract Button -->
    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Contract Management Test</h2>
        @livewire('contract-management')
    </div>
    
    <!-- Test Expense Button -->
    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Expense Management Test</h2>
        @livewire('expense-management')
    </div>
    
    <!-- Test Income Button -->
    <div class="mb-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Income Management Test</h2>
        @livewire('income-management')
    </div>
</div>
@endsection