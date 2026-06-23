<?php 

require_once __DIR__ . '/../../config/config.php'; 

// Inicia a sessão se ainda não estiver iniciada 

function start_session() 
{    
     if (session_status() == PHP_SESSION_NONE) {         
        session_start();     
     } 
}  

// Verifica se a sessão do utilizador está ativa 

function check_session() 
{     
    return isset($_SESSION['utilizador']); 
}  

// Redireciona automaticamente se não houver sessão iniciada 

function redirect_if_not_logged($redirect_to = '/public/login.php') 
{     start_session();    
    if (!check_session()) {         
         header("Location: " . BASE_URL . $redirect_to);
         exit;     
    } 
    
}  

function logout_and_redirect($redirect_to = '/public/index.php') 
{     start_session();       // Garante que a sessão foi iniciada     
      session_unset();       // Remove todas as variáveis da sessão     
      session_destroy();     // Destrói completamente a sessão      
      // Redireciona para a página de login com caminho absoluto   
        header("Location: " . BASE_URL . $redirect_to); 
            exit; 
} 


function definir_mensagem($tipo, $texto)
{
    start_session();

    $_SESSION['mensagem_modal'] = [
        'tipo' => $tipo,
        'texto' => $texto
    ];
}

function badge_criticidade($criticidade)
{
    $texto = $criticidade ?? '–';
    $valor = strtolower(trim($texto));
    $classe = 'badge text-bg-success';

    if ($valor === 'média' || $valor === 'media') {
        $classe = 'badge text-bg-warning';
    } elseif ($valor === 'alta') {
        $classe = 'badge text-bg-orange';
    } elseif ($valor === 'suporte de vida') {
        $classe = 'badge text-bg-danger';
    }

    return '<span class="' . $classe . '">' . htmlspecialchars($texto) . '</span>';
}
