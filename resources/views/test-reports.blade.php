<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Reports Navigation Debug</h1>
        
        @php
            $user = auth()->user();
        @endphp
        
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">User Information</h2>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ $user->role }}</p>
            
            <h3 class="text-lg font-semibold mt-4 mb-2">Role Checks</h3>
            <ul class="list-disc pl-5">
                <li>Is Admin: {{ $user->isAdmin() ? 'Yes' : 'No' }}</li>
                <li>Is Manager: {{ $user->isManager() ? 'Yes' : 'No' }}</li>
                <li>Is Finance: {{ $user->isFinance() ? 'Yes' : 'No' }}</li>
                <li>Is Regular User: {{ $user->isRegularUser() ? 'Yes' : 'No' }}</li>
                <li>Has Role (Admin/Manager/Finance): {{ $user->hasRole([App\Models\User::ROLE_ADMIN, App\Models\User::ROLE_MANAGER, App\Models\User::ROLE_FINANCE]) ? 'Yes' : 'No' }}</li>
            </ul>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">Available Roles</h2>
            <ul class="list-disc pl-5">
                @foreach(App\Models\User::getRoles() as $roleKey => $roleName)
                    <li>{{ $roleKey }}: {{ $roleName }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>