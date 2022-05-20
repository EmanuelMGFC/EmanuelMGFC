<?php
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;


class Formulario extends Widget_Base{
    public function get_name()
    {
        return 'formulario';
    }
    public function get_title()
    {
        return 'Formulario';
    }
    public function get_icon(){
        return 'fa fa-form';
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
            'formulario',
            [
                'label' => esc_html__( 'Formulario', 'plugin-name' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name'=>"legend",
                        'label'=>"Legenda Formulario",
                        'type'=> \Elementor\Controls_Manager::TEXT,
                    ],
                    [
                        'name' => 'tipo_input',
                        'label' => "Tipo do campo",
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'options' => [
                            'text'  => esc_html__( 'Texto', 'plugin-name' ),
                            'file' => esc_html__( 'Arquivo', 'plugin-name' ),
                            'submit' => esc_html__( 'Enviar', 'plugin-name' ),
                            
                        ],
                    ],
                    [
                        'name'=>"dica",
                        'label'=>"Dica",
                        'type'=> \Elementor\Controls_Manager::TEXT,

                    ],
                    [
                        'name'=>"valor",
                        'label'=>"valor",
                        'type'=> \Elementor\Controls_Manager::TEXT,

                    ],
                ],
              
            ]
        );
       $this->end_controls_section();
    }
    public function render()
    {

       

        $settings = $this->get_settings_for_display();
		?>
		<form>
		<?php foreach ( $settings['formulario'] as $index => $item ) : ?>
            <?php
                if (isset($item['legend'])) {
                    echo("<legend>".$item['legend']."</legend>");
                }
                if (isset($item['tipo_input'])) {
                    echo("<input >");
                }
            ?>
			
		<?php endforeach; ?>
		</form>
		<?php
        
    }
    public function content_template()
    {
        ?>
            <form>
                <#
                    if ( settings.formulario ) {
                        _.each( settings.formulario, function( item, index ) {
                        #>
                            <input value="{{{item.valor}}}" placeholder="{{{item.dica}}}" type="{{{item.tipo_input}}}">
                        <#
                        } );
                    }
                #>
            </form>
		<?php
    }

}
?>