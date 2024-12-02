<?php

if(isset($_POST['submit']))
{
    print_r($_POST['nome']);
    print_r($_POST['email']);
    
}


?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration Form</title>
  <!-- MDB CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.css" rel="stylesheet">
  <style>
    .card-registration .select-input.form-control[readonly]:not([disabled]) {
      font-size: 1rem;
      line-height: 2.15;
      padding-left: .75em;
      padding-right: .75em;
    }
    .card-registration .select-arrow {
      top: 13px;
    }
    section {
      width: 100vw; 
      height: 100vh; 
      position: relative;
      background-image: url('img/background-azul.jpg');
      background-size: cover; 
      background-position: center; 
      background-repeat: no-repeat; 
    }
  </style>
</head>
<body>
  <section class="h-100">
    <form action="cadastro.php" method="post">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col">
          <div class="card card-registration my-4">
            <div class="row g-0">
              <div class="col-xl-6 d-none d-xl-block">
                <div class="col-12 col-xl-9">
                  <h1 class="h1 mb-4" style="margin-left:60px; margin-top: 220px;">Web Shoppe</h1>
                  <p class="lead mb-5" style="margin-left:60px;">Não importa o tamanho do seu negócio, com nossa hospedagem de lojas, você tem a oportunidade de oferecer uma experiência de compra online excelente e de forma segura para seus clientes.</p>
                  <div class="text-endx">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16">
                      <path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                    </svg>
                  </div>
                </div>
              </div>
              <div class="col-xl-6">
                <div class="card-body p-md-5 text-black">
                  <h3 class="mb-5 text-uppercase" style="text-align:center;">Cadastre-se</h3>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" name="nome" id="nome" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1m">Nome</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="text" id="Sobrenome" name="sobrenome" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1n">Sobrenome</label>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                        <input type="password" id="senha" name="senha" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1m1">Senha</label>
                      </div>
                    </div>
                    <div class="col-md-6 mb-4">
                      <div data-mdb-input-init class="form-outline">
                      <input type="password" id="senha" name="senha" class="form-control form-control-lg" />
                        <label class="form-label" for="form3Example1n1">Confirmar senha</label>
                      </div>
                    </div>
                  </div>

                  <div class="d-md-flex justify-content-start align-items-center mb-4 py-2">
                    <h6 class="mb-0 me-4">Genero </h6>
                    <div class="form-check form-check-inline mb-0 me-4">
                      <input class="form-check-input" type="radio" name="genero" id="femaleGender" value="femenino" />
                      <label class="form-check-label" for="femaleGender">Femenino</label>
                    </div>
                    <div class="form-check form-check-inline mb-0 me-4">
                      <input class="form-check-input" type="radio" name="genero" id="maleGender" value="masculino" />
                      <label class="form-check-label" for="maleGender">Masculino</label>
                    </div>
                    <div class="form-check form-check-inline mb-0">
                      <input class="form-check-input" type="radio" name="genero" id="otherGender" value="outro" />
                      <label class="form-check-label" for="otherGender">Outros</label>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 mb-4">
                      <select data-mdb-select-init>
                        <option value="1">Estado</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                      </select>
                    </div>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="email"  name="email"class="form-control form-control-lg" />
                    <label class="form-label" for="form3Example9">Email</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                  <input type="email" id="email"  name="email"class="form-control form-control-lg" />
                    <label class="form-label" for="form3Example90">Confirmar Email</label>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <input type="Number" id="cpf_cnpj" name="cpf_cnpj" class="form-control form-control-lg" />
                    <label class="form-label" for="form3Example99">CPF/CNPJ</label>
                  </div>

                  <div class="d-flex justify-content-end pt-3"><button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-light btn-lg" style="border: 2px solid #6F00B4; color: #6F00B4;">Resetar</button>


                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-lg ms-2" style="background-color: #6F00B4; color: white;">Concluir cadastro</button>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- MDB JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.js"></script>
</form>
</body>
</html>
