<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Link_Diferente extends Widget_Base{
    /*identificaçoes widget*/
    public function get_name()
    {
        return 'link_diferente';
    }
    public function get_title()
    {
        return 'Link diferente';
    }
    public function get_icon()
    {
        return 'fa fa-link';
    }
    public function get_categories(){
        return ['basic'];
    }
    /*registrar controles*/
    protected function register_controls()
    {
        $this->start_controls_section(
        'content',
        [
            'label'=> esc_html__( 'Content', 'plugin-name' ),
            'tab'=> \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );
            
            
            $this->add_control('url',
            [
                'label' => 'Url',
                'type'=> \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'plugin-name' ),
                'default' => [
                    'url' => ' ',
                    'is_external' => true,
                    'nofollow' => true,
                    'custom_attributes' => '',
                ],
                'label_block' => true,
            ]);

            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(),
                [
                    'name'=>'conteudo',
                    'selector'=>'{{WRAPPER}} .link_diferente',
                    
                ]
            );

            $this->add_responsive_control(
                'hover',
                [
                    'label'=>'Aparecer',
                    'type'=>\Elementor\Controls_Manager::SELECT,
                    'options'=>[
                        'block'=>'sempre',
                        'none'=>'só quando passar mouse',
                    ],
                    'default'=>'block',
                    'selectors'=>[
                        '{{WRAPPER}} .link_diferente>p'=>'display:{{VALUE}};',
                        '{{WRAPPER}} .link_diferente:hover>p'=>'display:block;',
                    ],
                    'devices' => [ 'desktop', 'tablet','mobile' ],
                ]
            );

            $this->add_control(
                'cor',
                [
                    'label'=>'cor',
                    'type'=>\Elementor\Controls_Manager::COLOR,
                    'selectors'=>[
                        '{{WRAPPER}} .link_diferente'=>'color:{{VALUE}}',
                    ],
                ]
            );

           
            $this->add_control(
                'escrito',
                [
                    'label'=>'Nome',
                    'type'=>\Elementor\Controls_Manager::TEXT,
                    'default'=>'Link Diferente',
                ]
            );
            $this->add_control(
                'height',
                [
                    'label'=>'Altura',
                    'type'=>\Elementor\Controls_Manager::SLIDER,
                    'size_units'=>['px','vh','%'],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'selectors'=>[
                        '{{WRAPPER}} .link_diferente'=>'height:{{SIZE}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_control(
                'width',
                [
                    'label'=>'Largura',
                    'type'=> \Elementor\Controls_Manager::SLIDER,
                    'size_units'=> ['px', 'vw', '%'],
                    'selectors'=>[
                        '{{WRAPPER}} .link_diferente'=>'width:{{SIZE}}{{UNIT}}',
                    ]
                ]
            );
        $this->end_controls_section();
    }
    /*no frontend*/
    protected function render()
    {
        $configuracoes = $this->get_settings_for_display();
        if ( ! empty( $configuracoes['url']['url'] ) ) {
			$this->add_link_attributes( 'url', $configuracoes['url'] );
		}
		
        ?>
       <style>
           .link_diferente{
               display:flex;
               align-items:center;
               justify-content:center;
           }
        </style>
        <a class="link_diferente"  href="<?php echo $configuracoes['url']['url']?>">
            <p>
                <?php echo($configuracoes['escrito'])?>
            </p>
        </a>
    <?php
    }

    /*preview editor*/
    protected function content_template()
    {
        ?>
            <style>
                .link_diferente{
                    display:flex;
                    align-items:center;
                    justify-content:center;
                }
            </style>
            <a class="link_diferente" href={{settings.url.url}}>
               <p>{{{settings.escrito}}}</p>
            </a>
        <?php
    }
}   

?>