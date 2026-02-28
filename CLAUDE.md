# CLAUDE.md — Contexto del Proyecto PikiVoley

Este archivo es para agentes de IA (Claude Code, OpenCode, etc.) y provee el contexto completo del proyecto para que puedan asistir de forma efectiva sin necesidad de explicaciones adicionales.

---

## 🎯 ¿Qué es este proyecto?

**PikiVoley App** es una plataforma web para gestionar partidos y apuestas informales de Piki Voley, el deporte más popular del Paraguay. El Piki Voley es una variante del futvoley que se juega en modalidades 1x1, 2x2 o 3x3, donde es muy común apostar dinero (guaraníes) entre los equipos.

La app **no mueve dinero**. Actúa como registro transparente de acuerdos, gestor de partidos y sistema de reputación para jugadores.

---

## ⚙️ Stack

- **Framework:** Laravel 12.52.0
- **PHP:** 8.2
- **Base de datos:** PostgreSQL 16
- **Entorno:** Docker via Laravel Sail
- **OS de desarrollo:** Windows 11 + WSL2
- **Frontend:** Por definir (pendiente)
- **App móvil:** Futura, consumirá la misma API REST

---

## 📁 Estructura relevante del proyecto

```
pikivoley/
├── app/
│   ├── Models/          # Modelos Eloquent
│   ├── Http/
│   │   ├── Controllers/ # Controladores
│   │   └── Requests/    # Form Requests para validación
│   └── Services/        # Lógica de negocio compleja
├── database/
│   ├── migrations/      # Todas las migraciones del esquema
│   └── seeders/         # Seeders para datos de prueba
├── routes/
│   ├── api.php          # Rutas de la API REST
│   └── web.php          # Rutas web
├── tests/               # Tests Feature y Unit
├── CLAUDE.md            # Este archivo
└── README.md            # Documentación general
```

---

## 🗃️ Modelos y relaciones clave

### User
- Tiene muchos `matches` (como creador)
- Puede ser `referee` en muchos matches
- Pertenece a muchos `match_teams` a través de `match_team_players`
- Tiene muchos `reputation_events`

### Match
- Pertenece a `User` (created_by)
- Tiene un `referee` opcional (User)
- Tiene exactamente 2 `match_teams` (local y visitante)
- Tiene una `match_rule`
- Tiene `match_scores`, `match_disputes`, `match_cards`, `reputation_events`
- **Status flow:** `pending → confirmed → in_progress → finished | disputed`

### MatchTeam
- Pertenece a un `Match`
- Tiene un `captain` (User)
- Tiene muchos jugadores a través de `match_team_players`
- `side`: siempre es `local` o `visitante`

### MatchRule
- Pertenece a un `Match` (relación 1:1)
- Define las reglas acordadas antes del partido

### RefereeInvitation
- Un árbitro debe **aceptar** la invitación antes de que el partido comience
- Solo puede haber una invitación activa por partido
- Status: `pending → accepted | rejected`

### MatchDispute
- Se crea cuando dos capitanes reportan ganadores distintos
- Cambia el status del match a `disputed`
- Afecta negativamente la reputación de ambos equipos

### ReputationEvent
- Se crean automáticamente al finalizar un partido
- Tipos: `won`, `lost`, `disputed`, `paid`, `no_show`

---

## 🔄 Flujo principal de un partido

```
1. Usuario crea Match + MatchRule
2. Invita al equipo rival (capitán acepta)
3. Jugadores confirman participación (match_team_players.confirmed = true)
4. [Opcional] Se invita árbitro → RefereeInvitation → árbitro acepta
5. Ambos capitanes marcan ready → status: in_progress
6. Se juega el partido
7a. [Con árbitro] Árbitro registra ganador → status: finished
7b. [Sin árbitro] Ambos capitanes reportan mismo ganador → status: finished
7c. [Desacuerdo] Se crea MatchDispute → status: disputed
8. Se generan ReputationEvents para todos los jugadores
```

---

## 🗺️ Esquema de base de datos completo

### Tabla: `users`
```
id, name, nickname, phone, avatar, reputation_score, timestamps
```

### Tabla: `matches`
```
id, created_by (FK users), referee_id (FK users, nullable),
winner_team_id (FK match_teams, nullable),
status (enum: pending/confirmed/in_progress/finished/disputed),
location (string), lat (decimal), lng (decimal),
scheduled_at (timestamp), bet_amount (bigint, guaraníes), timestamps
```

### Tabla: `match_teams`
```
id, match_id (FK matches), captain_id (FK users),
side (enum: local/visitante), timestamps
```

### Tabla: `match_team_players`
```
id, match_team_id (FK match_teams), user_id (FK users),
is_captain (boolean), confirmed (boolean), timestamps
```

