<h1>Тестовая страница комментариев</h1>

<form class="comment" method="post">
<?php

echo renderReviews($data);

function renderReviews($reviews, $isChild=false) {
	$html = '';
	
	foreach ($reviews as $review) {
		$html .= '
<div class="comment_item'.(($isChild)?' comment_item_child':'').'">
 <div class="comment_item_info">
  <span>'.$review['name'].'</span>
  <span>'.$review['email'].'</span>
  	<span>'.$review['created_on'].'</span>
 </div>
 <div class="comment_item_text">'.$review['review'].'<textarea name="review_'.$review['review_id'].'" placeholder="Ответить на комментарий"></textarea><input type="submit" name="submit_reviewAnswer_'.$review['review_id'].'" value="Ответить"></div>';
 
		if (is_array($review['child'])) {
			$html .= renderReviews($review['child'], true);
		}
 		$html .= '</div>';
	}
	
	return $html;
}
	
?>
 <div class="comment_first">
  <textarea name="review_text" placeholder="Напишите комментарий"></textarea>
  <input type="submit" name="addNewReview" value="Написать"/>
 </div>
 <?php if (!empty($data)) {echo '<div class="comment_first comment_last"><input type="submit" name="deleteAllReview" value="Удалить все комментарии"/></div>';}?>
</form>