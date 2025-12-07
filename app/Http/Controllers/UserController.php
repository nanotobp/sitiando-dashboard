public function editRole($id)
{
    $user = User::with('roles')->findOrFail($id);
    $roles = Role::all();

    return view('admin.users.edit-role', compact('user', 'roles'));
}

public function updateRole(Request $request, $id)
{
    $user = User::findOrFail($id);

    $request->validate([
        'role_id' => 'required|exists:roles,id'
    ]);

    // Reemplazar todos los roles del usuario por el nuevo
    $user->roles()->sync([$request->role_id]);

    return redirect()
        ->route('admin.users.show', $id)
        ->with('success', 'Rol actualizado correctamente.');
}

public function permissions($id)
{
    $user = User::with('roles.abilities')->findOrFail($id);

    // Permisos finales = combinación de roles
    $finalPermissions = $user->roles
        ->flatMap(fn ($r) => $r->abilities)
        ->unique('id')
        ->values();

    return view('admin.users.permissions', compact('user', 'finalPermissions'));
}
public function index()
{
    $query = User::query()->with('roles')->withCount('orders');

    if (request('q')) {
        $query->where(function($q) {
            $q->where('name', 'ilike', '%' . request('q') . '%')
              ->orWhere('email', 'ilike', '%' . request('q') . '%');
        });
    }

    if (request('role')) {
        $query->whereHas('roles', function($q) {
            $q->where('role_id', request('role'));
        });
    }

    $users = $query->paginate(20);
    $roles = \App\Models\Role::all();

    return view('admin.users.index', compact('users', 'roles'));
}
public function show($id)
{
    $user = User::with('roles')->findOrFail($id);

    // ==========================
    // ROLES DISPONIBLES
    // ==========================
    $roles = \App\Models\Role::all();


    // ==========================
    // ESTADÍSTICAS DEL USUARIO
    // ==========================
    $orders = $user->orders()->get();

    $stats = [
        'orders_total'   => $orders->count(),
        'total_spent'    => $orders->sum('total'),
        'avg_ticket'     => $orders->avg('total') ?? 0,
        'abandoned_carts'=> $user->carts()->where('status', 'abandoned')->count(),
    ];


    // ==========================
    // CARRITO ACTUAL
    // ==========================
    $cart = $user->carts()
        ->where('status', 'active')
        ->with(['items.product'])
        ->first();


    // ==========================
    // CARRITOS ABANDONADOS
    // ==========================
    $abandoned_carts = $user->carts()
        ->where('status', 'abandoned')
        ->withCount('items')
        ->get();


    return view('admin.users.show', [
        'user'             => $user,
        'roles'            => $roles,
        'stats'            => $stats,
        'orders'           => $orders,
        'cart'             => $cart,
        'abandoned_carts'  => $abandoned_carts,
    ]);
}
