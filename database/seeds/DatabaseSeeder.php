<?php

use App\Permission;
use App\Role;
use Illuminate\Database\Seeder;

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
        $permission->name = 'Create Users';
        $permission->save();
        $permission->roles()->attach($admin_role->id);
        $permission = new Permission();
        $permission->slug = 'update-users';
        $permission->name = 'Edit Users';
        $permission->save();
        $permission->roles()->attach($admin_role->id);
        $permission = new Permission();
        $permission->slug = 'read-users';
        $permission->name = 'Read Users';
        $permission->save();
        $permission->roles()->attach($admin_role->id);
        $permission = new Permission();
        $permission->slug = 'delete-users';
        $permission->name = 'Delete Users';
        $permission->save();
        $permission->roles()->attach($admin_role->id);

        // Customers permissions
        $permission = new Permission();
        $permission->slug = 'create-customers';
        $permission->name = 'Create Customers';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-customers';
        $permission->name = 'Edit Customers';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-customers';
        $permission->name = 'Read Customers';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-customers';
        $permission->name = 'Delete Customers';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);

        // Services permissions
        $permission = new Permission();
        $permission->slug = 'create-services';
        $permission->name = 'Create Services';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-services';
        $permission->name = 'Edit Services';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-services';
        $permission->name = 'Read Services';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-services';
        $permission->name = 'Delete Services';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);

        // Appointments permissions
        $permission = new Permission();
        $permission->slug = 'create-appointments';
        $permission->name = 'Create Appointments';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'update-appointments';
        $permission->name = 'Edit Appointments';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
        $permission = new Permission();
        $permission->slug = 'read-appointments';
        $permission->name = 'Read Appointments';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id, $doctor_role->id]);
        $permission = new Permission();
        $permission->slug = 'delete-appointments';
        $permission->name = 'Delete Appointments';
        $permission->save();
        $permission->roles()->attach([$admin_role->id, $assistant_role->id]);
    }
}
