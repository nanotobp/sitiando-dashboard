<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{
    public function run(): void
    {
        $abilities = [

            // Dashboard
            ['view_dashboard', 'Ver dashboard'],

            // Orders
            ['view_orders', 'Ver órdenes'],
            ['update_orders', 'Actualizar órdenes'],
            ['cancel_orders', 'Cancelar órdenes'],
            ['assign_orders', 'Asignar órdenes'],
            ['refund_orders', 'Reembolsar órdenes'],
            ['view_order_payments', 'Ver pagos'],
            ['manage_order_payments', 'Gestionar pagos'],

            // Products
            ['view_products', 'Ver productos'],
            ['create_products', 'Crear productos'],
            ['edit_products', 'Editar productos'],
            ['delete_products', 'Eliminar productos'],
            ['manage_inventory', 'Administrar inventario'],

            // Users
            ['view_users', 'Ver usuarios'],
            ['edit_users', 'Editar usuarios'],
            ['reset_user_passwords', 'Resetear contraseñas'],
            ['view_user_activity', 'Ver actividad de usuario'],
            ['view_user_stats', 'Ver estadísticas de usuario'],
            ['view_user_cart', 'Ver carrito actual'],
            ['view_user_abandoned_cart', 'Ver carritos abandonados'],
            ['view_user_orders', 'Ver compras del usuario'],

            // Roles
            ['view_roles', 'Ver roles'],
            ['assign_roles', 'Asignar roles'],
            ['edit_roles', 'Editar roles'],
            ['edit_permissions', 'Editar permisos'],

            // Affiliates
            ['view_affiliates', 'Ver afiliados'],
            ['edit_affiliates', 'Editar afiliados'],
            ['approve_affiliates', 'Aprobar afiliados'],
            ['view_affiliate_performance', 'Performance afiliado'],
            ['manage_commissions', 'Gestionar comisiones'],

            // Marketing
            ['view_coupons', 'Ver cupones'],
            ['create_coupons', 'Crear cupones'],
            ['edit_coupons', 'Editar cupones'],
            ['delete_coupons', 'Eliminar cupones'],

            // Reports
            ['view_reports', 'Ver reportes'],
            ['export_reports', 'Exportar reportes'],

            // Settings
            ['manage_settings', 'Configurar sistema'],
            ['manage_payments', 'Configurar pagos'],
            ['manage_integrations', 'Integraciones'],
            ['manage_branding', 'Branding'],

            // Audit
            ['view_audit_logs', 'Logs del sistema'],
            ['view_operator_logs', 'Actividad de operadores'],
        ];

        foreach ($abilities as [$key, $label]) {
            Ability::firstOrCreate(['key' => $key], ['label' => $label]);
        }
    }
}
