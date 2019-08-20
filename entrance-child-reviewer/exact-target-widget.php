<?php
// use widgets_init Action hook to execute custom function
add_action( 'widgets_init', 'Exact_Target_register_widgets' );

 //register our widget
function Exact_Target_register_widgets() {

    register_widget( 'Exact_Target_Widget' );

}

class Exact_Target_Widget extends WP_Widget {

    function Exact_Target_Widget() {

      $widget_ops = array(
          'classname'   => 'exact_target_widget',
          'description' => 'Exact Target Widget'
      );

      $this->WP_Widget( 'Exact_Target_Widget', 'Exact Target Widget',  $widget_ops );

    }

     //build our widget settings form
    function form( $instance ) {

    }

    //save our widget settings
    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        return $instance;

    }

    //display our widget
    function widget( $args, $instance ) {

        // echo $before_widget;

        extract( $args );

        include 'exact-target-widget-body.php';

        // // END DISPLAY ACF HERE
        // echo $after_widget;

    }
}
// =====================================================================================================
// Params
// =====================================================================================================
