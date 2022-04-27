<?php
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;


class Whatsapp extends Widget_Base{
    public function get_name()
    {
        return 'whatsapp';
    }
    public function get_title()
    {
        return 'whatsapp diferente';
    }
    public function get_icon(){
        return 'fa fa-whatsapp';
    }
    
    protected function register_controls(){
        $this->start_controls_section(
            'conteudo',
            [
                'label'=>'conteudo',
                'tab'=> Elementor\Controls_Manager::TAB_CONTENT,
            ]
            );
        $this->add_control(
            'posicao',
            [
                'label'=>'Posição referente a tela',
                'type'=> Elementor\Controls_Manager::SELECT,
                'options'=>[
                    'fixed'=>'fixo',
                    'static'=>'estatico'
                ],
                'default'=>'static',
                'selectors'=>[
                    '{{WRAPPER}} .whatsapp'=>'position:{{VALUE}};',
                ]
            ]
        );
        
        $this->add_control(
            'posicao_na_tela',
			[
				'label' => 'posicao',
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .whatsapp' => 'top: {{TOP}}{{UNIT}}; right:{{RIGHT}}{{UNIT}}; bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
			]
        );

        $this->add_control(
            'arrendondamento',
			[
				'label' => 'arredondamento',
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'border-radius: {{VALUE}}{{UNIT}};',
				],
			]
        );
        
       $this->end_controls_section();
    }
    public function content_template()
    {
        ?>

            <div class="whatsapp">
                <div class="card">
                    <p>
                        Conversar pelo WhatsApp
                    </p>
                    <p>
                   
                    </p>
                    <div class="box_contato">
                      
                    </div>
                   
                </div>
                <button class="icon">
                    ☼☼►◄
                </button>
            </div>
        <?php
    }
    public function render()
    {
        $settings =$this->get_settings_for_display();
        ?>
            <div class="whatsapp">
                
                <div class="card">
                    <p>
                        Conversar pelo WhatsApp
                    </p>
                    <p>
                    
                    </p>
                    <div class="box_contato">
                      
                    </div>
                </div>
                <button class="icon ">
                    [
                </button>
            </div>
        <?php
    }

}
?>