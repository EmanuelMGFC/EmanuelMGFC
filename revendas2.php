<?php

use Elementor\Content_Manager;
use Elementor\Widget_Base;

class Revendas2 extends Widget_Base{
    public function get_name()
    {
        return 'revenda2';
    }
    public function get_title(){
        return 'Revendas 2';
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
    protected function render()
    {
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
                    $filter=array(
                        "q"=>FILTER_SANITIZE_STRIPPED,
                        "todos"=>FILTER_VALIDATE_BOOLEAN,
                        "linha_automotiva"=>FILTER_VALIDATE_BOOLEAN,
                        "linha_hdm"=>FILTER_VALIDATE_BOOLEAN,
                        "linha_diesel"=>FILTER_VALIDATE_BOOLEAN,
                        
                    );
                    $data=filter_input_array(INPUT_POST, $filter);
                    $linhas=array(
                        "linha_automotiva",
                        "linha_hdm",
                        "linha_diesel",
                    );
                    if (is_null($data)) {
                        echo(" ");
                    }else{

                        $nao_marcou_nenhuma;
                        if(is_null($data["todos"])){
                            foreach($linhas as $linha){
                                if(is_null($data[$linha])){
                                    $data["todos"]=true;
                                }else{
                                    $data["todos"]=false;
                                    break;
                                }
                            }
                        }
                        if($data["todos"]){
                            foreach($linhas as $linha){
                                $data[$linha]=true;
                            }
                        }
                        /*criar url*/
    
                        $url_com_linhas_selecionadas="http://localhost:1337/api/revendas?";
                        $contador_de_linhas =0;
                        foreach($linhas as $linha){
                            if($data[$linha]){
                                $url_com_linhas_selecionadas .= "filters[$"."or][".$contador_de_linhas."][".$linha."][$"."eq]=".$data[$linha];
                                $url_com_linhas_selecionadas .= "&";
                                $contador_de_linhas=$contador_de_linhas+1;
                            }
                        }
                        substr($url_com_linhas_selecionadas, 0, -1);
    
                        /*add estado ou estado*/
                        $url_com_linhas_selecionadas_e_local;
                        /*verificar se tem algum dado em q*/
                        if($data["q"]==""){
                            $url_com_linhas_selecionadas_e_local=$url_com_linhas_selecionadas;
                        }else{
                            $url_com_linhas_selecionadas_e_local = $url_com_linhas_selecionadas."&filters[$"."or][0][estado][$"."eq]=".$data["q"]."&filters[$"."or][1][cidade][$"."eq]=".$data["q"];
                        }
                        
    
                        $resposta =wp_remote_get($url_com_linhas_selecionadas_e_local);
                        $corpo=$resposta['body'];
                        $corpo_em_php= json_decode($corpo);
                        $data_do_corpo=$corpo_em_php->data;
            
                        $quantidade_de_respostas= sizeof($data_do_corpo);
                        if($quantidade_de_respostas==0){
                            echo("sem resultados 😔");
                        }else{
                            foreach($data_do_corpo as $valor){
                                echo($valor->attributes->nome_fantasia);
                                echo($valor->attributes->linha_automotiva?"AUTOMOTIVA":"");
                                echo($valor->attributes->linha_diesel?"diesel":"");
                                echo($valor->attributes->linha_hdm?"hdm":"");
                                echo($valor->attributes->endereco);
                                echo(",");
                                echo($valor->attributes->numero);
                                echo($valor->attributes->CEP);
                                echo($valor->attributes->cidade);
                                echo($valor->attributes->estado);
                                echo($valor->attributes->numero_de_telefone);
                                echo("<br>");
                            }
                        }
                    }
                ?>
            </section>
        <?php
    }

}

?>