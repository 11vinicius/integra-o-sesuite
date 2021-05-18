<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


 
 function connect_soap(){
		 
	$wsdl      = "https://desenvolvimento.c3isystems.com.br/se/ws/wf_ws.php?wsdl";
	$location  = "https://desenvolvimento.c3isystems.com.br/apigateway/se/ws/wf_ws.php";



	$options = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		),
		'http' => array(
			'header' => "Authorization: Basic " . base64_encode("ws:C3i@20#")
		)
	);

	 

	$client = new Soapclient (
		$wsdl, array (
			"trace"          => 1, // Habilita o trace
			"exceptions"     => 1, // Trata as exceção
			"location"       => $location, // Nao funciona com o location padr�o
			"stream_context" => stream_context_create( $options )
		)
	);
	 
	return $client;
 }
 
 
 

    /*
     Instanciar o workflow
     */
        
    $request = array(
        "ProcessID"     => "PC",
        "WorkflowTitle" => "Disparado via php",
        "UserID"        => ""
    );

	$client_wf = connect_soap();
	$workflow = $client_wf->newWorkflow($request);
   
   

    /*
     Alterar conteúdo do formulário 
     */

    $atributos["EntityAttribute"] = array(
        "EntityAttributeID" => "nome",
        "EntityAttributeValue" => "Adilson M."
        
    );
 
    $request_form = [
        "WorkflowID"              => $workflow->RecordID,
        "EntityID"                => "compras",
        "EntityAttributeList"     => $atributos,
        "RelationshipList"        => null,
        "EntityAttributeFileList" => null
    ];
	
	$client_form = connect_soap();
    $formulario = $client_form->editEntityRecord( $request_form ); 


    /*
     Executar a atvidade
     */
        
    $req_ex = array(
        "WorkflowID"        => $workflow->RecordID,
        "ActivityID"        => "ATV-01",
        "ActionSequence"    => 1,
        "UserID"            => "",
        "ActivityOrder"     => ''
    );

	$client_ex = connect_soap();
	$execute = $client_ex->executeActivity( $req_ex );
   
   
    echo "<pre>";
    print_r( $execute );
    echo "</pre>";



    
// } catch (Exception $e) {
    
//     echo "<pre>";
//     print_r( $e );
//     echo "</pre>";

// }




/*
require_once('inc/functions.php'); 
require_once('inc/languages.php'); 
require_once('Smarty.class.php');

$smarty = new Smarty;
$current_lang = set_lang();
$smarty->assign('lang',$lang[$current_lang]);
$extensions = '';
$loaded_ext = get_loaded_extensions(); foreach ($loaded_ext as $ext) $extensions.=$ext.', ';
$smarty->assign('extensions',$extensions);

if (((extension_loaded('mysqli')))&&(@mysqli_connect('localhost','root','vertrigo'))) 
 $smarty->assign('password_status', false); 
else 
 $smarty->assign('password_status', true);

$smarty->assign('php_version', phpversion());
 
$smarty->display('index.tpl');
*/
?>