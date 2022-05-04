<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

/*
    a classe Csa deriva da classe Widget_Base;

    Esse widget serve para mostra todos os centros especializados da MGFC ao selecionar o estado no 
    mapa;
*/

class Csa extends Widget_Base{
    public function get_name(){
        return 'csa';
    }
    public function get_title(){
        return 'CSA';
    }
    public function get_icon(Type $var = null)
    {
        return 'fa fa-gear';
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

        $api_em_json=wp_remote_get("http://localhost:1337/api/csas");
        $corpo_da_resposta=$api_em_json['body'];
        $api_em_php=json_decode($corpo_da_resposta);
        
        $estados=array(
            "AC",
            "AL",
            "AP",
            "AM",
            "BA",
            "CE",
            "DF",
            "ES",
            'GO',
            "MA",
            "MT",
            "MS",
            "MG",
            "PA",
            "PB",
            "PR",
            "PE",
            "PI",
            "RJ",
            "RN",
            "RS",
            "RO",
            "RR",
            "SC",
            "SP",
            "SE",
            "TO"
        );
        
        function montar_card_csa($obj){
           
            $tamanho=sizeof($obj->data)-1;
            for($i=0;$i<=$tamanho;$i++){
                ?>
                    <style>
                        #csa{
                            width:100%;
                            block-size: fit-content;
                        }
                        
                        dl.csas_attributes{
                            border:solid 1px black;
                            display:inline-block;
                            margin: auto;
                        }
                    </style>
                    <dl class="csas_attributes">
                        <dd><?php echo($obj->data[$i]->attributes->tipo);?></dd>
                        <dd><?php echo($obj->data[$i]->attributes->nome);?></dd>
                        <dd><?php echo($obj->data[$i]->attributes->cidade);?></dd>
                        <dd><?php echo($obj->data[$i]->attributes->estado);?></dd>
                        <dd><?php echo($obj->data[$i]->attributes->telefone);?></dd>
                        <dd><?php echo($obj->data[$i]->attributes->site);?></dd>
                    </dl>
                <?php
            }
            
        }

        function get_api_to_php(String $url)
        {
            $api_em_json=wp_remote_get($url);
            $corpo_da_resposta=$api_em_json['body'];
            $api_em_php=json_decode($corpo_da_resposta);
            return $api_em_php;
        }
        function clicou($estado_recebido){
            $url_estado_filtrado="http://localhost:1337/api/csas?filters[estado][$"."eq]="."$estado_recebido";
            return get_api_to_php($url_estado_filtrado);
        }
        
        

        ?>
            <section id="csa">
                <?php
                    ?>
                        <div class="brasil">
                            <?php
                                foreach($estados as $estado){
                                    echo('<a class="estado"  style="background: black;" href="?estado='.$estado.'#csa"> ');
                                    echo("$estado");
                                    echo(' </a>');
                                }
                            ?>
                        </div>
                    <?php
                    if(isset($_GET['estado'])){
                        $filtrado = clicou($_GET['estado']);
                        montar_card_csa($filtrado);
                   }
                ?>
            </section>
       <?php
       
    }
    /*preview editor*/
    protected function content_template()
    {
        $api_em_json=wp_remote_get("http://localhost:1337/api/csas");
        $corpo_da_resposta=$api_em_json['body'];
        $api_em_php=json_decode($corpo_da_resposta);
        
        var_dump($api_em_php);
    }
}

?>