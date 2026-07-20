<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enum Translations
    |--------------------------------------------------------------------------
    */
    'termination' => [
        'resignation' => 'Renuncia',
        'termination' => 'Desahucio',
        'firing' => 'Despido',
        'abandonment' => 'Abandono',
        'dismissing' => 'Dimisión',
        'resignation_description' => 'El empleado comunicó su deseo de terminar el contrato.',
        'termination_description' => 'La empresa ejerció la terminación del contrato sin causa.',
        'firing_description' => 'El empleado cometió una falta grave, causando su despido.',
        'abandonment_description' => 'Múltiples ausencias injustificadas.',
        'dismissing_description' => 'El empleado demandó a la empresa.',
    ],

    'employee_status' => [
        'created' => 'Creado',
        'hired' => 'Contratado',
        'suspended' => 'Suspendido',
        'terminated' => 'Terminado',
    ],

    'downtime_status' => [
        'pending' => 'Pendiente',
        'approved' => 'Aprobado',
        'rejected' => 'Rechazado',
    ],

    'absence_type' => [
        'justified' => 'Justificada',
        'unjustified' => 'Injustificada',
    ],

    'absence_status' => [
        'created' => 'Creada',
        'reported' => 'Reportada',
    ],

    'suspension_status' => [
        'pending' => 'Pendiente',
        'current' => 'Actual',
        'completed' => 'Completada',
    ],

    'gender' => [
        'male' => 'Masculino',
        'female' => 'Femenino',
    ],

    'personal_id_type' => [
        'dominican_id' => 'Cédula Dominicana',
        'passport' => 'Pasaporte',
    ],

    'salary_type' => [
        'salary' => 'Salario',
        'hourly' => 'Por Hora',
        'by_sales' => 'Por Ventas',
    ],

    'revenue_type' => [
        'downtime' => 'Inactividad',
        'login_time' => 'Tiempo de Conexión',
        'production_time' => 'Tiempo de Producción',
        'talk_time' => 'Tiempo de Habla',
        'conversions' => 'Conversiones',
    ],

    'campaign_source' => [
        'chat' => 'Chat',
        'email' => 'Correo',
        'inbound' => 'Entrante',
        'outbound' => 'Saliente',
        'qa_review' => 'Revisión QA',
        'resubmissions' => 'Reenvíos',
        'training' => 'Entrenamiento',
    ],

    'hr_activity_type' => [
        'vacations' => 'Vacaciones',
        'permission' => 'Permiso',
        'employment_letter' => 'Carta de Trabajo',
        'loan' => 'Préstamo',
        'uniform' => 'Uniforme',
        'counseling' => 'Consejería',
        'interview' => 'Entrevista',
    ],

    'hr_activity_request_status' => [
        'requested' => 'Solicitado',
        'in_progress' => 'En Progreso',
        'completed' => 'Completado',
        'cancelled' => 'Cancelado',
    ],

    'evaluation_status' => [
        'draft' => 'Borrador',
        'published' => 'Publicado',
        'accepted_closed' => 'Aceptado y Cerrado',
        'disputed' => 'Disputado',
        'rejected' => 'Rechazado',
    ],

    'ticket_status' => [
        'pending' => 'No Asignado',
        'pending_expired' => 'Expirado Antes de Asignación',
        'in_progress' => 'Asignado a Usuario',
        'in_progress_expired' => 'Expirado y Asignado',
        'completed' => 'Completado a Tiempo',
        'completed_expired' => 'Completado Después de Vencer',
    ],

    'ticket_priority' => [
        'normal' => 'Normal',
        'medium' => 'Medio',
        'high' => 'Alto',
        'emergency' => 'Emergencia',
    ],

    'article_status' => [
        'draft' => 'Borrador',
        'published' => 'Publicado',
    ],

    'qa_role' => [
        'manager' => 'Gerente de Aseguramiento de Calidad',
        'agent' => 'Agente de Aseguramiento de Calidad',
    ],

    'support_role' => [
        'manager' => 'Gerente de Soporte',
        'agent' => 'Agente de Soporte',
    ],

    'invoice_status' => [
        'pending' => 'Pendiente',
        'partially_paid' => 'Parcialmente Pagado',
        'paid' => 'Pagado',
        'overdue' => 'Vencido',
        'cancelled' => 'Cancelado',
    ],
];
