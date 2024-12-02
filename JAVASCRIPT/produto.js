    function mostrarDescricao() {
        // Esconde a descrição cortada
        document.getElementById('descricao').style.display = 'none';
        // Mostra a descrição completa
        document.getElementById('descricaoCompleta').style.display = 'block';
        // Esconde o botão
        document.getElementById('verMaisBtn').style.display = 'none';
    }

function setRating(rating) {
    const estrelas = document.querySelectorAll('.estrela-img');
        document.getElementById('nota').value = rating;

        estrelas.forEach((estrela, index) => {
            if (index < rating) {
                estrela.src = '../IMAGENS/home/estrela-cheia.png'; // Estrela cheia
            } else {
                estrela.src = '../IMAGENS/home/estrela-vazia.png'; // Estrela vazia
            }
        });
    }

