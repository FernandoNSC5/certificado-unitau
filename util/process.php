<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

//Base data user
$host = '184.107.51.101:3306';
$user = 'cicted_adm';
$senha = 'cictedunitau2015';
$db = 'cicted_bd_2018';

//Base connection
$conn = mysqli_connect($host, $user, $senha, $db);

//Brings info in UTF8 codification
mysqli_set_charset($conn, "utf8");

if(! $conn ) {
  die('Sorry :c There\`s a problem in our Database');
}

//Query process
if(isset($_POST['cpf']) && strlen($_POST['cpf']) > 0){
  $qrr = "SELECT t.identificador_trabalho AS identificador, t.titulo_trabalho, orientadores.nomes AS orientador, autores.nomes AS alunos, e.nome_evento AS evento, area.nome_area AS area, s.nome_subarea AS subarea, avaliacoes1.media AS media, autores.cpf

FROM (SELECT t.id_trabalho, GROUP_CONCAT(if(atrab.orientador IS FALSE,ud.nome_usuario,null) SEPARATOR ', ') AS nomes, GROUP_CONCAT(if(atrab.orientador IS FALSE,u.email_usuario,null) SEPARATOR ', ') AS email, GROUP_CONCAT(if(atrab.orientador IS FALSE,ud.cpf_usuario,null) SEPARATOR ', ') AS cpf FROM TB_TRABALHO t LEFT OUTER JOIN TB_AUTOR_TRABALHO atrab ON t.id_trabalho = atrab.id_trabalho LEFT OUTER JOIN TB_USUARIO u ON atrab.id_usuario = u.id_usuario LEFT OUTER JOIN TB_USUARIO_DADOS ud ON ud.id_usuario_dados = u.usuario_dados_id GROUP BY t.id_trabalho) autores, 

(SELECT t.id_trabalho, GROUP_CONCAT(if(atrab.orientador IS TRUE,ud.nome_usuario,null) SEPARATOR ', ') AS nomes, GROUP_CONCAT(if(atrab.orientador IS TRUE,u.email_usuario,null) SEPARATOR ', ') AS email FROM TB_TRABALHO t LEFT OUTER JOIN TB_AUTOR_TRABALHO atrab ON t.id_trabalho = atrab.id_trabalho LEFT OUTER JOIN TB_USUARIO u ON atrab.id_usuario = u.id_usuario LEFT OUTER JOIN TB_USUARIO_DADOS ud ON ud.id_usuario_dados = u.usuario_dados_id GROUP BY t.id_trabalho) orientadores,  

TB_TRABALHO t 
INNER JOIN TB_SITUACAO_TRABALHO st ON st.id_situacao_trabalho = t.id_situacao_trabalho 
INNER JOIN TB_AUTOR_TRABALHO auttrab ON auttrab.id_trabalho = t.id_trabalho 
INNER JOIN TB_USUARIO u ON u.id_usuario = auttrab.id_usuario 
INNER JOIN TB_USUARIO_DADOS ud ON ud.id_usuario_dados = u.usuario_dados_id 
INNER JOIN TB_EVENTO e ON t.id_evento = e.id_evento 
LEFT OUTER JOIN TB_SUBAREA s ON s.id_subarea = t.id_subarea 
LEFT OUTER JOIN TB_AREA area ON area.id_area = s.id_area 
INNER JOIN TB_PERIODO_APRESENTACAO pa ON pa.id_periodo = t.id_periodo 
INNER JOIN (SELECT t.id_trabalho, CAST(AVG(a.nota_avaliacao) AS DECIMAL(5, 2)) AS media FROM TB_TRABALHO t LEFT OUTER JOIN TB_AVALIACAO a ON t.id_trabalho = a.id_trabalho WHERE a.id_tipo_avaliacao = 2 GROUP BY a.id_trabalho) avaliacoes1 ON avaliacoes1.id_trabalho = t.id_trabalho
WHERE t.id_trabalho = autores.id_trabalho 
AND t.id_trabalho = orientadores.id_trabalho 
AND avaliacoes1.media IS NOT NULL
AND (t.id_evento != 2 AND t.id_evento != 3 AND t.id_evento != 4)
AND autores.cpf LIKE '%".$_POST['cpf']."%'
GROUP BY t.id_trabalho";

}else if(isset($_POST['name']) && strlen($_POST['name']) > 0){
  $qrr = "SELECT t.identificador_trabalho AS identificador, t.titulo_trabalho, orientadores.nomes AS orientador, alunos.nomes AS alunos, e.nome_evento AS evento, area.nome_area AS area, s.nome_subarea AS subarea, avaliacoes1.media

FROM (SELECT t.id_trabalho, GROUP_CONCAT(a.nome_aluno SEPARATOR ', ') AS nomes FROM TB_TRABALHO t LEFT OUTER JOIN TB_ALUNO a ON t.id_trabalho = a.id_trabalho GROUP BY t.id_trabalho) alunos, 

(SELECT t.id_trabalho, GROUP_CONCAT(if(atrab.orientador IS TRUE,ud.nome_usuario,null) SEPARATOR ', ') AS nomes, GROUP_CONCAT(if(atrab.orientador IS TRUE,u.email_usuario,null) SEPARATOR ', ') AS email FROM TB_TRABALHO t LEFT OUTER JOIN TB_AUTOR_TRABALHO atrab ON t.id_trabalho = atrab.id_trabalho LEFT OUTER JOIN TB_USUARIO u ON atrab.id_usuario = u.id_usuario LEFT OUTER JOIN TB_USUARIO_DADOS ud ON ud.id_usuario_dados = u.usuario_dados_id GROUP BY t.id_trabalho) orientadores,

TB_TRABALHO t
INNER JOIN TB_SITUACAO_TRABALHO st ON st.id_situacao_trabalho = t.id_situacao_trabalho 
INNER JOIN TB_AUTOR_TRABALHO auttrab ON auttrab.id_trabalho = t.id_trabalho 
INNER JOIN TB_USUARIO u ON u.id_usuario = auttrab.id_usuario 
INNER JOIN TB_USUARIO_DADOS ud ON ud.id_usuario_dados = u.usuario_dados_id 
INNER JOIN TB_EVENTO e ON t.id_evento = e.id_evento 
LEFT OUTER JOIN TB_SUBAREA s ON s.id_subarea = t.id_subarea 
LEFT OUTER JOIN TB_AREA area ON area.id_area = s.id_area 
INNER JOIN TB_PERIODO_APRESENTACAO pa ON pa.id_periodo = t.id_periodo 
INNER JOIN (SELECT t.id_trabalho, CAST(AVG(a.nota_avaliacao) AS DECIMAL(5, 2)) AS media FROM TB_TRABALHO t LEFT OUTER JOIN TB_AVALIACAO a ON t.id_trabalho = a.id_trabalho WHERE a.id_tipo_avaliacao = 2 GROUP BY a.id_trabalho) avaliacoes1 ON avaliacoes1.id_trabalho = t.id_trabalho
WHERE t.id_trabalho = alunos.id_trabalho 
AND t.id_trabalho = orientadores.id_trabalho 
AND avaliacoes1.media IS NOT NULL
AND (t.id_evento = 2 OR t.id_evento = 3 OR t.id_evento = 4)
AND alunos.nomes LIKE '%".$_POST['name']."%'
GROUP BY t.id_trabalho LIMIT 1500";
} else {
    die('Preencher Nome Completo');
}

