<?php
use Elementor\Widget_Base;
use Elementor\Element_Section;
use Elementor\Controls_Manager;

class Slide_Diferente extends Widget_Base{
    /*dados do widget*/
    public function get_name(){
        return "slide_diferente";
    }
    public function get_title(){
        return "Slide Diferente";
    }
    public function get_icon(){
        return " fa fa-carousel";
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
            <div class="slide">
                <div class="items">
                    <?php
                        $secao = new Element_Section(
                            [
                                'id'=>1,
                            ],
                            []
                        );
                        $secao->print_element();
                        
                      
                    ?>
                </div>
                <div class="bolinhas">
                    <a href="#" class="bolinha">
                        •
                    </a>
                </div>
            </div>
        <?php
    }

    /*renderizar no modo de edicao*/
    protected function content_template(){
        ?>
            <div class="slide">
                <div class="items">
                    <?php
                        $secao = new Element_Section(
                            [
                                'id'=>1,
                            ],
                            []
                        );
                        $secao->render_elements_content();

                        
            
                    ?>
                </div>
                <div class="bolinhas">
                    <a href="#" class="bolinha">
                        •
                    </a>
                </div>
            </div>
        <?php
    }
}

?>