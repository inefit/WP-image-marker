<div class="wrap">
	<?php screen_icon( 'post' ); ?>
	<h2><?php _e('Image Marker','fabric-marker'); ?></h2>

	<div id="container_marker">		
    <div id="fabim_text_area">
      <div>
      <button id="fabim_btn_upload" type="button" class="upload_image_button button button-secondary">
        <?php _e('Select Image','fabric-marker'); ?>
      </button>
  
        <p>
          <?php _e('Text','fabric-marker'); ?><br />
          <textarea name="fabim_input_text" id="fabim_input_text"></textarea>
        </p>
        <p>
          <?php _e('Text Align','fabric-marker'); ?><br />
          <select name="fabim_input_align" id="fabim_input_align">
            <option value="left">left</option>
            <option value="center">center</option>
            <option value="right">right</option>
          </select>
        </p>
        <p>
          <?php _e('Position (vertical)','fabric-marker'); ?><br />
          <select name="fabim_input_vertical" id="fabim_input_vertical">
            <option value="top">top</option>
            <option value="bottom">bottom</option>
          </select>
        </p>
        <p>
          <?php _e('Position (Horizontal)','fabric-marker'); ?><br />
          <select name="fabim_input_horizontal" id="fabim_input_horizontal">
            <option value="left">left</option>
            <option value="center">center</option>
            <option value="right">right</option>
          </select>
        </p>
        <p>
          <?php _e('Font Size','fabric-marker'); ?><br />
          <select name="fabim_input_font" id="fabim_input_font">
            <?php for($i=1;$i<=100;$i++): ?>
              <option <?php if($i==12){ echo 'selected="selected"'; } ?> value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php endfor; ?>
          </select>px
        </p>
        <p>
          <?php _e('Padding','fabric-marker'); ?><br />
          <select name="fabim_input_padding" id="fabim_input_padding">
            <?php for($i=1;$i<=100;$i++): ?>
              <option <?php if($i==10){ echo 'selected="selected"'; } ?> value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php endfor; ?>
          </select>px
        </p>
        <p>
          <?php _e('Font Color','fabric-marker'); ?><br />
          <?php $this->colorField('fabim_input_fontcolor','#000000','#000000','color'); ?>
        </p>
        <p>
          <?php _e('Background Color','fabric-marker'); ?><br />
          <?php $this->colorField('fabim_input_bgcolor','none','none','background-color'); ?>
        </p>
        <p>
          <?php _e('Width','fabric-marker'); ?><br />
          <input style="width:80px" value="100" type="number" size="5" name="fabim_input_number" min="1" step="1" id="fabim_input_number" />
          <select name="fabim_input_numberpx" id="fabim_input_numberpx">
            <option value="%">%</option>
            <option value="px">px</option>
          </select>
        </p>
        <p>
          <button type="button" id="submit_button" class="button button-primary"><?php _e('Submit','fabric-marker'); ?></button>
          <?php add_thickbox(); ?>
          <div id="submit_box" style="display:none;" class="modal">
               <div align="center">
                   <img src="<?php echo plugin_dir_url( dirname(__FILE__ ) ) ?>/assets/images/loading.gif" />
                   <span id="submit_box_text"><?php _e('Please wait','fabric-marker'); ?></span>
               </div>
          </div>
        </p>
      </div>
    </div>
    <div id="fabim_marker_area">
      <div id="fabim_image_area">
       <div id="fabim_text"></div> 
			 <img src="" id="fabim_image" width="100%" height="100%">
      </div>
		</div>
	</div>
</div>

