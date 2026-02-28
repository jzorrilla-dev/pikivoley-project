D
Copiar

# 🏐 PikiVoley App

Plataforma web para la gestión de partidos y apuestas informales de **Piki Voley**, el deporte más popular de Paraguay.

---

## 📖 ¿Qué es el Piki Voley?

El Piki Voley (o Piki) es un deporte paraguayo que se juega entre 2 equipos (en modalidades 1x1, 2x2 o 3x3). El objetivo es echar el balón al campo del adversario usando el cuerpo entero, con un máximo de 2 o 3 toques por equipo antes de pasar la pelota al lado contrario. Es un deporte muy popular tanto en formato informal como competitivo, y es muy común que los partidos involucren apuestas en guaraníes entre los participantes.

**Reglamento oficial:** Basado en el reglamento de la APP (Asociación Paraguaya de Piki Voley).

---

## 🎯 Propósito de la App

La app nace para resolver un problema real: los partidos de Piki Voley con apuestas se acuerdan de forma oral, lo que genera conflictos y malentendidos. La plataforma actúa como:

- **Registro transparente** de los acuerdos entre equipos
- **Gestor del partido**: reglas, árbitro, resultado
- **Sistema de reputación** para saber si un jugador es confiable
- **Historial** de partidos y apuestas de cada jugador

> La app **no mueve dinero**, solo registra los acuerdos y resultados entre las partes.

---

## ⚙️ Stack Tecnológico

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 12.52.0 |
| Lenguaje | PHP 8.2 |
| Base de datos | PostgreSQL 16 |
| Entorno | Docker + Laravel Sail |
| OS de desarrollo | Windows 11 + WSL2 |
| Frontend (futuro) | Por definir (Vue o React) |
| App móvil (futuro) | Por definir, consumirá la misma API REST |

---

## 🗃️ Esquema de Base de Datos

### `users`
Tabla estándar de Laravel extendida con campos específicos del dominio.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| name | string | Nombre real |
| nickname | string | Apodo del jugador |
| phone | string | Contacto |
| avatar | string | Foto de perfil |
| reputation_score | integer | Puntaje de reputación acumulado |
| timestamps | - | created_at, updated_at |

---

### `matches`
El partido en sí. Centro del modelo de datos.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| created_by | FK users | Quién creó el partido |
| referee_id | FK users (nullable) | Árbitro designado |
| winner_team_id | FK match_teams (nullable) | Equipo ganador |
| status | enum | pending / confirmed / in_progress / finished / disputed |
| location | string | Descripción del lugar |
| lat / lng | decimal | Coordenadas (para el mapa futuro) |
| scheduled_at | timestamp | Fecha y hora del partido |
| bet_amount | bigint | Monto apostado en guaraníes |
| timestamps | - | created_at, updated_at |

---

### `match_teams`
Representa cada uno de los dos equipos dentro de un partido.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_id | FK matches | Partido al que pertenece |
| captain_id | FK users | Capitán del equipo |
| side | enum | local / visitante |
| timestamps | - | created_at, updated_at |

---

### `match_team_players`
Jugadores que conforman cada equipo en el partido.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_team_id | FK match_teams | Equipo al que pertenece |
| user_id | FK users | Jugador |
| is_captain | boolean | Si es capitán del equipo |
| confirmed | boolean | Si confirmó su participación |
| timestamps | - | created_at, updated_at |

---

### `match_rules`
Reglas acordadas antes del partido. Basadas en el reglamento oficial de la APP.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_id | FK matches | Partido al que aplica |
| modality | enum | 1x1 / 2x2 / 3x3 |
| max_touches | enum | 2 o 3 toques máximos por equipo |
| serve_type | enum | foot_only / hand_allowed |
| points_to_win | integer | Puntos para ganar (default: 12) |
| win_difference | integer | Diferencia mínima para ganar (default: 2) |
| max_extensions | integer | Máximo de alargues permitidos (default: 3) |
| extension_points | integer | Puntos en alargue final (default: 5) |
| additional_notes | text | Reglas custom adicionales |
| timestamps | - | created_at, updated_at |

---

### `match_scores`
Marcador del partido.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_id | FK matches | Partido |
| team_side | enum | local / visitante |
| points | integer | Puntos actuales |
| extensions_played | integer | Alargues jugados |
| timestamps | - | created_at, updated_at |

---

### `referee_invitations`
Gestión de la invitación y aceptación del árbitro.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_id | FK matches | Partido |
| referee_id | FK users | Usuario invitado como árbitro |
| status | enum | pending / accepted / rejected |
| timestamps | - | created_at, updated_at |

