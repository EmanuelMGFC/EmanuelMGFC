<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/*
a classe Revendas deriva da classe abstrata Widget_Base

Na tela cria a seção revendas,
que terá:
    -filtros;
    -buscador;
    -mapa do google mostrando a localização das revendas;
*/
class Revendas extends Widget_Base{
    /*identificaçoes widget*/

    /*indentificação do widget*/
    public function get_name()
    {
        return 'revendas';
    }

    /*titulo que aparecerá no editor*/
    public function get_title()
    {
        return 'Revendas';
    }

    /*icone do widget*/
    public function get_icon()
    {
        return "fa fa-sale";
    }

    /*em que categoria no editor vai estar*/
    public function get_categories(){
        return ['basic'];
    }

    /*registrar controles*/
    protected function register_controls()
    {
        /*iniciar uma seção de controles do tipo conteudo*/
        $this->start_controls_section(
        'content',
        [
            'label'=> esc_html__( 'Content', 'plugin-name' ),
            'tab'=> \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );
            
            
        /*finaliza a seção de controles conteudo*/
        $this->end_controls_section();
    }

   

   
    /*mostrar widget para o usuario final*/
    protected function render()
    {
        /*pegar dados da coleção revendas da api feita pelo strapi*/
        $response = wp_remote_get("http://localhost:1337/api/revendas");
        /*pegar conteudo da resposta http*/
        $body=$response['body'];
        /*tranformar o json em php*/
        $data=json_decode($body);
        /*pegar os dados da lista de objetos*/
        $api=$data->data;
        /*pegar a quantidade de objetos*/
        $quantidade_de_items=sizeof($api);

        /*html da pagina*/
        ?>
            <!--formulario de busca, ele usa o metodo post e a ação retorna os dados para a mesma pagina-->
            <form action="#revendas" method="post" id="formulario" name="formulario">
                <label for="linha_automotiva">Linha Automotiva</label>
                <input type="checkbox" name="linha_automotiva" id="linha_automotiva">

                <label for="linha_hdm">Linha HDM</label>
                <input type="checkbox" name="linha_hdm" id="linha_hdm">

                <label for="linha_diesel">Linha diesel</label>
                <input type="checkbox" name="linha_diesel" id="linha_diesel">

                <label for="todos">Todos</label>
                <input type="checkbox" name="todos" id="todos">
                <br>
                <label for="q">Localidade</label>
                <input type="search" placeholder="digite uma cidade ou estado..." name="q" id="q">

                <button type="submit"  name="buscar" id="buscar">Buscar</button>
            </form>
            <!--estilos -->
            <style>
                #revendas{
                    display:flex;
                    flex-wrap: wrap;
                    gap: 1em;
                }
                .card{
                    border: solid 1px grey;
                    padding:5px;
                }
                .card>#linha>span~*::before{
                    content:"|";
                }
            </style>
            <!-- seção que mostra os resutados da pesquisa-->
            <section id="revendas">
                <?php
                    /*variaveis de entrada*/
                    /*filtros pega todos os inputs post*/
                    $filtros=filter_input_array(INPUT_POST, FILTER_DEFAULT);

                    if (isset($_POST["buscar"])) {
                        $erros=array();
                        /*sanitize*/
                        $busca = filter_input(INPUT_POST, 'q', FILTER_SANITIZE_SPECIAL_CHARS);
                        
                        
                    }

                    /*declara que a busca tem haver com o objeto*/
                    $tem_haver=true;
                    /*variaveis de saida*/
                    $nome;
                    $linha;
                    $endereco;
                    $cep;
                    $cidade_e_estado;
                    $telefone;
                    /*processamento*/