$trabalhos = [];

$getTrabalhos = mysqli_query($conn, $qrr);

if( !$getTrabalhos || $getTrabalhos->num_rows === 0 ) {
  echo "<script> alert('O nome/cpf informado não foi encontrado em nossa base de dados :c');";
  echo "location.href='../index.php'</script>";
  exit();
}
while($row = mysqli_fetch_array($getTrabalhos, MYSQLI_ASSOC)) {
  array_push($trabalhos, ['orientador' => $row['orientador'], 'alunos' => $row['alunos'], 'titulo_trabalho' => $row['titulo_trabalho'], 'evento' => $row['evento'], 'area' => $row['area'], 'subarea' => $row['subarea']]);
}

mysqli_close($conn);

//***************************************************************
//					PDF BUILDER MODULE
//***************************************************************
try{
	//Alpha PDF Framework
	require('fpdf/alphapdf.php');

	$pdf = new AlphaPDF();

	foreach($trabalhos as $trabalho){
		$build_text = utf8_decode($trabalho['alunos'] . " orientados por " . $trabalho['orientador'] . " apresentaram o trabalho \"" . $trabalho['titulo_trabalho'] . "\" no " . $trabalho['evento'] . " durante o VII Congresso Internacional de Ciência, Tecnologia e Desenvolvimento, realizado naa Universidade de Taubaté, no dia 20 de setembro de 2018.");

	    $pdf->AddPage('L');
	    $pdf->SetLineWidth(1.5);
	    $pdf->Image('../images/base_model.png',0,0,297);
	    $pdf->SetAlpha(1);

	    //Font Family - Font Size
	    $pdf->SetFont('Arial', '', 10);
	    //Axys fine ajusts
	    $pdf->SetXY(30,80);
	    //Cell position
	    $pdf->MultiCell(240, 8, $build_text, '', 'J', 0); 

	  } 

	  //Output PDF file
	  $pdf->Output(); 

}catch(Exception $e){
	echo "Ops! An error ocurred while trying to build the PDF file :c Please, contact suport :D";
} finally {
	echo "<script> alert('Ops! Algo não funcionou bem :c Por favor, contate o incrível pessoal do suporte (gentilmente) :D');";
	echo "location.href='../index.php'</script>";
}
