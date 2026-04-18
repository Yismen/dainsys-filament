<?php

return [
    /*
    |--------------------------------------------------------------------------
    | All Translations - Flat Structure (No Prefixes)
    |--------------------------------------------------------------------------
    */
    'id' => 'ID',
    'name' => 'Nombre',
    'description' => 'Descripción',
    'email' => 'Correo Electrónico',
    'password' => 'Contraseña',
    'phone' => 'Teléfono',
    'cellphone' => 'Celular',
    'address' => 'Dirección',
    'date' => 'Fecha',
    'start_date' => 'Fecha de Inicio',
    'end_date' => 'Fecha de Fin',
    'created_at' => 'Creado El',
    'updated_at' => 'Actualizado El',
    'deleted_at' => 'Eliminado El',
    'status' => 'Estado',
    'type' => 'Tipo',
    'comment' => 'Comentario',
    'gender' => 'Género',
    'is_active' => 'Está Activo',
    'notes' => 'Notas',

    // Employee fields
    'first_name' => 'Nombre',
    'second_first_name' => 'Segundo Nombre',
    'last_name' => 'Apellido',
    'second_last_name' => 'Segundo Apellido',
    'personal_id_type' => 'Tipo de Cédula',
    'personal_id' => 'Cédula',
    'date_of_birth' => 'Fecha de Nacimiento',
    'secondary_phone' => 'Teléfono Secundario',
    'has_kids' => 'Tiene Hijos',
    'citizenship' => 'Nacionalidad',
    'profile_photo' => 'Foto',
    'internal_id' => 'ID Interno',
    'full_name' => 'Nombre Completo',

    // Relations
    'site' => 'Sucursal',
    'project' => 'Proyecto',
    'supervisor' => 'Supervisor',
    'position' => 'Cargo',
    'employee' => 'Empleado',
    'user' => 'Usuario',
    'department' => 'Departamento',

    // Bank account
    'bank_account_information' => 'Información de Cuenta Bancaria',
    'bank' => 'Banco',
    'account' => 'Número de Cuenta',

    // Social security
    'social_security_information' => 'Información de Seguridad Social',
    'afp' => 'AFP',
    'ars' => 'ARS',
    'tss_number' => 'Número TSS',
    'is_universal' => 'Es Empleado Universal',

    // Hiring
    'hiring_information' => 'Información de Contratación',
    'job_information' => 'Información del Puesto',
    'hired_at' => 'Fecha de Contratación',
    'date_since' => 'Fecha Desde',

    // History sections
    'suspensions_history' => 'Historial de Suspensiones',
    'hires_history' => 'Historial de Contrataciones',
    'terminations_history' => 'Historial de Terminaciones',
    'last_30_days_absences' => 'Ausencias Últimos 30 Días',

    // History columns
    'starts_at' => 'Inicio',
    'ends_at' => 'Fin',
    'duration_days' => 'Duración (Días)',
    'suspension_type' => 'Tipo de Suspensión',
    'termination_type' => 'Tipo de Terminación',
    'absence_type' => 'Tipo de Ausencia',
    'is_rehirable' => 'Es Recontratable',

    // HR-specific
    'salary_type' => 'Tipo de Salario',
    'salary' => 'Salario',
    'person_of_contact' => 'Persona de Contacto',
    'geolocation' => 'Geolocalización',
    'activity_type' => 'Tipo de Actividad',
    'requested_at' => 'Solicitado El',
    'reported_by' => 'Reportado Por',

    // Additional
    'date_range' => 'Rango de Fechas',
    'date_from' => 'Fecha desde',
    'date_until' => 'Fecha hasta',
    'rehireable' => 'Recontratable',
    'not_rehireable' => 'No Recontratable',

    // Additional table columns
    'is_active' => 'Activo',
    'number' => 'Número',
    'action' => 'Acción',
    'employees' => 'Empleados',
    'completed_at' => 'Completado El',
    'roles' => 'Roles',
    'email_verified' => 'Correo Verificado',
    'verified' => 'Verificado',
    'not_verified' => 'No Verificado',
    'has_employee_id' => 'Tiene ID de Empleado',
    'no_employee_id' => 'Sin ID de Empleado',
    'log' => 'Registro',
    'subject' => 'Sujeto',
    'subject_id' => 'ID de Sujeto',
    'causer_type' => 'Tipo de Responsable',
    'user_id' => 'ID de Usuario',
    'ip_address' => 'Dirección IP',
    'browser' => 'Navegador',
    'event' => 'Evento',
    'role' => 'Rol',
    'joined' => 'Se unió',
    'action' => 'Acción',
    'timestamp' => 'Fecha/Hora',

    /*
    |--------------------------------------------------------------------------
    | App Configuration
    |--------------------------------------------------------------------------
    */
    'app' => [
        'name' => 'Dainsys',
        'description' => 'Sistema de Gestión de Recursos Humanos',
    ],

    'navigation' => [
        'dashboard' => 'Tablero',
        'admin' => 'Administración',
        'clients' => 'Clientes',
        'employees' => 'Empleados',
        'human_resources' => 'Recursos Humanos',
        'invoicing' => 'Facturación',
        'support' => 'Soporte',
        'settings' => 'Configuración',
    ],

    'resources' => [
        'User' => ['label' => 'Usuario', 'plural_label' => 'Usuarios'],
        'Employee' => ['label' => 'Empleado', 'plural_label' => 'Empleados'],
        'Role' => ['label' => 'Rol', 'plural_label' => 'Roles'],
        'Permission' => ['label' => 'Permiso', 'plural_label' => 'Permisos'],
    ],

    'buttons' => [
        'save' => 'Guardar',
        'create' => 'Crear',
        'update' => 'Actualizar',
        'delete' => 'Eliminar',
        'cancel' => 'Cancelar',
        'confirm' => 'Confirmar',
        'submit' => 'Enviar',
        'back' => 'Atrás',
        'next' => 'Siguiente',
        'previous' => 'Anterior',
        'search' => 'Buscar',
        'filter' => 'Filtrar',
        'export' => 'Exportar',
        'import' => 'Importar',
        'refresh' => 'Actualizar',
    ],

    'messages' => [
        'saved' => 'Guardado exitosamente',
        'deleted' => 'Eliminado exitosamente',
        'created' => 'Creado exitosamente',
        'updated' => 'Actualizado exitosamente',
        'error' => 'Ocurrió un error',
        'loading' => 'Cargando...',
        'no_results' => 'No se encontraron resultados',
        'confirm_delete' => '¿Está seguro de que desea eliminar esto?',
        'required' => 'Este campo es obligatorio',
    ],

    'actions' => [
        'view' => 'Ver',
        'edit' => 'Editar',
        'delete' => 'Eliminar',
        'create' => 'Crear',
        'replicate' => 'Duplicar',
        'restore' => 'Restaurar',
        'force_delete' => 'Eliminar permanentemente',
    ],

    'filters' => [
        'all' => 'Todos',
        'active' => 'Activos',
        'inactive' => 'Inactivos',
        'trashed' => 'Eliminados',
    ],

    'statuses' => [
        'active' => 'Activo',
        'inactive' => 'Inactivo',
        'pending' => 'Pendiente',
        'suspended' => 'Suspendido',
        'terminated' => 'Terminado',
    ],

];