---

### `match_disputes`
Registro de disputas cuando hay desacuerdo en el resultado.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_id | FK matches | Partido disputado |
| reported_by | FK users | Quién reportó la disputa |
| claimed_winner | enum | local / visitante |
| reason | text | Motivo del desacuerdo |
| timestamps | - | created_at, updated_at |

---

### `match_cards`
Tarjetas amarillas y rojas emitidas por el árbitro durante el partido.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| match_id | FK matches | Partido |
| player_id | FK users | Jugador sancionado |
| card_type | enum | yellow / red |
| reason | text | Motivo de la tarjeta |
| issued_by | FK users | Árbitro que la emitió |
| timestamps | - | created_at, updated_at |

> Una tarjeta roja suma automáticamente 1 punto al equipo contrario.

---

### `reputation_events`
Historial de eventos que afectan la reputación de cada jugador.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | bigint | PK |
| user_id | FK users | Jugador afectado |
| match_id | FK matches | Partido relacionado |
| type | enum | won / lost / disputed / paid / no_show |
| points | integer | Puntos sumados o restados |
| timestamps | - | created_at, updated_at |

---

## 🔄 Flujo de un Partido

```
1. Usuario crea el partido y define reglas (modalidad, toques, saque, apuesta)
2. Invita al equipo rival (el capitán rival acepta)
3. Ambos equipos confirman sus jugadores
4. [Opcional] Se designa un árbitro → árbitro acepta la invitación
5. Ambos capitanes confirman que están listos → status: in_progress
6. Se juega el partido
7a. [Con árbitro] El árbitro registra el resultado → status: finished
7b. [Sin árbitro] Ambos capitanes reportan el mismo ganador → status: finished
7c. [Desacuerdo] Se genera un match_dispute → status: disputed
8. Se actualizan los reputation_events de todos los jugadores
```

---

## 🏆 Sistema de Reputación

Cada jugador tiene un `reputation_score` que sube o baja según su comportamiento:

| Evento | Puntos |
|--------|--------|
| Ganar un partido | +10 |
| Perder un partido | -2 |
| Partido disputado (generó conflicto) | -15 |
| No presentarse (no_show) | -20 |
| Historial limpio de pagos | +5 |

> La reputación es pública y visible en el perfil de cada jugador, generando presión social para comportarse de forma honesta en comunidades pequeñas.

---

## 🗺️ Funcionalidades Futuras

- **Mapa de partidos:** Ver en un mapa dónde y cuándo habrá partidos próximos (lat/lng ya está en el esquema)
- **Calendario:** Vista de partidos programados
- **App móvil:** La API REST actual será consumida por una app móvil
- **Notificaciones push:** Para invitaciones, confirmaciones y resultados
- **Estadísticas:** Ranking de jugadores por zona, rachas, historial completo

---

## 🚀 Instalación y Setup

### Requisitos
- PHP 8.2+
- Composer
- Docker + Docker Compose
- WSL2 (recomendado en Windows)

### Pasos

```bash
# Clonar el repositorio
git clone https://github.com/tu-usuario/pikivoley.git
cd pikivoley

# Instalar dependencias
composer install

# Copiar el archivo de entorno
cp .env.example .env

# Levantar los contenedores
./vendor/bin/sail up -d

# Generar la clave de la app
./vendor/bin/sail artisan key:generate

# Ejecutar las migraciones
./vendor/bin/sail artisan migrate

# [Opcional] Ejecutar seeders
./vendor/bin/sail artisan db:seed
```

La app estará disponible en `http://localhost`.

---

## 📋 Reglas Oficiales del Piki (Resumen APP)

- Se juega a **12 puntos**, con diferencia mínima de 2
- En empate (8-8, 9-9...) se va a **alargue hasta 5 puntos** (máximo 3 alargues; el 4to es muerte súbita)
- Cambio de lado a los **6 puntos iniciales**
- **Toques:** mínimo 2, máximo 3 por equipo (en 1x1 un jugador puede tocar 2 veces seguidas)
- El **primer saque** no cuenta como punto, es para disputar el saque
- Para ganar se necesita **diferencia de 2 puntos**
- El balón **no puede tocar la red en el saque**
- **Tarjeta roja = 1 punto para el equipo contrario**
- La cancha mide **18 o 20m de largo x 9 o 10m de ancho**
- La red mide entre **2.40 y 2.50m de altura**

---

## 📄 Licencia

MIT

---

*Proyecto desarrollado con ❤️ para la comunidad pikivolera del Paraguay 🇵🇾*