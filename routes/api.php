<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - GearBase API
|--------------------------------------------------------------------------
|
| Todas as rotas registradas neste arquivo recebem automaticamente:
| 1. Prefixo de URL '/api' (definido em bootstrap/app.php).
| 2. Grupo de middleware 'api', que aplica 'throttle' (limite de taxa de requisições)
|    e desativa cookies de sessão e proteção CSRF típica de formulários web Blade.
|
*/

// =========================================================================
// 1. ROTAS PÚBLICAS DE AUTENTICAÇÃO (Não exigem Token Bearer)
// =========================================================================

// POST /api/register -> Cadastro de novos usuários/mecânicos
Route::post('/register', [AuthController::class, 'register']);

// POST /api/login    -> Autenticação com e-mail e senha, retornando o Personal Access Token
Route::post('/login', [AuthController::class, 'login']);


// =========================================================================
// 2. ROTAS PROTEGIDAS PELO LARAVEL SANCTUM (Exigem Authorization: Bearer <token>)
// =========================================================================

Route::middleware('auth:sanctum')->group(function () {

    // POST /api/logout -> Revoga o token atual do dispositivo
    Route::post('/logout', [AuthController::class, 'logout']);

    // GET /api/me      -> Retorna os dados do usuário atualmente autenticado
    Route::get('/me', [AuthController::class, 'me']);

    /*
    |----------------------------------------------------------------------
    | O que é Route::apiResource()?
    |----------------------------------------------------------------------
    |
    | O método apiResource() é um atalho elegante do Laravel para registrar
    | automaticamente as 5 rotas essenciais de um CRUD RESTful:
    |
    | Verbo HTTP | URI                   | Ação Controller | Descrição
    | -----------|-----------------------|-----------------|-----------------------------
    | GET        | /api/{recurso}        | index           | Lista registros (paginados)
    | POST       | /api/{recurso}        | store           | Salva um novo registro
    | GET        | /api/{recurso}/{id}   | show            | Detalhes de um registro
    | PUT/PATCH  | /api/{recurso}/{id}   | update          | Atualiza um registro
    | DELETE     | /api/{recurso}/{id}   | destroy         | Remove um registro
    |
    | Diferente do Route::resource() comum, o apiResource() omite as rotas
    | 'create' e 'edit', pois em APIs REST não entregamos telas de formulário HTML.
    */

    // CRUD de Usuários / Mecânicos
    Route::apiResource('users', UserController::class);

    // CRUD de Peças Automotivas
    Route::apiResource('parts', PartController::class);

    // CRUD de Ferramentas da Oficina
    Route::apiResource('tools', ToolController::class);

    // CRUD de Ordens de Manutenção
    Route::apiResource('maintenances', MaintenanceController::class);
});
