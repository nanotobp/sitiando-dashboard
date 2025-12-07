@extends('layouts.dashboard')

@section('content')
<div class="content-wrapper">
    <div class="content-inner">

        <!-- ============================
             HEADER DEL PERFIL
        ============================ -->
        <div class="df-title-block">
            <h1 class="title">{{ $user->name }}</h1>
            <p class="subtitle-text">Ficha completa del usuario</p>
        </div>

        <!-- ============================
             GRID PRINCIPAL
        ============================ -->
        <div class="grid md:grid-cols-3 gap-6">

            <!-- ======================================================
                 COLUMNA 1 — Datos del usuario
            ====================================================== -->
            <div class="col-span-1">

                <x-admin.card title="Información Personal">
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>

                    @if($user->last_login_at)
                        <p><strong>Último acceso:</strong> {{ $user->last_login_at->format('d/m/Y H:i') }}</p>
                    @endif

                    @if($user->last_login_ip)
                        <p><strong>IP último login:</strong> {{ $user->last_login_ip }}</p>
                    @endif
                </x-admin.card>

                <!-- Roles -->
                <x-admin.section title="Roles del Usuario">
                    @foreach($roles as $role)
                        <div class="flex items-center justify-between mb-2">
                            <span>{{ ucfirst($role->name) }}</span>

                            <form method="POST" action="{{ route('admin.users.updateRole', $user->id) }}">
                                @csrf
                                <input type="hidden" name="role_id" value="{{ $role->id }}">

                                <button class="topbar-btn" style="padding:4px 10px;">
                                    {{ $user->roles->contains($role->id) ? 'Quitar' : 'Asignar' }}
                                </button>
                            </form>
                        </div>
                    @endforeach
                </x-admin.section>

                <!-- Reset password -->
                <x-admin.section title="Acciones">
                    <form method="POST" action="{{ route('admin.users.resetPassword', $user->id) }}">
                        @csrf
                        <button class="topbar-btn danger w-full">
                            Resetear Contraseña
                        </button>
                    </form>
                </x-admin.section>

            </div>



            <!-- ======================================================
                 COLUMNA 2 y 3 — Datos avanzados
            ====================================================== -->
            <div class="col-span-2">

                <!-- ======================
                     STATS DEL USUARIO
                ======================= -->
                <x-admin.section title="Estadísticas del Usuario">
                    <div class="kpi-grid">

                        <x-admin.kpi 
                            label="Órdenes Totales"
                            :value="$stats['orders_total']"
                            sparkId="sparkUserOrders"
                        />

                        <x-admin.kpi 
                            label="Gasto Total"
                            :value="format_currency($stats['total_spent'])"
                            sparkId="sparkUserSpent"
                        />

                        <x-admin.kpi 
                            label="Ticket Promedio"
                            :value="format_currency($stats['avg_ticket'])"
                            sparkId="sparkUserAvg"
                        />

                        <x-admin.kpi 
                            label="Carritos Abandonados"
                            :value="$stats['abandoned_carts']"
                            sparkId="sparkUserAbandoned"
                        />

                    </div>
                </x-admin.section>


                <!-- ======================
                     ÓRDENES DEL USUARIO
                ======================= -->
                <x-admin.section title="Órdenes del Usuario">

                    <x-admin.table>
                        <x-slot name="head">
                            <th>#</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </x-slot>

                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->order_number }}</td>
                                <td>{{ format_currency($order->total) }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($order->status) }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </x-admin.table>

                </x-admin.section>


                <!-- ======================
                     CARRITO ACTUAL
                ======================= -->
                <x-admin.section title="Carrito Actual">

                    @if($cart && count($cart->items) > 0)

                        <x-admin.table>
                            <x-slot name="head">
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </x-slot>

                            @foreach($cart->items as $item)
                                <tr>
                                    <td>{{ $item->product->nombre }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ format_currency($item->price) }}</td>
                                    <td>{{ format_currency($item->price * $item->qty) }}</td>
                                </tr>
                            @endforeach
                        </x-admin.table>

                    @else
                        <p class="text-muted">El usuario no tiene productos en el carrito.</p>
                    @endif

                </x-admin.section>


                <!-- ======================
                     CARRITOS ABANDONADOS
                ======================= -->
                <x-admin.section title="Carritos Abandonados">

                    @if(count($abandoned_carts) > 0)

                        <x-admin.table>
                            <x-slot name="head">
                                <th>Creado</th>
                                <th>Total</th>
                                <th>Items</th>
                            </x-slot>

                            @foreach($abandoned_carts as $c)
                                <tr>
                                    <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ format_currency($c->total) }}</td>
                                    <td>{{ $c->items_count }}</td>
                                </tr>
                            @endforeach
                        </x-admin.table>

                    @else
                        <p class="text-muted">No hay carritos abandonados registrados.</p>
                    @endif

                </x-admin.section>

            </div>

        </div> <!-- end grid -->

    </div>
</div>
@endsection