### Tabla: `match_rules`
```
id, match_id (FK matches),
modality (enum: 1x1/2x2/3x3),
max_touches (enum: 2/3),
serve_type (enum: foot_only/hand_allowed),
points_to_win (int, default: 12),
win_difference (int, default: 2),
max_extensions (int, default: 3),
extension_points (int, default: 5),
additional_notes (text, nullable), timestamps
```

### Tabla: `match_scores`
```
id, match_id (FK matches),
team_side (enum: local/visitante),
points (int), extensions_played (int), timestamps
```

### Tabla: `referee_invitations`
```
id, match_id (FK matches), referee_id (FK users),
status (enum: pending/accepted/rejected), timestamps
```

### Tabla: `match_disputes`
```
id, match_id (FK matches), reported_by (FK users),
claimed_winner (enum: local/visitante),
reason (text), timestamps
```

### Tabla: `match_cards`
```
id, match_id (FK matches), player_id (FK users),
card_type (enum: yellow/red),
reason (text), issued_by (FK users), timestamps
```
> ⚠️ Una tarjeta roja suma automáticamente 1 punto al equipo contrario. Esto se maneja en la capa de servicio, no en la base de datos.

### Tabla: `reputation_events`
```
id, user_id (FK users), match_id (FK matches),
type (enum: won/lost/disputed/paid/no_show),
points (int), timestamps
```

---

## 📏 Reglas de negocio importantes

1. **Un partido siempre tiene exactamente 2 match_teams**: local y visitante.
2. **El árbitro no puede ser jugador del partido** que arbitra.
3. **El primer saque no cuenta como punto** (regla oficial del Piki).
4. **Ganar requiere diferencia de 2 puntos** (ej: 12-10, no 12-11).
5. **Máximo 3 alargues**; el 4to alargue es muerte súbita a 5 puntos sin posibilidad de bajar.
6. **Tarjeta roja = 1 punto automático** para el equipo contrario.
7. **Sin árbitro:** ambos capitanes deben reportar el mismo ganador para que el partido cierre como `finished`. Si difieren, pasa a `disputed`.
8. **El bet_amount se guarda en guaraníes** (moneda paraguaya). No hay conversión de moneda.
9. **reputation_score** nunca puede ser negativo (mínimo 0).

---

## 🧪 Convenciones de código

- **Modelos:** singular en inglés (`Match`, `MatchTeam`, `ReputationEvent`)
- **Controladores:** resource controllers con API resources (`MatchController`, `MatchTeamController`)
- **Form Requests:** para toda validación (`StoreMatchRequest`, `UpdateMatchStatusRequest`)
- **Services:** para lógica compleja (`MatchResultService`, `ReputationService`)
- **Rutas:** todas bajo `/api/v1/` con autenticación Sanctum
- **Tests:** Feature tests para todos los endpoints, Unit tests para Services

---
# Project Rules
- All PHP, Composer, Artisan, and Bun or pnpm commands MUST be executed through Laravel Sail.
- Correct format: `sail [command]` (e.g., `sail artisan migrate`).
- NEVER execute commands directly on the host.
---

## 🚀 Comandos frecuentes

```bash
# Levantar entorno
./vendor/bin/sail up -d

# Apagar entorno
./vendor/bin/sail down

# Migraciones
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan migrate:fresh --seed

# Crear archivos
./vendor/bin/sail artisan make:model NombreModelo -mfsc
./vendor/bin/sail artisan make:controller NombreController --api --model=Nombre
./vendor/bin/sail artisan make:request StoreNombreRequest

# Tests
./vendor/bin/sail artisan test
./vendor/bin/sail artisan test --filter NombreTest

# Tinker (consola interactiva)
./vendor/bin/sail artisan tinker
```

---

## 📌 Funcionalidades pendientes / roadmap

- [ ] Migraciones completas y modelos con relaciones
- [ ] Autenticación con Sanctum
- [ ] CRUD de partidos
- [ ] Sistema de invitaciones (rivales y árbitros)
- [ ] Registro de resultados y lógica de disputas
- [ ] Sistema de reputación automático
- [ ] API REST completa con recursos y paginación
- [ ] Tests de feature para todos los endpoints
- [ ] Frontend web
- [ ] Mapa de partidos con geolocalización
- [ ] Calendario de partidos
- [ ] App móvil (consume la misma API)
- [ ] Notificaciones push

---

## 🌍 Contexto cultural importante

- El Piki Voley es **muy popular en Paraguay**, especialmente en formato informal
- Las apuestas son en **guaraníes (₲)**, la moneda paraguaya
- Los partidos informales con apuestas altas son comunes, y el sistema de reputación es clave para la confianza entre jugadores
- La app está pensada para comunidades locales donde **todos se conocen**, lo que hace que la reputación pública sea un incentivo fuerte para comportarse honestamente

---

*Última actualización: Febrero 2026*