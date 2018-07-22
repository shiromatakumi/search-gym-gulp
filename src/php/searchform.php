<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<input type="search" class="search-field" placeholder="地域 ジム名など" value="<?php echo get_search_query(); ?>" name="s" />
<input type="submit" class="search-submit" value="検索" />
</form>