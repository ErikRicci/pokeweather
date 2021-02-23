<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>PokéWeather™</title>
    <link rel="stylesheet" href="{{ asset('css/libs.css') }}" />
    <script src="{{ asset('js/libs.js') }}"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        body {
            background: rgb(223, 236, 241);
        }
        .autocomplete {
            position: relative;
            display: inline-block;
        }
        input {
            border: 1px solid transparent;
            background-color: #f1f1f1;
            padding: 10px;
            font-size: 16px;
        }
        input[type=text] {
            background-color: #f1f1f1;
            width: 100%;
        }
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-bottom: none;
            border-top: none;
            z-index: 99;
            /*position the autocomplete items to be the same width as the container:*/
            top: 100%;
            left: 0;
            right: 0;
        }
        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
            background-color: #fff;
            border-bottom: 1px solid #d4d4d4;
        }

        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }
        .autocomplete-active {
            background-color: DodgerBlue !important;
            color: #ffffff;
        }
        .product-holder {
            position: relative;
            top: 0;
            left: 0;
        }
        div.img {
            position: relative;
            width: 50%
        }
        div.img > img{
            width: 100%;
            height: 100%
        }
        div.img > div{
            position: absolute;
            top: 50%;
            margin-top: -75px;
        }

    </style>

</head>