<script type="text/javascript">
	var file_frame;
	var wp_media_post_id = 10; // Store the old id
	var set_to_post_id = 10; // Set this

  jQuery(document).ready(function($){
    jQuery('#fabim_input_text').on('change paste keyup', function() {
      $('#fabim_text').html( jQuery('#fabim_input_text').val() );
    });

    $('#submit_button').click(function(){
      $('#submit_box').modal();
      $('#fabim_image_area').html2canvas({
          onrendered: function (canvas) {
              //Set hidden field's value to image data (base-64 string)
              var _imageData = canvas.toDataURL("image/png");

              var data = {
                'action': 'fabim_upload',
                'image': _imageData
              };

              jQuery.post(ajaxurl, data, function(response) {
                $('#submit_box_text').html(response)
              });
          }
      });
    })

    $('#fabim_input_vertical').on('change',function(){
      var _align = $('#fabim_input_vertical').val();
      if(_align  == 'top'){
        $('#fabim_text').addClass('fabim-top');
        $('#fabim_text').removeClass('fabim-bottom');
      }
      else{
        $('#fabim_text').removeClass('fabim-top');
        $('#fabim_text').addClass('fabim-bottom');
      }   
    });

    $('#fabim_input_horizontal').on('change',function(){
      var _align = $('#fabim_input_horizontal').val();
      if(_align == 'left'){
        $('#fabim_text').addClass('fabim-left');
        $('#fabim_text').removeClass('fabim-right');
        $('#fabim_text').removeClass('fabim-center');
      }
      else if(_align == 'center'){
        $('#fabim_text').addClass('fabim-center');
        $('#fabim_text').removeClass('fabim-right');
        $('#fabim_text').removeClass('fabim-left');
      }
      else if(_align == 'right'){
        $('#fabim_text').addClass('fabim-right');
        $('#fabim_text').removeClass('fabim-center');
        $('#fabim_text').removeClass('fabim-left');
      }
    });

    $('#fabim_input_align').on('change',function(){
      var _align = $('#fabim_input_align').val();
      $('#fabim_text').attr('align',_align);
    });

    $('#fabim_input_font').on('change',function(){
      var _size = $('#fabim_input_font').val()+"px";
      $('#fabim_text').css('font-size',_size);
    });

    $('#fabim_input_padding').on('change',function(){
      var _padding = $('#fabim_input_padding').val()+"px";
      $('#fabim_text').css('padding',_padding);
    });
    
    $('.colorpicker').each(function(){
        $(this).wpColorPicker({
          palettes: true,
          change: function(event,ui){
            var hexcolor = $( this ).wpColorPicker( 'color' );
          }
        });
    });

    $('#fabim_input_number').on('change paste keyup',function(){
      changeWidth();
    });

    $('#fabim_input_numberpx').on('change', function(){
      changeWidth();
    });

    function changeWidth(){
      var _number = $('#fabim_input_number').val();
      var _pixel = $('#fabim_input_numberpx').val();

      $('#fabim_text').css('width',_number+_pixel);
    }


    $('.colorpick').each(function(){
      $(this).hide();
      var target_id = $(this).attr('target-id')+"Target";
      var text_id = $(this).attr('target-id');
      var _id = $(this).attr('id');
      var _change = $(this).attr('data-change');
      $(this).farbtastic(function(color){
        $('#'+text_id).val(color);
        $('#'+target_id).css('background-color',color);
        //alert(_change);
        $('#fabim_text').css(_change,color);
      });

      $('#'+text_id).bind("change paste keyup", function() {
        var _textId = $(this).attr('id');
        $('#'+_textId+"Target").css('background-color', $(this).val() );
        $('#fabim_text').css(_change,$(this).val());
      });

      $('#'+target_id).click(function(){ $('#'+_id).slideToggle()});

    })
  
  });
  

  jQuery('.upload_image_button').live('click', function( event ){

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      // Set the post ID to what we want
      file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
      // Open frame
      file_frame.open();
      return;
    } else {
      // Set the wp.media post id so the uploader grabs the ID we want when initialised
      wp.media.model.settings.post.id = set_to_post_id;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery( this ).data( 'uploader_title' ),
      button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();

      console.log(attachment.url);
      jQuery('#fabim_image').attr('src',attachment.url);

      // Do something with attachment.id and/or attachment.url here
      
      // Restore the main post ID
      wp.media.model.settings.post.id = wp_media_post_id;
    });

    // Finally, open the modal
    file_frame.open();
  });
  
  // Restore the main ID when the add media button is pressed
  jQuery('a.add_media').on('click', function() {
    wp.media.model.settings.post.id = wp_media_post_id;
  });
</script>