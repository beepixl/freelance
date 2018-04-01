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
    <div class="container container-property">
        <div class="row-fluid">
            <div class="span9">
                <h2>{page_title}</h2>
                <div class="property_content">
                {page_body}
                {has_page_images}
                <h2>{lang_Imagegallery}</h2>
                <ul data-target="#modal-gallery" data-toggle="modal-gallery" class="files files-list ui-sortable content-images">  
                    {page_images}
                    <li class="template-download fade in">
                        <a data-gallery="gallery" href="{url}" title="{filename}" download="{url}" class="preview show-icon">
                            <img src="assets/img/preview-icon.png" class="" />
                        </a>
                        <div class="preview-img"><img src="{thumbnail_url}" data-src="{url}" alt="{filename}" class="" /></div>
                    </li>
                    {/page_images}
                </ul>

                <br style="clear: both;" />
                {/has_page_images}
                
                {has_page_documents}
                <h2>{lang_Filerepository}</h2>
                <ul>
                {page_documents}
                <li>
                    <a href="{url}">{filename}</a>
                </li>
                {/page_documents}
                </ul>
                {/has_page_documents}
                </div>

                <div class="row-fluid">
                <div class="agents-list">
                {paginated_agents}
                <div class="agent span4">
                    <div class="image"><img src="{image_url}" alt="{name_surname}" /></div>
                    <div class="name"><a href="{agent_url}#content">{name_surname} ({total_listings_num})</a></div>
                    <div class="phone">{phone}</div>
                    <div class="mail"><a href="mailto:{mail}?subject={lang_Estateinqueryfor}: {page_title}">{mail}</a></div>
                </div>
                {/paginated_agents}
                </div>
                </div>
                
                <div class="pagination" style="margin-top: 10px;">
                <?php echo $agents_pagination; ?>
                </div>
            </div>
            <div class="span3">
                <h2>{lang_Search}</h2>
                <form class="form-search agents" action="<?php echo current_url().'#content'; ?>" method="get">
                <input name="search-agent" type="text" placeholder="{lang_CityorName}" value="<?php echo $this->input->get('search-agent'); ?>" class="input-medium" />
                <button type="submit" class="btn">{lang_Search}</button>
                </form>
            </div>
        </div>
    </div>
</div>
    
<?php _subtemplate('footers', _ch($subtemplate_footer, 'standard')); ?>

<?php _widget('custom_javascript');?> 

  </body>
</html>