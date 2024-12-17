<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('check-in') }}">
                @csrf
                <button type="submit">Check In</button>
            </form>
            
            <form method="POST" action="{{ route('check-out') }}">
                @csrf
                <button type="submit">Check Out</button>
            </form>
        </div>
    </div>
</x-app-layout>