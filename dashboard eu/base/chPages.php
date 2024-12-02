<?php 
    $page = $_REQUEST['page'] ?? '';

    switch($page) {
        case 'lista_usu.php':
            require_once "lista_usu.php";
            break;

        case 'lista_prod.php':
            require_once "lista_prod.php";
            break;

        case 'add_prod.php':
            require_once "fadd_prod.php";
            break;

        case 'add_usu.php':
            require_once "fadd_usu.php";
            break;

        case 'remove.php':
            require_once "remove.php";
            break;

        case 'fedit_usu.php':
            require_once "fedit_usu.php";
            break;

        case 'fedit_prod.php':
            require_once "fedit_prod.php";
            break;

        case 'lista_venda.php':
            require_once "lista_venda.php";
            break;

        case 'perfil.php':
            require_once "perfil.php";
            break;
                
        case 'edit_loja.php':
            require_once "edit_loja.php";
            break;

        case 'lista_vendas_adm.php':
            require_once "lista_vendas_adm.php";
            break;

        case 'lista_lojistas.php':
            require_once "lista_lojistas.php";
            break;

        case 'lista_lojas.php':
            require_once "lista_lojas.php";
            break;

        case 'lista_prodadm.php':
            require_once "lista_prodadm.php";
            break;
        default:
            
            break;
    }

?>