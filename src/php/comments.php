<?php $args = array(
  'title_reply' => 'クチコミを投稿する',
  'label_submit' => 'クチコミを送信する',
  // 'comment_notes_before' => '<p class="commentNotesBefore">入力エリアすべてが必須項目です。メールアドレスが公開されることはありません。</p>',
  'comment_notes_after'  => '<p class="comment-notes-after">クチコミは承認されると表示されます。</p>',
  'fields' => array(
    'author' => '<p class="comment-form-author">' .
                '<label for="author">名前</label> <input id="author" name="author" type="text" value="" size="30" maxlength="245"><label for="sex">'. __('性別') . '</label>
                <select id="sex" class="sex" name="sex">
                  <option value="">--
                  <option value="男性">男性
                  <option value="女性">女性
                </select>
                </p>',
    'email'  => '<div class="comment-form-star"><p class="comment-form-star__text">満足度</p>
                  <div class="evaluation">
                  <input id="star1" type="radio" name="star" value="5" />
                  <label for="star1">★</label>
                  <input id="star2" type="radio" name="star" value="4" />
                  <label for="star2">★</label>
                  <input id="star3" type="radio" name="star" value="3" />
                  <label for="star3">★</label>
                  <input id="star4" type="radio" name="star" value="2" />
                  <label for="star4">★</label>
                  <input id="star5" type="radio" name="star" value="1" />
                  <label for="star5">★</label>
                </div></div>',
    'url'    => '',
    'cookies'    => '',
    ),
  'comment_field' => '<p class="comment-form-comment">' . '<label for="comment">コメント</label><textarea id="comment" name="comment" cols="45" rows="8" required="required" placeholder="コメント" /></textarea></p>',
  );
comment_form( $args ); ?>