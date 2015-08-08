<?php
 
class linkedinoauth_widget extends WP_Widget {
 
    function __construct(){
        // Constructor del Widget
        $widget_ops = array('classname' => 'linkedinoauth_widget', 'description' => "Show Linkedin login button in a Widget" );
        parent::__construct('linkedinoauth_widget', "Linkedin Oauth Widget", $widget_ops);
    }
 
    function widget($args,$instance){
        // Contenido del Widget que se mostrará en la Sidebar
        extract($args);
        echo $before_widget; 
        $title =  $instance["linkedinoauth_title"];
        $description = $instance["linkedinoauth_descr"]; 
        if (isset($title))
        echo "<h3 class=\"widget-title\">".$title."</h3>";
        echo "<p>";
        echo do_shortcode('[linkedinbtn]');
        echo "</p>";
        if (isset($description))
        echo "<p>".$description."</p>";
        echo $after_widget;
    }
 
    function update($new_instance, $old_instance){
        // Función de guardado de opciones  
        $instance = $old_instance;
        $instance["linkedinoauth_title"] = strip_tags($new_instance["linkedinoauth_title"]);
        $instance["linkedinoauth_descr"] = strip_tags($new_instance["linkedinoauth_descr"]);
        // Repetimos esto para tantos campos como tengamos en el formulario.
        return $instance;      
    }
 
    function form($instance){
        // Formulario de opciones del Widget, que aparece cuando añadimos el Widget a una Sidebar
         ?>
        <p>
            <label for="<?php echo $this->get_field_id('linkedinoauth_title'); ?>"><?php _e('Title','linkedin_oauth'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('linkedinoauth_title'); ?>" name="<?php echo $this->get_field_name('linkedinoauth_title'); ?>" <?php if (isset($instance["linkedinoauth_title"])) { ?> value="<?php echo $title = $instance["linkedinoauth_title"]; ?>" <?php } ?>>
        </p>
         <p>
            <label for="<?php echo $this->get_field_id('linkedinoauth_descr'); ?>"><?php _e('Description','linkedin_oauth'); ?></label>
            <input type="text" id="<?php echo $this->get_field_id('linkedinoauth_descr'); ?>" name="<?php echo $this->get_field_name('linkedinoauth_descr'); ?>" <?php if (isset($instance["linkedinoauth_descr"])) { ?> value="<?php echo $title = $instance["linkedinoauth_descr"]; ?>" <?php } ?>>
        </p>
        <?php
    }    
} 
 
?>