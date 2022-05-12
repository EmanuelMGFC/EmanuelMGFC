<?php

/*
    a classe Login deriva da classe WP_Widget, que é a classe base do wordpress de widgets
*/
class Login extends WP_Widget {
    /*função publica construtora*/
    public function __construct() {
        /*
            $id_base é opcional. Indentificador base para o widget,
            deve ser em letra minuscula e unico. Se deixado vazio, uma porção de nomes de classe PHP do widget serão usadas.
            Tem que ser unico.

            $name         Nome para o widget exibido na pagina de configuração.

            $widgets_ops ou só $widget_options(opções do widget)
            é  opcional. Opções do widget. Veja wp_register_sidebar_widget()
            para informações para quais são os argumentos que são aceitos. por padrão é um array vazio.

            $control_options(opções de controle)  é opcional. Opções de controles do widget.
            Veja wp_register_widget_control() para informações
            para quais são os argumentos que são aceitos. por padrão é um array vazio.
        */
        $id_base = 'login';
        $name ='Login'; 
        
        /*
            parent
            Faz referência a classe pai(Wp_Widget) que foi derivada pela classe atual(Login).
            Basicamente foi criado para que você não precise,
            dentro da classe filha, ficar explicitando qual é a classe pai
            de onde será chamado o método, acessar o atributo, constante, etc.
        */
        /*chama a função construtora da classe WP_Widget*/
		parent::__construct($id_base,$name);
	}

    /*
        Método widget()

        A função que irá determinar o conteúdo do widget. Provavelmente, é a função mais importante.
        essa função é abstrata ou seja tem que ser compativel ao metodo widget da classe WP_Widget,
        dessa forma tendo que passar os parametros:
            $args(array) Exibe os argumentos incluindo before_title, after_title, before_widget e after_widget.
            $instance(array) As configurações para a instância específica do widget
             
    */

    public function widget($args, $instance){
       ?>
            <button onClick="pegar_cnpj()">a</button>
            <style>
                .login_formulario_csa{
                    position: fixed;
                    display:none;
                    box-shadow: 1,0,0,1 rgba(0,0,0,1);
                    top:50vh;
                    bottom:50vh;
                    left: 50vh;
                    right:50vh;
                }
                .login_formulario_csa>fieldset{
                    background-color:white;
                }
                .login_formulario_csa>fieldset>button.fechar{
                    position:absolute;
                    top:0;
                    right:0;
                    background-color:white;
                }
            </style>
            <form action="#" method="post" id="formulario_csa" class="login_formulario_csa">
                <fieldset>

                    <legend><?php echo($instance['title'])?></legend>
                    <label for="cnpj">CNPJ</label>
                    <input type="text" name="cnpj">
                    <input type="submit" value="enviar">
                    <button class="fechar" onClick="fechar()">X</button>
                </fieldset>
            </form>
            <script>
                function pegar_cnpj() {
                    const formulario= document.getElementById("formulario_csa")
                    formulario.style.visibility = "block"
                }
                function fechar(){
                    const formulario= document.getElementById("formulario_csa")
                    formulario.style.visibility = "none"
                }
            </script>
       <?php
    }
    /*  
        Método form()

        A função que irá determinar as configurações do widget no painel do WordPress.

        essa função é abstrata ou seja tem que ser compativel ao metodo widget da classe WP_Widget,
        dessa forma tendo que passar os parametros:
           array $instance configurações atuais.
           

    */
    public function form($instance){
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<?php
    }
    /*   
        Método update()

        Toda ferramenta online precisa de atualizações ao longo do tempo.
        É essa função que vai permitir que o widget seja atualizado sempre que preciso.

        essa função é abstrata ou seja tem que ser compativel ao metodo widget da classe WP_Widget,
        dessa forma tendo que passar os parametros:
            $new_instance Novas configurações para essa instancia como input pelo usuario via form()
            $old_instance Configurações antigas para esta instancia
    */
    public function update($new_instance, $old_instance){
       
        $instance          = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        return $instance;
    
    }
}

?>