<body>
    <main role="main" class="flex-shrink-0">
        <div class="container">
            <div class="row py-3">
                <div class="col" id="main">
                    <h1><img src="{{ asset('logo.png') }}" alt="" class="img-fluid">™</h1>
                    <h3 class="display-6">Encontre o pokémon perfeito para seu clima!</h3>
                    <div class="row">
                        <div class="col-8">
                            <div class="img">
                                <img class="img-fluid" src="https://images-wixmp-ed30a86b8c4ca887773594c2.wixmp.com/f/12ecb7ae-7059-48df-a4f8-2e3fb7858606/d47rmjf-de88a574-49c8-4dcf-9df4-7e11722e8bec.png?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1cm46YXBwOjdlMGQxODg5ODIyNjQzNzNhNWYwZDQxNWVhMGQyNmUwIiwic3ViIjoidXJuOmFwcDo3ZTBkMTg4OTgyMjY0MzczYTVmMGQ0MTVlYTBkMjZlMCIsImF1ZCI6WyJ1cm46c2VydmljZTpmaWxlLmRvd25sb2FkIl0sIm9iaiI6W1t7InBhdGgiOiIvZi8xMmVjYjdhZS03MDU5LTQ4ZGYtYTRmOC0yZTNmYjc4NTg2MDYvZDQ3cm1qZi1kZTg4YTU3NC00OWM4LTRkY2YtOWRmNC03ZTExNzIyZThiZWMucG5nIn1dXX0.qQtrPbihCWTTF7bQl9cQzUVnPw_yhtVNHAWcDgQV8k4"
                                style="border-radius: 12px"/>
                                <div style="width: 100%">
                                    <img class="img-fluid" src="@isset($poke_data) {{ url($poke_data[2]) }} @endisset"
                                    style="z-index: 2; margin-left: 18%">
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <h5>
                                Procurar uma cidade:
                            </h5>
                            <form autocomplete="off" action="{{ route('city') }}" class="form-group">
                                <input type="hidden" name="old_pokemon" value="@isset($old_pokemon) {{$old_pokemon}} @endisset">
                                <div class="autocomplete" style="width:100%">
                                    <input id="myInput" type="text" required name="city" placeholder="Insira aqui o nome de uma cidade do mundo...">
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    Procurar
                                </button>
                            </form>
                        </div>
                    </div>
                    <hr>
                    {{-- Caso haja dados da cidade, fazemos uma listagem de informações --}}
                    @isset($city_data)
                        <div class="row">
                            <h1>
                                    {{ ucfirst($city_data[2]) }}
                            </h1>
                            <div class="col-sm">
                                <h6> POKÉMON </h6>
                                @isset($poke_data)
                                    {{ ucfirst($poke_data[1]) }}
                                @endisset
                            </div>
                            <div class="col-sm">
                                <h6> TEMPERATURA </h6>
                                    {{ ucfirst($city_data[0]) }} °C
                            </div>
                            <div class="col-sm">
                                <h6> SITUAÇÃO </h6>
                                    {{ ucfirst($city_data[1]) }}
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </main>
    {{-- Footer para aviso --}}
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
          <span class="text-muted">Usuários mobile, sentimos muito, porém nosso site ainda não é responsivo :(</span>
        </div>
    </footer>
</body>

<script>
    //Utilizado o exemplo do site https://www.w3schools.com/
    //Adaptei o autocomplete deles, para usar um arquivo JSON que contém 200 mil cidades
    var countries = @json($cities);

    const LIMIT = 8;

    var map = {
        "â": "a",
        "Â": "A",
        "à": "a",
        "À": "A",
        "á": "a",
        "Á": "A",
        "ã": "a",
        "Ã": "A",
        "ê": "e",
        "Ê": "E",
        "è": "e",
        "È": "E",
        "é": "e",
        "É": "E",
        "î": "i",
        "Î": "I",
        "ì": "i",
        "Ì": "I",
        "í": "i",
        "Í": "I",
        "õ": "o",
        "Õ": "O",
        "ô": "o",
        "Ô": "O",
        "ò": "o",
        "Ò": "O",
        "ó": "o",
        "Ó": "O",
        "ü": "u",
        "Ü": "U",
        "û": "u",
        "Û": "U",
        "ú": "u",
        "Ú": "U",
        "ù": "u",
        "Ù": "U",
        "ç": "c",
        "Ç": "C"
    };

    function autocomplete(inp, arr) {

        //Foco atual
        var currentFocus;

        //Função ao digitar
        inp.addEventListener("input", function(e) {
            var a, b, i, val = this.value;

            //Fechar tudo
            closeAllLists();
            if (!val) {
                return false;
            }
            currentFocus = -1;

            //Criação da DIV para conter os resultados
            a = document.createElement("DIV");
            a.setAttribute("id", this.id + "autocomplete-list");
            a.setAttribute("class", "autocomplete-items");

            //Unir a div com o AUTOCOMPLETE
            this.parentNode.appendChild(a);

            //Contagem para total de resultados
            var count = 0;

            /*for each item in the array...*/
            for (i = 0; i < arr.length; i++) {

                //Checamos não só se o começo é igual, mas também se estamos abaixo do LIMIT
                if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase() && count < LIMIT) {

                    //Variável para limitar o tanto de itens que aparecerão ao começar a digitar
                    //Tive que colocar isso pois eram resultados de mais
                    count++;

                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");

                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);

                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";

                    /*execute a function when someone clicks on the item value (DIV element):*/
                    b.addEventListener("click", function(e) {
                        //Adicionamos o texto do autocomplete
                        inp.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                    });
                    a.appendChild(b);
                }
            }
        });
        //Ação ao apertar uma tecla
        inp.addEventListener("keydown", function(e) {
            var x = document.getElementById(this.id + "autocomplete-list");
            if (x) x = x.getElementsByTagName("div");
            if (e.keyCode == 40) {
                //Esperar para tecla de CIMA pra mudar o escolhido
                currentFocus++;

                //Deixar o item ativo "mais ativo"
                addActive(x);
            } else if (e.keyCode == 38) {
                //Esperar para tecla de BAIXO pra mudar o escolhido
                currentFocus--;

                //Deixar o item ativo "mais ativo"
                addActive(x);
            } else if (e.keyCode == 13) {
                //Se apertar ENTER, prevenir o SUBMIT
                e.preventDefault();

                if (currentFocus > -1) {
                    //Simulado o click no focado
                    if (x) x[currentFocus].click();
                }
            }
        });

        function addActive(x) {
            //Classificando o ATIVO
            if (!x) return false;

            //Começa removendo o ATIVO
            removeActive(x);

            if (currentFocus >= x.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = (x.length - 1);

            //Classe de autocomplete ativo
            x[currentFocus].classList.add("autocomplete-active");
        }

        function removeActive(x) {
            //Remover active de tudo que não foi escolhido
            for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
            }
        }

        function closeAllLists(elmnt) {
            //Excluir o não-utilizado
            var x = document.getElementsByClassName("autocomplete-items");

            for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                    x[i].parentNode.removeChild(x[i]);
                }
            }
        }

        //Função que formata uma string removendo todos os acentos e colocando
        //tudo em minúscula
        function removerAcentos(s) {
            return s.replace(/[\W\[\] ]/g, function(a) {
                return map[a] || a
            })
        };

        //Espera o click do mouse
        document.addEventListener("click", function(e) {
            closeAllLists(e.target);
        });
    }

    //Executa a função
    autocomplete(document.getElementById("myInput"), countries);

</script>

</html>