                    /*
                    a função montar_card pega o objeto
                    que tem haver com a pesquisa e mostra na tela os dados
                    */
                    function montar_card($objeto)
                    {
                        
                        /*pegar dados do cep do objeto que tem haver*/
                        $response_cep=wp_remote_get("https://ws.apicep.com/cep/$objeto->CEP.json");
                      
                        /*pegar só o conteudo da resposta*/
                        $body_cep=$response_cep['body'];
                        /*transformar o json em php*/
                        $data_cep=json_decode($body_cep);
                        
                        ?>
                        <section class="card">
                            <strong>
                                <?php echo($objeto->nome_fantasia);?>
                            </strong>
                            <p id="linha">
                               <?php
                                   if($objeto->linha_automotiva):
                                       echo("<span>Automotiva</span>");
                                   endif;
                                   if($objeto->linha_diesel):
                                       echo("<span>Diesel</span>");
                                   endif;
                                   if($objeto->linha_hdm):
                                       echo("<span>HDM</span>");
                                   endif;
                               ?>
                            </p>
                            <p>
                                <?php
                                    if (isset($objeto->endereco)) {
                                        echo($objeto->endereco);
                                    }elseif(isset($data_cep->address)){
                                        echo($data_cep->address);
                                    }else{
                                        echo("");
                                    }
                                    if (isset($objeto->numero)) {
                                        echo(",".$objeto->numero);
                                    }else{
                                        echo("");
                                    }
                                ?>
                            </p>
                            <p>
                                <?php
                                    if(isset($objeto->bairro)){
                                        echo($objeto->bairro);
                                    }elseif(isset($data_cep->district)){
                                        echo($data_cep->district);
                                    }else{
                                        echo("");
                                    }
                                ?>
                            </p>
                            <p>
                                <?php
                                   
                                   echo($objeto->CEP);
                                   
                                ?>
                            </p>
                            <p>
                               <?php
                                    
                                    if (isset($objeto->cidade)) {
                                        echo($objeto->cidade);
                                    }elseif(isset($data_cep->city)){
                                        echo($data_cep->city);
                                    }else{
                                        echo("");
                                    }
                                
                                    if (isset($objeto->estado)) {
                                        echo("-".$objeto->estado);
                                    }elseif(isset($data_cep->state)){
                                        echo("-".$data_cep->state);
                                    }else{
                                        echo("");
                                    }
                                   
                               ?>
                            </p>
                            
                            <p>
                                <?php
                                    if(isset($objeto->numero_de_telefone)){
                                        echo($objeto->numero_de_telefone);
                                    }else{
                                        echo("");
                                    }
                                ?>
                            </p>

                        </section>
                       <?php
                    }
                   
                    for ($i=0; $i < $quantidade_de_items; $i++) { 
                        /*pega os atributos do objeto*/
                        $obj=$api[$i]->attributes;

                        if (empty($filtros["q"])) {
                            /*se está vazio retorna vazio*/
                            $tem_haver_pesquisa=" ";
                           
                        }else{
                            /*
                            torna a pesquisa em uma expressão regular
                            e compara com o nome fantasia da revenda,
                            e retornar um valor verdadeiro ou falso
                            */
                            $tem_haver_pesquisa=preg_match("/".$filtros["q"]."/i",$obj->nome_fantasia); 
                        }

                        if($tem_haver_pesquisa){
                            if (isset($filtros["linha_hdm"])&& $obj->linha_hdm){
                                montar_card($obj);
                            }elseif (isset($filtros["linha_diesel"])&& $obj->linha_diesel){
                                montar_card($obj);
                            }elseif (isset($filtros["linha_automotiva"])&& $obj->linha_automotiva){
                                montar_card($obj);
                            }elseif(isset($filtros["todos"])){
                                montar_card($obj);
                            }else{
                               continue;
                            }
                        }
                    }
                ?>
            </section>

        <?php
        
        
    }

    /*preview editor*/
    protected function content_template()
    {
        $response = wp_remote_get("http://localhost:1337/api/revendas");
        $body=$response['body'];
        $data=json_decode($body);
        $api=$data->data;
        $quantidade_de_items=sizeof($api);

        ?>
            <form action="#revendas" method="post" id="formulario" name="formulario" >
                <label for="linha_automotiva">Linha Automotiva</label>
                <input type="checkbox" name="linha_automotiva" id="linha_automotiva">

                <label for="linha_hdm">Linha HDM</label>
                <input type="checkbox" name="linha_hdm" id="linha_hdm">

                <label for="linha_diesel">Linha diesel</label>
                <input type="checkbox" name="linha_diesel" id="linha_diesel">

                <label for="todos">Todos</label>
                <input type="checkbox" name="todos" id="todos">

                <label for="q">Localidade</label>
                <input type="search" placeholder="digite uma cidade ou estado..." name="q" id="q">

                <button type="submit"  name="buscar" id="buscar">Buscar</button>
            </form>

            <section id="revendas">
                <?php
                
                   /*variaveis de entrada*/
                    /*filtros*/
                    $filtros=filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
                    $tem_haver=true;
                            
                   /*variaveis de saida*/
                   $nome;
                   $linha;
                   $endereco;
                   $cep;
                   $cidade_e_estado;
                   $telefone;
                   /*processamento*/
                   
                   for ($i=0; $i < $quantidade_de_items; $i++) { 
                    $obj=$api[$i]->attributes;
                    ?>
                     <section class="card">
                         <strong>
                             <?php echo($obj->nome_fantasia);?>
                         </strong>
                         <p>
                            <?php
                                echo("linha")
                                /* echo($obj->linha); */
                            ?>
                         </p>
                         <p>
                             <?php
                                echo("endereco");
                                /* echo($obj->endereco); */
                             ?>
                         </p>
                         <p>
                             <?php
                                echo("cep");
                                /*  echo($obj->cep); */
                             ?>
                         </p>
                         <p>
                            <?php
                                echo("cidade-estado");
                                /* echo($obj->cidade_e_estado); */
                            ?>
                         </p>
                         <p>
                             <?php
                                echo("telfone");
                                /* echo($obj->telefone); */
                             ?>
                         </p>

                     </section>
                    <?php
                }
                 
                ?>
            </section>
        <?php
    }
}   

?>