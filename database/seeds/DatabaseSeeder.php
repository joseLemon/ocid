<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Admin Role
        $admin_role = new Role();
        $admin_role->slug = 'admin';
        $admin_role->name = 'Administrador';
        $admin_role->save();

        // Assistant Role
        $assistant_role = new Role();
        $assistant_role->slug = 'assistant';
        $assistant_role->name = 'Asistente';
        $assistant_role->save();

        // Doctor Role
        $doctor_role = new Role();
        $doctor_role->slug = 'doctor';
        $doctor_role->name = 'Doctor';
        $doctor_role->save();

        // Users permissions
        $permission = new Permission();
        $permission->slug = 'create-users';
        $permission->name = 'Crear usuarios';
        $permission->save();
        $permission->roles()->attach($admin_role->id);
        $permission = new Permission();
        $permission->slug = 'update-users';
        $permission->name = 'Editarar usuarios';
        $permission->save();
        $permission->roles()->attach($admin_role->id);
        $permission = new Permission();
        $permission->slug = 'read-users';
        $permission->name = 'Ver usuarios';
        $permission->save();
        $permission->roles()->attach($admin_role->id);
        $permission = new Permission();
        $permission->slug = 'delete-users';
        $permission->name = 'Borrar usuarios';
        $permission->save();
        $permission->roles()->attach($admin_role->id);

        // Customers permissions
        $permission = new Permission();
        $permission->slug = 'create-customers';
        $permission->name = 'Crear clientes';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-customers';
        $permission->name = 'Editar clientes';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-customers';
        $permission->name = 'Ver clientes';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-customers';
        $permission->name = 'Borrar clientes';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);

        // Services permissions
        $permission = new Permission();
        $permission->slug = 'create-services';
        $permission->name = 'Crear servicios';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-services';
        $permission->name = 'Editar servicios';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-services';
        $permission->name = 'Ver servicios';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-services';
        $permission->name = 'Borrar servicios';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);

        // Appointments permissions
        $permission = new Permission();
        $permission->slug = 'create-appointments';
        $permission->name = 'Crear citas';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-appointments';
        $permission->name = 'Editar citas';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-appointments';
        $permission->name = 'Ver citas';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id, $doctor_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-appointments';
        $permission->name = 'Borrar citas';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);

        // Branch permissions
        $permission = new Permission();
        $permission->slug = 'create-branches';
        $permission->name = 'Crear sucursales';
        $permission->save();
        $permission->roles()->attach([$admin_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-branches';
        $permission->name = 'Editar sucursales';
        $permission->save();
        $permission->roles()->attach([$admin_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-branches';
        $permission->name = 'Ver sucursales';
        $permission->save();
        $permission->roles()->attach([$admin_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-branches';
        $permission->name = 'Borrar sucursales';
        $permission->save();
        $permission->roles()->attach([$admin_role->id]);

        $user = new User();
        $user->name = 'Developer';
        $user->email = 'developer@mixen.mx';
        $user->password = Hash::make('dEve.9407');
        $user->save();
        $user->roles()->attach(1);
    }
}
