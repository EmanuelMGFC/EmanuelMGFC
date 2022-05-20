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
        
                <button class="fazer_login" id="fazer_login"><?php echo($instance['title'])?></button>
                
                <style>
                    .modal_login{
                        position: fixed;
                        display:none;
                        top:30vh;
                        bottom:50vh;
                        left: 50vh;
                        right:50vh;
                        z-index: 11111111;
                    }
                    
                    .modal_login>.formulario_login_csa>fieldset{
                        background-color:white;
                    }
                    .modal_login button.fechar{
                        position: absolute;
                        top: 0.8em;
                        right: 0;
                        background-color: white;
                        box-sizing: border-box;
                        height: auto;
                        width: 20%;
                        margin: 2px;
                        text-align:center;
                        color:black;
                        float: left;
                        display: inline-block;
                        cursor:pointer;
                    }
                    .modal_login>button.fechar:hover{
                        background-color:rgb(224,224,224);
                    }
                    .modal_ativo{
                        display:block;
                    }
                
                </style>
                <div id="modal_login" class="modal_login">
                    <form method="post"  action="" id="formulario_csa" class="formulario_login_csa">
                        <fieldset>
        
                            <legend>Login CSA</legend>
                            <label for="cnpj_form_modal">CNPJ</label>
                            <input type="text" id="cnpj_form_modal" placeholder="11.111.111/1111-11" name="cnpj_form_modal" maxlength="18" required>
                           
                            <input type="submit" value="enviar">
                            
                        </fieldset>
                    </form>
                    <button id="fechar" class="fechar">X</button>
                    <script>
                    
                        const btn_fazer_login =document.getElementById("fazer_login")
                        function add_class_ativo() {
                            const modal = document.getElementById("modal_login")
                            modal.classList.toggle('modal_ativo')
                        }
                        btn_fazer_login.addEventListener('click', add_class_ativo)
                        
                        const btn_fechar_modal = document.getElementById("fechar")
                        function remove_class_ativo() {
                            const modal = document.getElementById("modal_login")
                            modal.classList.remove('modal_ativo')
                        }
                        btn_fechar_modal.addEventListener('click', remove_class_ativo)

                        const cnpj = document.getElementById("cnpj_form_modal")
                        cnpj.addEventListener('keyup',()=>{
                            if(cnpj.value.length == 2 || cnpj.value.length == 6){
                                cnpj.value +='.'
                            }
                            if(cnpj.value.length == 10){
                                cnpj.value +='/'
                            }
                            if(cnpj.value.length == 15){
                                cnpj.value +='-'
                            }

                        })
                    </script>
                    
                </div>
        <?php

        $token='56b910bc820c209c86574cb4425db223e330717c8a6a6a1aed75e84983178dd97ef9b122c3744fd2df44bf4b780232e87591977149605cb44e0d6a2de12f405ee53c23f497fe6da14edba7d1f19933a6a9c583dcac0faab955792804c6a926070639b3b1f3fca6d90a1983f41a7190524a37168ca964d039b889104a20cfbbbd';
        $argumentos = array(
            'method'=> 'GET',
            'headers'=>array(
                'authorization'=> 'bearer '.$token,
                'Content-Type' => 'application/json; charset=utf-8',
            )
        );

            
        $urlds= $_SERVER['REQUEST_URI'];
        $url_formulario_cnpj="/wordpress/formulario-de-solicitacao-para-analise-biagio-turbos/";
        $cnpj=filter_input(INPUT_POST,'cnpj_form_modal', FILTER_SANITIZE_SPECIAL_CHARS);


        $api_em_json = wp_remote_get('http://localhost:1337/api/csas?filters%5Bcnpj%5D%5B$eq%5D='.$cnpj, $argumentos);
        $corpo_do_json = $api_em_json['body'];
        $corpo_json_php= json_decode($corpo_do_json);
        $data= $corpo_json_php->data;

        $errors= array();
        
        if (empty($data)) {
            $errors[]="<li>usuario não existe</li>";
            
        }

        function mostrar_erros($errors){
            foreach($errors as $error){
                echo("$error");
            }
        }
      
        if(empty($errors)){
            session_start();
            $_SESSION['data']=password_hash(serialize($data[0]), PASSWORD_DEFAULT);
            $_SESSION['logado']=true;
            $_SESSION['cnpj'] = base64_encode($data[0]->attributes->cnpj);
            session_create_id();
            session_encode();
            header('Location: '.$url_formulario_cnpj);
            die();
        }
      
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
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo:' ); ?></label>
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