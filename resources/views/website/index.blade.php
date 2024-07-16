<x-layouts::website>
    <form action="{{ route('website.logout') }}" method="POST" id="logout-form" class="d-none">
        @csrf
    </form>

    <div class="page page-center">
        <div class="container-tight">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Important Links</h3>
                </div>

                <div class="list-group list-group-flush">
                    @auth('users')
                        <a href="#" class="list-group-item list-group-item-action" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    @else
                        <a href="{{ route('website.login') }}" class="list-group-item list-group-item-action">Login</a>
                        <a href="{{ route('website.register') }}" class="list-group-item list-group-item-action">Register</a>
                    @endauth

                    <a href="{{ route('dashboard.index') }}" class="list-group-item list-group-item-action">Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::website>
