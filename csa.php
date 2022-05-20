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
    
        $this->add_control(
            'color-state',
            [
                'label' => 'Cor do Brasil',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' =>[
                    '{{WRAPPER}} .brasil path'=>'fill: {{VALUE}}',
                ],
            ]   
        );

        $this->add_control(
            'color-state_hover',
            [
                'label' => 'Cor do Brasil<br>quando passar<br>o mouse',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' =>[
                    '{{WRAPPER}} .brasil path:hover '=>'fill: {{VALUE}}',
                ],
            ]   
        );
                
        /*finaliza a seção de controles conteudo*/
        $this->end_controls_section();
        
    }
    /*mostrar widget para o usuario final*/
    protected function render()
    {
        $token='56b910bc820c209c86574cb4425db223e330717c8a6a6a1aed75e84983178dd97ef9b122c3744fd2df44bf4b780232e87591977149605cb44e0d6a2de12f405ee53c23f497fe6da14edba7d1f19933a6a9c583dcac0faab955792804c6a926070639b3b1f3fca6d90a1983f41a7190524a37168ca964d039b889104a20cfbbbd';
        $argumentos = array(
            'method'=> 'GET',
            'headers'=>array(
                'authorization'=> 'bearer '.$token,
                'Content-Type' => 'application/json; charset=utf-8',
            )
        );    
        $api_em_json=wp_remote_get("http://localhost:1337/api/csas", $argumentos);
        $corpo_da_resposta=$api_em_json['body'];
        $api_em_php=json_decode($corpo_da_resposta);
        
        $estados_json_csa = file_get_contents(__DIR__.'\scripts_dependecies\json\estados_csa.json');
        $estados_php_csa = json_decode($estados_json_csa);
        $estados_brasil= $estados_php_csa->estados;

        
        function montar_card_csa($obj){
           
            $tamanho=sizeof($obj->data)-1;
            for($i=0;$i<=$tamanho;$i++){
                ?>
                    
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

        function get_api_to_php(String $url, $argumentos)
        {
            
            $api_em_json=wp_remote_get($url, $argumentos);
            $corpo_da_resposta=$api_em_json['body'];
            $api_em_php=json_decode($corpo_da_resposta);
            return $api_em_php;
        }
        function clicou($estado_recebido, $argumentos){
            $url_estado_filtrado="http://localhost:1337/api/csas?filters[estado][$"."eq]="."$estado_recebido";
            return get_api_to_php($url_estado_filtrado, $argumentos);
        }
        
        

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

                

                        @media (min-width:800px){
                            .brasil{
                                width:700px;
                            }
                        }
                    </style>
                <div class="brasil">
                    
                    <svg
                    
                    xmlns:mapsvg="http://mapsvg.com"
                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                    xmlns:svg="http://www.w3.org/2000/svg"
                    xmlns="http://www.w3.org/2000/svg"
                    
                    mapsvg:geoViewBox="-74.008595 5.275696 -34.789914 -33.743888"
                    
                    viewBox="0 0 612.51611 639.04297"
                    preverveAspectRatio="xMidYMid meet"
                    >
                    <?php
                        foreach ($estados_brasil as $cont) {
                            ?>
                                <a class="estado" href="<?php echo("?estado=".$cont->name."#csa");?>">
                                    <path d="<?php echo($cont->d)?>" title=<?php echo($cont->name)?> id=<?php echo($cont->id)?> />
                                    <title><?php echo($cont->name)?></title>
                          
                                </a>
                            <?php
                        }
    
                    ?>
                   
                    </svg>
                    
                </div>
            <section id="csa">            
                <?php
                    if(isset($_GET['estado'])){
                        $filtrado = clicou($_GET['estado'], $argumentos);
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