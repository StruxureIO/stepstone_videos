humhub.module('videos', function(module, require, $) {

    var init = function() {
        console.log('videos module activated');
    };

    var hello = function() {
        alert(module.text('hello')+' - '+module.config.username)
    };

    module.export({
        //uncomment the following line in order to call the init() function also for each ajax call
        //initOnPjaxLoad: true,
        init: init,
        hello: hello
    });
    
    $(document).on("click", "#step-add-video-tag-old", function () {
      console.log('step-add-video-tag');
      
      var next_tag_id   = $('#next-tag-id').val();      
      $('#tag-list tbody').append('<tr data-id="'+ next_tag_id +'"><td><input class="step-video-tag" type="text" value=""></td><td class="tb-tag-icon"><i class="fal fa-tag"></i></td><td><a class="step-choose-icon">Edit Icon</a></td><td><label class="switch"><input class="step-tag-menu" type="checkbox"><span class="slider round"></span></label></td><td><label class="switch"><input class="step-tag-forcetop" type="checkbox"><span class="slider round"></span></label></td><td><a class="step-delete-tag"><i class="fal fa-trash-alt"></i></a></td></tr>');
      next_tag_id++;
      $('#next-tag-id').val(next_tag_id);            
    });
    
    //<label class="switch"><input type="checkbox"><span class="slider round"></span></label>
    
    
    $(document).on("click", ".step-choose-icon", function () {
      var new_icon = '';
      var tag_icon = $(this).closest('tr').find('i').attr("class");
      if(tag_icon != '')
        tag_icon = '<i class="' + tag_icon + '"></i>';
      new_icon = prompt('Enter the fontawesome icon HTML that you would like to assign for this tag.', tag_icon);
      console.log('new_icon', new_icon);
      //$(this).closest(".tb-tag-icon").toggleClass();
      //new_icon = new_icon.trim();
      if(new_icon != '') {
        var start = new_icon.indexOf('"');
        var end = new_icon.lastIndexOf('"');
        var icon_class = new_icon.substring(start+1, end);
        console.log('icon_class',icon_class,start+1,end);

        $(this).closest('tr').find('i').attr('class',icon_class);      
        $('#video-tags-icon').val(icon_class);      
      }
      //$(this).closest('tr').find('i').toggleClass('fal','far');
      //$(this).closest('tr').find('i').toggleClass('fa-tag','fa-list-ol');
      
      // <i class="far fa-list-ol"></i> <i class="far fa-clipboard-list"></i>
    });
    
    
    $(document).on("click", ".step-tag-menu", function () {
      var tag_id = $(this).closest('tr').attr('data-id');      
      var checked = jQuery(this).is(':checked') 
      console.log('step-tag-menu',tag_id,'checked',checked);
      if(checked) {
        $('#menu').val('1');
      } else {
        $('#menu').val('0');        
      }
    });
    
    $(document).on("click", ".step-tag-forcetop", function () {
      var tag_id = $(this).closest('tr').attr('data-id');      
      var checked = jQuery(this).is(':checked') 
      console.log('step-tag-forcetop',tag_id,'checked',checked);
      if(checked) {
        $('#force_top').val('1');
        $('#hide_top').val('0');        
        $('.step-tag-hidetop').prop('checked', false);
      } else {
        $('#force_top').val('0');        
      }
        
    });
    
    $(document).on("click", ".step-tag-hidetop", function () {
      var tag_id = $(this).closest('tr').attr('data-id');      
      var checked = jQuery(this).is(':checked') 
      console.log('step-tag-hidetop',tag_id,'checked',checked);
      if(checked) {
        $('#hide_top').val('1');
        $('#force_top').val('0');        
        $('.step-tag-forcetop').prop('checked', false);
      } else {
        $('#hide_top').val('0');        
      }
        
    });
    
        
    $(document).on("click", ".step-edit-tag", function () {
        var tag_id = $(this).closest('tr').attr('data-id');      
        console.log('tag_id',tag_id);
    });
            
    $(document).on("click", ".step-delete-tag", function () {
        var tag_id = $(this).closest('tr').attr('data-id');      
        console.log('tag_id',tag_id);
    });
    
    $(document).on("click", ".step-video-tag", function () {
      var video_tags = "";    
    
      $('.step-video-tag').each(function() {   
        var tag_id = $(this).attr('data-id');      
        var checked = $(this).is(':checked') 
        if(checked) {
          if(video_tags == '') 
            video_tags = tag_id;
          else
            video_tags += ',' + tag_id;
        }        
      });
      $('#video-tags').val(video_tags);              
    });
        
  
  
  
//    $(document).on("click", ".step-video-tag", function () {
//      var video_tags = "";    
//      $('input[type=radio].step-video-tag:checked').each(function() {   
//        var tag_id = jQuery(this).attr('data-id');
//        console.log('tag_id',tag_id);
//        if(video_tags == '')
//          video_tags = tag_id;
//        else
//          video_tags += ',' + tag_id;        
//      });
//      console.log('video_tags',video_tags);
//      $('#video_tags').val(video_tags);              
//    });
    
    
});


