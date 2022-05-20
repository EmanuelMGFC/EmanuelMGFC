<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Sair extends Widget_Base{
    /*dados do widget*/
    public function get_name(){
        return "exit";
    }
    public function get_title(){
        return "Botão de sair";
    }
    public function get_icon(){
        return " fa fa-exit";
    }
    public function get_categories(){
        return ['basic'];
    }
    /*controles do widget*/
    protected function register_controls(){
        $this->start_controls_section(
            'conteudo',
            [
                'label'=> "conteudo",
                'tab'=> Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->end_controls_section();
    }
    /*renderizar pro usuario final*/
    protected function render(){
        ?>  
            <form action="" method="post">
                <input type="submit" name="sair" value="sair">
            </form>
        <?php
        if (isset($_POST["sair"])) {
            session_start();
            session_unset();
            session_destroy();
        }
    }

    /*renderizar no modo de edicao*/
    protected function content_template(){
        
    }
}

?>