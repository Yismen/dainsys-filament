<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Enums\QARoles;
use App\Enums\SupportRoles;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            return $user->isSuperAdmin() ? true : null;
        });

        Gate::define('viewPulse', function (User $user) {
            return false;
        });

        Gate::define('viewTelescope', function (User $user) {
            return false;
        });

        Gate::define('manageTickets', function (User $user) {
            return $user->isTicketsManager() ||
                $user->isTicketsAgent();
        });

        Gate::define('interactsWithHumanResource', function (User $user) {
            return $user->hasAnyRole([
                'Human Resource Manager',
                'Human Resource Agent',
            ]);
        });

        Gate::define('interactsWithWorkforce', function (User $user) {
            return $user->hasAnyRole([
                'Workforce Manager',
                'Workforce Agent',
            ]);
        });

        Gate::define('interactsWithProjectExecutive', function (User $user) {
            return $user->hasAnyRole([
                'Project Executive Manager',
                'Project Executive Agent',
            ]);
        });

        Gate::define('interactsWithOperationsDirector', function (User $user) {
            return $user->hasAnyRole([
                'Operations Director Manager',
                'Operations Director Agent',
            ]);
        });

        Gate::define('interactsWithQualityAssurance', function (User $user) {
            return $user->hasAnyRole([
                QARoles::Manager->value,
                QARoles::Agent->value,
            ]);
        });

        Gate::define('manageQAForms', function (User $user): bool {
            return $user->hasRole(QARoles::Manager->value);
        });

        Gate::define('manageQAQuestions', function (User $user): bool {
            return $user->hasRole(QARoles::Manager->value);
        });

        Gate::define('createQAEvaluations', function (User $user): bool {
            return $user->hasRole(QARoles::Agent->value);
        });

        Gate::define('publishQAEvaluations', function (User $user): bool {
            return $user->hasRole(QARoles::Agent->value);
        });

        Gate::define('resolveQADisputes', function (User $user): bool {
            return $user->hasRole(QARoles::Manager->value);
        });

        Gate::define('isActiveSupervisor', function (User $user): bool {
            return $user->supervisor()->where('is_active', true)->exists();
        });

        Gate::define('interactsWithSupervisor', function (User $user) {
            return $user->hasAnyRole([
                'Supervisor Manager',
                'Supervisor Agent',
            ]);
        });

        Gate::define('interactsWithBlog', function (User $user) {
            return $user->hasAnyRole([
                'Blog Manager',
                'Blog Editor',
            ]);
        });

        Gate::define('interactsWithRecruitment', function (User $user) {
            return $user->hasAnyRole([
                'Recruitment Manager',
                'Recruitment Agent',
            ]);
        });

        Gate::define('isAuthenticableEmployee', function (User $user): bool {
            return $user->employee && $user->employee->isActive();
        });

        Gate::define('grab', function (User $user, Ticket $ticket) {
            if ($ticket->isClosed()) {
                return false;
            }

            if ($user->isTicketsManager()) {
                return true;
            }

            if ($ticket->assigned_to === null && $user->isTicketsAgent()) {
                return true;
            }

            return false;
        });

        Gate::define('assign', function (User $user, Ticket $ticket) {
            return
                $user->isTicketsManager() &&
                $ticket->isOpen();

        });

        Gate::define('close', function (User $user, Ticket $ticket) {
            if ($user->id === $ticket->owner_id && $ticket->isOpen()) {
                return true;
            }

            return $ticket->isOpen() &&
            $ticket->isAssigned() &&
            (
                $user->isTicketsManager() ||
                $user->id === $ticket->assigned_to
            );
        });

        Gate::define('reopen', function (User $user, Ticket $ticket) {
            return $ticket->isClosed() &&
            (
                $user->isTicketsManager() ||
                $user->id === $ticket->owner_id ||
                $user->id === $ticket->assigned_to
            );
        });

        Gate::define('reply', function (User $user, Ticket $ticket) {
            return $ticket->isOpen() &&
            (
                $user->isTicketsManager() ||
                $user->id === $ticket->owner_id ||
                $user->id === $ticket->assigned_to
            );
        });

        Gate::define('modify', function (User $user, TicketReply $ticketReply) {
            $ticket = $ticketReply->ticket;

            return $ticket->isOpen() && $ticketReply->user_id === $user->id;
        });

        // ticket special policies

        Gate::define('viewAny', function (User $user, Ticket $ticket) {
            return true;
        });

        Gate::define('view', function (User $user, Ticket $ticket) {
            return $user->hasAnyRole([
                SupportRoles::Manager->value,
                // SupportRoles::Agent->value,
            ]) ||
            $ticket->owner_id === $user->id ||
            $ticket->assigned_to === $user->id;
        });

        Gate::define('viewApiDocs', function (User $user) {
            return $user->isSuperAdmin() ||
            $user->hasAnyRole([
                'Workforce Manager',
                'Workforce Agent',
            ]);
        });

        Gate::define('interactsWithSupport', function (User $user) {
            return $user->can('manageTickets');
        });

        Gate::define('interactsWithInvoicing', function (User $user) {
            return $user->hasAnyRole([
                'Invoicing Manager',
                'Invoicing Agent',
            ]);
        });

    }
}
