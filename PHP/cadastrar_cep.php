<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Endereço com Mapa</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(../IMAGENS/login/foto_fundo.png);
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            margin: 0;
            color: #000;
            flex-direction: column;
        }

        h1 {
            font-size: 30px;
            color: #fff;
            margin: 20px 0;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
            padding: 20px;
        }

        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
            width: 400px; 
            margin-right: 20px; 
        }

        h2 {
            text-align: center;
            margin-bottom: 15px;
            color: #6D09A4; 
        }

        .form-group {
            margin-bottom: 10px; 
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold; 
            color: #000; 
        }

        .form-group input {
            width: calc(100% - 16px); 
            padding: 8px; 
            font-size: 14px; 
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: border-color 0.3s;
            color: #000; 
        }

        .form-group input:focus {
            border-color: #6D09A4; 
            outline: none;
        }

        .form-group button {
            width: 100%;
            padding: 8px; 
            background-color: #6D09A4;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px; 
            transition: background-color 0.3s;
            margin-top: 10px; 
        }

        .form-group button:hover {
            background-color: #5c078c; 
        }

        #map {
            height: 400px; 
            width: 400px; 
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5); 
        }
    </style>
</head>
<body>
    <h1>WEB SHOPPE</h1> 
    <div class="container">
        <div class="form-container">
            <h2>Cadastro de Endereço</h2>
            <form id="cep-form" action="adicionar_cep.php" method="post">
                <div class="form-group">
                    <label for="cep">CEP:</label>
                    <input type="text" name="cep" id="cep" placeholder="Digite seu CEP" required>
                </div>
                <div class="form-group">
                    <label for="rua">Rua:</label>
                    <input type="text" id="rua" placeholder="Rua" required>
                </div>
                <div class="form-group">
                    <label for="bairro">Bairro:</label>
                    <input type="text" id="bairro" placeholder="Bairro" required>
                </div>
                <div class="form-group">
                    <label for="numero">Número:</label>
                    <input type="text" id="numero" placeholder="Número" required>
                </div>
                <div class="form-group">
                    <label for="complemento">Complemento:</label>
                    <input type="text" id="complemento" placeholder="Complemento">
                </div>
                <div class="form-group">
                    <label for="cidade">Cidade:</label>
                    <input type="text" id="cidade" placeholder="Cidade" required>
                </div>
                <div class="form-group">
                    <button type="submit">Salvar Endereço</button>
                </div>
            </form>
        </div>
        <div id="map"></div> 
    </div>

    <script>
        const map = L.map('map').setView([-23.55052, -46.633308], 12);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const marker = L.marker([-23.55052, -46.633308]).addTo(map); 

        $(document).ready(function(){
            $('#cep').inputmask('99999-999'); 
        });

        // Função para buscar dados do CEP
        document.getElementById('cep').addEventListener('blur', function () { 
            const cep = this.value.replace(/\D/g, ''); 

            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;

                            const enderecoCompleto = `${data.logradouro}, ${data.bairro}, ${data.localidade}`;

                            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(enderecoCompleto)}`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        const { lat, lon } = data[0];
                                        marker.setLatLng([lat, lon]);
                                        map.setView([lat, lon], 18); 
                                    } else {
                                        alert('Endereço não encontrado no mapa!');
                                    }
                                });
                        } else {
                            alert('CEP não encontrado!');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Erro ao buscar CEP. Tente novamente.');
                    });
            } else {
                alert('Por favor, insira um CEP válido.');
            }
        });
    </script>
</body>
</html>
