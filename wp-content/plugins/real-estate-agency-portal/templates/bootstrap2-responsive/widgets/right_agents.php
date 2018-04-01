<h2>{lang_Agents}</h2>
   <form class="form-search agents" action="<?php echo current_url().'#content'; ?>" method="get">
   <input name="search-agent" type="text" placeholder="{lang_CityorName}" value="<?php echo $this->input->get('search-agent'); ?>" class="input-medium" />
   <button type="submit" class="btn">{lang_Search}</button>
   </form>
   {paginated_agents}
   <div class="agent">
       <div class="image"><img src="{image_url}" alt="{name_surname}" /></div>
       <div class="name"><a href="{agent_url}">{name_surname} ({total_listings_num})</a></div>
       <div class="phone">{phone}</div>
       <div class="mail"><a href="mailto:{mail}?subject={lang_Estateinqueryfor}: {page_title}">{mail}</a></div>
   </div>
   {/paginated_agents}
   <div class="pagination" style="margin-top: 10px;">
   <?php echo $agents_pagination; ?>
   </div>