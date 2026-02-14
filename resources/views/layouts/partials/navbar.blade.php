@auth
    <span class="me-3 text-dark fw-semibold">
        👋 {{ auth()->user()->name }}
    </span>

    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
        @csrf
        <button class="btn btn-danger btn-sm">Logout</button>
    </form>
@endauth

@guest
    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login</a>
@endguest
