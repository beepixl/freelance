<?php if(file_exists(APPPATH.'libraries/pdf.php')) : ?>
<div class="panel panel-default panel-sidebar-1">
    <div class="panel-body text-center" style="padding:15px 0;">
        <a class='btn btn-default' target="_blank" style="border-color: #ccc;" href='<?php echo site_url('api/pdf_export/'.$property_id.'/'.$lang_code) ;?>'><img src='assets/img/icons/filetype/pdf.png' height="20px" style='height: 20px;'/>
            <span style="vertical-align: middle;"><?php echo _l('PDF export');?> </span>
        </a>
    </div>
</div>
<?php endif;?>