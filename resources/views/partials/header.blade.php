<div class="header">
    <div id="collapseSidebar" class="collapse-btn">
        <i class="fa fa-bars"></i>
    </div>

    <div>
        {{ Auth::user()->email }}
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
    @csrf

            <button class="btn btn-danger btn-sm">Salir</button>
        </form>
    </div>
</div>
