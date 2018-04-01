<!DOCTYPE html>
<html lang="{lang_code}">
  <head>
    <?php _widget('head');?>
    <script language="javascript">
    $(document).ready(function(){


    });
    
    </script>
  </head>

  <body>
{template_header}

<?php _subtemplate('headers', _ch($subtemplate_header, 'empty')); ?>

<a name="content" id="content"></a>
<div class="wrap-content">
    <div class="container">

        <h2>{page_title}</h2>
        
        {page_body}
        <br style="clear:both;" />
        <br style="clear:both;" />

        <?php _widget('center_featureproperties');?>
    </div>
</div>

<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?>  
  </body>
</html>