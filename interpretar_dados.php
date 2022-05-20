<?php
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;


class Interpretar_Dados extends Widget_Base{
    public function get_name()
    {
        return 'interpretar_dados_login';
    }
    public function get_title()
    {
        return 'Interpretar dados Login';
    }
    public function get_icon(){
        return 'fa fa-data';
    }
    
    protected function register_controls(){
        $this->start_controls_section(
            'conteudo',
                [
                    'label'=>'conteudo',
                    'tab'=> Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );
        
       $this->end_controls_section();
    }
    public function render()
    {

        session_start();
        
       
        
        $cnpj_chegou=base64_decode($_SESSION["cnpj"]);
        $token='56b910bc820c209c86574cb4425db223e330717c8a6a6a1aed75e84983178dd97ef9b122c3744fd2df44bf4b780232e87591977149605cb44e0d6a2de12f405ee53c23f497fe6da14edba7d1f19933a6a9c583dcac0faab955792804c6a926070639b3b1f3fca6d90a1983f41a7190524a37168ca964d039b889104a20cfbbbd';
        $argumentos = array(
            'method'=> 'GET',
            'headers'=>array(
                'authorization'=> 'bearer '.$token,
                'Content-Type' => 'application/json; charset=utf-8',
            )
        );
        $api_em_json = wp_remote_get('http://localhost:1337/api/csas?filters[cnpj][$eq]='.$cnpj_chegou, $argumentos);
        $corpo_do_json = $api_em_json['body'];
        $corpo_json_php= json_decode($corpo_do_json);
        $data= $corpo_json_php->data;

        
        if (!password_verify(serialize($data[0]),$_SESSION["data"])) {
            header("Location: /wordpress");
            die();
        }
       
    }
    public function content_template()
    {
        
    }

}
?>