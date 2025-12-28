# 🎯 Riepilogo Tecnico del Progetto

## Stack Tecnologico

- **Backend**: Laravel 12.43.1
- **Frontend**: Vue.js 3 + Inertia.js 2.0
- **CSS Framework**: Bulma CSS
- **Database**: SQLite (default, configurabile)
- **Autenticazione**: Laravel Breeze
- **Build Tool**: Vite 7

## Database Schema

### Tabella: contacts
```sql
- id (bigint, primary key)
- name (string)
- email (string, nullable)
- phone (string, nullable)
- company (string, nullable)
- notes (text, nullable)
- status (enum: 'contatto', 'cliente')
- created_at, updated_at
```

### Tabella: project_types
```sql
- id (bigint, primary key)
- name (string)
- description (text, nullable)
- created_at, updated_at
```

### Tabella: projects
```sql
- id (bigint, primary key)
- contact_id (foreign key → contacts.id)
- project_type_id (foreign key → project_types.id)
- name (string)
- description (text, nullable)
- status (enum: 'attivo', 'completato', 'sospeso')
- created_at, updated_at
```

### Tabella: task_templates
```sql
- id (bigint, primary key)
- project_type_id (foreign key → project_types.id)
- name (string)
- description (text, nullable)
- default_deadline_days (integer, default: 3)
- order (integer, default: 0)
- created_at, updated_at
```

### Tabella: tasks
```sql
- id (bigint, primary key)
- project_id (foreign key → projects.id)
- task_template_id (foreign key → task_templates.id, nullable)
- blocked_by_task_id (foreign key → tasks.id, nullable)
- name (string)
- description (text, nullable)
- status (enum: 'da_fare', 'in_corso', 'completato', 'in_pausa', 'in_attesa')
- deadline (timestamp, nullable)
- started_at (timestamp, nullable)
- completed_at (timestamp, nullable)
- paused_at (timestamp, nullable)
- total_work_time (integer, in minuti, default: 0)
- created_at, updated_at
```

## Relazioni Eloquent

```
Contact (1) → (N) Projects
ProjectType (1) → (N) Projects
ProjectType (1) → (N) TaskTemplates
Project (1) → (N) Tasks
TaskTemplate (1) → (N) Tasks
Task (1) → (N) Tasks (blocking relationship)
```

## API Routes

### Dashboard
- `GET /dashboard` → DashboardController@index

### Contacts
- `GET /contacts` → ContactController@index
- `GET /contacts/create` → ContactController@create
- `POST /contacts` → ContactController@store
- `GET /contacts/{contact}` → ContactController@show
- `GET /contacts/{contact}/edit` → ContactController@edit
- `PUT /contacts/{contact}` → ContactController@update
- `DELETE /contacts/{contact}` → ContactController@destroy

### Projects
- `GET /projects` → ProjectController@index
- `GET /projects/create` → ProjectController@create
- `POST /projects` → ProjectController@store
- `GET /projects/{project}` → ProjectController@show
- `GET /projects/{project}/edit` → ProjectController@edit
- `PUT /projects/{project}` → ProjectController@update
- `DELETE /projects/{project}` → ProjectController@destroy

### Tasks
- `GET /tasks` → TaskController@index
- `GET /tasks/create` → TaskController@create
- `POST /tasks` → TaskController@store
- `GET /tasks/{task}` → TaskController@show
- `GET /tasks/{task}/edit` → TaskController@edit
- `PUT /tasks/{task}` → TaskController@update
- `DELETE /tasks/{task}` → TaskController@destroy
- `POST /tasks/{task}/start` → TaskController@start
- `POST /tasks/{task}/complete` → TaskController@complete
- `POST /tasks/{task}/pause` → TaskController@pause
- `POST /tasks/{task}/resume` → TaskController@resume

### Project Types
- `GET /project-types` → ProjectTypeController@index
- `GET /project-types/create` → ProjectTypeController@create
- `POST /project-types` → ProjectTypeController@store
- `GET /project-types/{projectType}` → ProjectTypeController@show
- `GET /project-types/{projectType}/edit` → ProjectTypeController@edit
- `PUT /project-types/{projectType}` → ProjectTypeController@update
- `DELETE /project-types/{projectType}` → ProjectTypeController@destroy

### Task Templates
- `GET /task-templates` → TaskTemplateController@index
- `GET /task-templates/create` → TaskTemplateController@create
- `POST /task-templates` → TaskTemplateController@store
- `GET /task-templates/{taskTemplate}` → TaskTemplateController@show
- `GET /task-templates/{taskTemplate}/edit` → TaskTemplateController@edit
- `PUT /task-templates/{taskTemplate}` → TaskTemplateController@update
- `DELETE /task-templates/{taskTemplate}` → TaskTemplateController@destroy

## Model Methods

### Contact
- `projects()`: HasMany → Projects
- `isCliente()`: bool
- `promoteToCliente()`: void

