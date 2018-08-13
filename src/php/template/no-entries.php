<div class="notfound">
	<div class="nofound-title">
		<?php if( is_search() || is_archive() ): ?>
			記事が見つかりませんでした。
		<?php else: ?>
			お探しのページが見つかりませんでした。
		<?php endif; ?>	
	</div>
	<div class="nofound-contents">
		<?php if(is_search()): ?>
			<p>指定されたキーワードでは記事が見つかりませんでした。別のキーワード、もしくはカテゴリーから記事をお探しください。</p>
		<?php elseif(is_archive()): ?>
			<p>まだ記事が投稿されていません。以下でキーワードやカテゴリーから記事を探すことができます。</p>
		<?php else: ?>
			<p>お探しのページは「すでに削除されている」、「アクセスしたアドレスが異なっている」などの理由で見つかりませんでした。以下でキーワードやカテゴリーから記事を探すことができます。</p>
		<?php endif; ?>
		<?php get_search_form(); ?>
		<div class="ct">
			<a class="raised accent-bc" href="<?php echo esc_url( home_url( '/' ) ); ?>""><i class="fa fa-home"></i> ホームに戻る</a>
		</div>
	</div>
</div>