<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Form_Contact extends Widget_Base{
    public function get_name(){
        return 'form_contact';
    }
    public function get_title(){
        return 'Formulario contato';
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
        ?>
            <form action="#" method="post">
                <fieldset>
                    <legend id="legenda_form_contato">Para mais informações 📧</legend>
                    <input type="text" placeholder="Nome" minlength="2" required>
                    <input type="email" placeholder="Email">
                    <input type="text" placeholder="Empresa" required>
                    <input type="text" placeholder="Telefone">
                    <input type="text" placeholder="Cidade" required>
                    <select name="estado" id="estado">
                        <option value=" ">UF</option>
                        <?php
                            $estados_json_form = file_get_contents(__DIR__.'\scripts_dependecies\json\estados_csa.json');
                            $estados_php_form = json_decode($estados_json_form);
                            $estados_brasil_form= $estados_php_form->estados;
                            foreach($estados_brasil_form as $cont){
                                ?>
                                    <option value=<?php echo($cont->name)?>><?php echo($cont->name)?></option>
                                <?php
                            }
                        ?>
                    </select>
                    <input type="text" id="assunto" placeholder="Assunto" required>
                    <textarea name="mensagem" placeholder="Mensagem" id="" cols="30" rows="10"></textarea>
                    <input type="submit" value="Enviar">
                    <input type="reset" value="Limpar">
                </fieldset>
            
            </form>
        <?php
       
    }
    /*preview editor*/
    protected function content_template()
    {
        ?>
            
        <?php
    }
}

?>