<!-------------------------------------
   @Author: Fernando N. S. Costa
   Universidade de Taubaté - 2018
   https://www.linkedin.com/in/fernandonsc5/
--------------------------------------->

<!DOCTYPE html>
<html lang="pt-br">

	<head>

		<title>Unitau - Certificados</title>
		<!-- Basics -->
		<meta charset="UTF-8">
		<meta name="viewport" contente="width=device-width, initial-scale=1">

		<!-- Main css & w3School framework -->
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

	</head>

	<body>

		<div class="background w3-animate-opacity" style="background-image: url('images/bg-01.jpg');">
			<div class="container">
				<div class="wrap w3-animate-opacity">

					<form id="certificate-form" class="certificate-form validate-form" action="util/process.php" method="POST" onsubmit="submitFFF(event)">
						<!-- Top form title -->
						<span class="form-title">
							Certificados
							<p>Preencha um dos campos abaixo</p>
						</span>

						<!-- form data -->
						<div class="wrap-input">
							<input id="cpf" class="input" type="text" name="cpf" placeholder="CPF">
							<span class="focus-input"/>
						</div>

						<div class="wrap-input">
							<input id="name" class="input" tyle="text" name="name" placeholder="Nome Completo">
							<span class="focus-input"/>
						</div>

						<!-- submit form button -->
						<div class="container-form-btn" >
							<button class="form-btn">
								Gerar Certificado
							</button>
						</div>
					</form>

				</div>
				<p class="footer">Universidade de Taubaté<br>2018</p>
			</div>
		</div>

	</body>

</html>

<!-- JS input mask for cpf -->
<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
<script>
	function submitFFF(e){
		var cpf = $("#cpf").val();
		var name = $("#name").val();

		if(cpf != null && cpf.length){
			if(cpf.length != 11){
				alert("Digite um cpf valido");
				e.preventDefault();
				return;
			}
            aux = 0;
            for (i = 0; i < 9; i++)
                aux += parseInt(cpf.charAt(i)) * (10 - i);
            check = 11 - (aux % 11);
            if (check == 10 || check == 11)
                check = 0;
            if (check != parseInt(cpf.charAt(9))){
				alert("Digite um cpf valido");
                e.preventDefault();
                return;
            }

            //calculo segunda parte
            aux = 0;
            for (i = 0; i < 10; i++)
                aux += parseInt(cpf.charAt(i)) * (11 - i);
            check = 11 - (aux % 11);
            if (check == 10 || check == 11)
                check = 0;
            if (check != parseInt(cpf.charAt(10))){
				alert("Digite um cpf valido");
                e.preventDefault();
                return;
            }
			return;
		} else if(name != null && name.length){
			return;
		}

		e.preventDefault();


	}
</script>