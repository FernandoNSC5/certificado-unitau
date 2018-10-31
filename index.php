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

					<form id="certificate-form" class="certificate-form validate-form" action="util/process.php" method="POST">
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
<script>
	
	function format(mask, doc){
		var i = doc.value.length;
		var out = mask.substring(0,1);
		var text = mask.substring(i);
		if(text.substring(0,1) != out){
			doc.value += text.substring(0,1);
		}
	}

	$(document).ready(function() {
        $('#certificate-form').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                cpf: {
                    validators: {
                        callback: {
                            message: 'CPF Invalido',
                            callback: function(value) {
                                  //retira mascara e nao numeros
                                cpf = value.replace(/[^\d]+/g, '');
                                if (cpf == '') return false;

                                if (cpf.length != 11) return false;

                                // testa se os 11 digitos são iguais, que não pode.
                                var valido = 0;
                                for (i = 1; i < 11; i++) {
                                    if (cpf.charAt(0) != cpf.charAt(i)) valido = 1;
                                }
                                if (valido == 0) return false;

                               //  calculo primeira parte
                                aux = 0;
                                for (i = 0; i < 9; i++)
                                    aux += parseInt(cpf.charAt(i)) * (10 - i);
                                check = 11 - (aux % 11);
                                if (check == 10 || check == 11)
                                    check = 0;
                                if (check != parseInt(cpf.charAt(9)))
                                    return false;

                                //calculo segunda parte
                                aux = 0;
                                for (i = 0; i < 10; i++)
                                    aux += parseInt(cpf.charAt(i)) * (11 - i);
                                check = 11 - (aux % 11);
                                if (check == 10 || check == 11)
                                    check = 0;
                                if (check != parseInt(cpf.charAt(10)))
                                    return false;
                                return true;

                            }
                        }
                    }
                }
            }
        })

    });
</script>