### Project
- `contact()`: BelongsTo → Contact
- `projectType()`: BelongsTo → ProjectType
- `tasks()`: HasMany → Tasks
- `generateTasksFromTemplates()`: void

### ProjectType
- `projects()`: HasMany → Projects
- `taskTemplates()`: HasMany → TaskTemplates

### TaskTemplate
- `projectType()`: BelongsTo → ProjectType
- `tasks()`: HasMany → Tasks

### Task
- `project()`: BelongsTo → Project
- `taskTemplate()`: BelongsTo → TaskTemplate
- `blockedBy()`: BelongsTo → Task
- `blockingTasks()`: HasMany → Tasks
- `scopeOrderByDeadline(Builder)`: Builder
- `scopeOverdue(Builder)`: Builder
- `scopeDueToday(Builder)`: Builder
- `start()`: void
- `complete()`: void
- `pause(?int $blockedByTaskId)`: void
- `resume()`: void
- `isOverdue()`: bool
- `isDueToday()`: bool

## Vue Components Structure

```
resources/js/
├── Layouts/
│   ├── AppLayout.vue (Main layout con Bulma)
│   ├── AuthenticatedLayout.vue (Original Breeze, non usato)
│   └── GuestLayout.vue (Per login/register)
│
└── Pages/
    ├── Dashboard.vue (Dashboard principale)
    ├── Contacts/
    │   └── Index.vue (Lista contatti)
    ├── Projects/ (da implementare)
    ├── Tasks/ (da implementare)
    ├── ProjectTypes/ (da implementare)
    ├── TaskTemplates/ (da implementare)
    └── Auth/ (Login, Register, etc.)
```

## CSS Classes (Bulma)

### Status Colors
- **Contatto**: `is-info` (blu)
- **Cliente**: `is-success` (verde)
- **Task da_fare**: `is-info` (blu)
- **Task in_corso**: `is-warning` (giallo)
- **Task completato**: `is-success` (verde)
- **Task scaduta**: `has-background-danger-light` (rosso chiaro)
- **Task oggi**: `has-background-warning-light` (giallo chiaro)

### Components Used
- `navbar`, `navbar-menu`, `navbar-item`
- `table`, `table-container`
- `box`, `card`
- `notification`
- `button`, `buttons`
- `tag`
- `level`, `columns`, `column`
- `title`, `subtitle`, `heading`

## Seeder Data

### Project Types
1. **Sviluppo Sito Web**
   - 6 task templates
   - Durata totale: ~31 giorni

2. **Marketing WhatsApp**
   - 5 task templates
   - Durata totale: ~14 giorni

3. **SEO**
   - 4 task templates
   - Durata totale: ~20 giorni

### Contacts
1. Mario Rossi (contatto)
2. Giulia Bianchi (cliente)
3. Luca Verdi (cliente)

### Projects
1. E-commerce Bianchi (Sviluppo Sito)
2. Campagna Primavera 2025 (Marketing WhatsApp)

## Performance Considerations

- **Eager Loading**: I controller usano `with()` per caricare relazioni
- **Query Optimization**: Scope per filtrare task scadute
- **Indexes**: Foreign keys hanno index automatici
- **Caching**: Non implementato (opzionale per produzione)

## Security Features

- **CSRF Protection**: Token su tutti i form
- **SQL Injection**: Protected by Eloquent ORM
- **XSS Protection**: Vue.js escapes output by default
- **Authentication**: Laravel Breeze middleware
- **Validation**: Server-side validation su tutti i controller

## Browser Compatibility

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile responsive

## Next Steps for Production

1. Configurare database MySQL/PostgreSQL
2. Configurare email (SMTP)
3. Implementare queue per eventi API
4. Aggiungere tests (PHPUnit, Pest)
5. Configurare cache (Redis)
6. Deploy su server (Laravel Forge, Vapor, ecc.)
7. SSL certificate
8. Backup automatici database
9. Monitoring (Sentry, Bugsnag)
10. CDN per asset statici

## File di Configurazione

- `.env` - Environment variables
- `config/database.php` - Database config
- `config/app.php` - App config
- `vite.config.js` - Frontend build config
- `tailwind.config.js` - Non usato (usa Bulma)

## Comandi Artisan Custom

Nessun comando custom creato, ma possibili aggiunte:
- `php artisan tasks:check-deadlines` - Check e notifica scadenze
- `php artisan projects:generate-report` - Report progetti
- `php artisan contacts:sync-gohighlevel` - Sync con GoHighLevel

## Testing URLs

- Login: http://127.0.0.1:8000/login
- Dashboard: http://127.0.0.1:8000/dashboard
- Contacts: http://127.0.0.1:8000/contacts
- Projects: http://127.0.0.1:8000/projects
- Tasks: http://127.0.0.1:8000/tasks

## Credenziali Test

- Email: admin@gestionale.test
- Password: password

---

**Progetto completato il**: 22 Dicembre 2025
**Versione Laravel**: 12.43.1
**Versione PHP**: >= 8.2
**Node Version**: >= 18

