<?php 
class Model_Reviews extends Model
{
	public $reviews;
	// Чтоб не заморачиваться с пользователем,
	// Такой ведь задачи не было...
	private $_customerId = 1;
	private $_pageId = 0;
	
	// Какое действие выбрал пользователь
	private $_mAction;
	private $_mActionedArticleId;
	
	
	
	
	public function __construct()
	{
		// Проверяем, не нужно ли добавить новый комментарий
		if (isset($_POST['addNewReview']))
		{
			$this->create($this->_customerId, $this->_pageId, $_POST['review_text'], 0);
		}
		
		
		
		if (isset($_POST['deleteAllReview']))
		{
			Db::Execute(Sql::deleteAllReview());
		}
		
		foreach ($_POST as $key => $value)
			// If a submit button was clicked ...
			if (substr($key, 0, 6) == 'submit')
			{
				/* Get the position of the last '_' underscore from submit
				 button name e.g strtpos('submit_edit_attr_1', '_') is 17 */
				$last_underscore = strrpos($key, '_');
				
				/* Get the scope of submit button
				 (e.g  'edit_dep' from 'submit_edit_attr_1') */
				$this->_mAction = substr($key, strlen('submit_'),
						$last_underscore - strlen('submit_'));
				
				/* Get the attribute id targeted by submit button
				 (the number at the end of submit button name)
				 e.g '1' from 'submit_edit_attr_1' */
				$this->_mActionedArticleId = substr($key, $last_underscore + 1);
				
				break;
			}
		
		// Нажали добавить новый отзыв
		if($this->_mAction == 'reviewAnswer') {
			$parent_id = $this->_mActionedArticleId;
			$review = $_POST['review_'.$this->_mActionedArticleId];
			$this->create($this->_customerId, $this->_pageId, $review, $parent_id);
		}
	}
	
	public function get_data()
	{
		$reviews = Db::getAll(Sql::getReviews());
		$this->reviews = $this->prepareReviews($reviews);
		
		
		return $this->reviews;
	}
	
	private function prepareReviews($reviews, $parentId = 0)
	{
		$res = [];
		
		foreach ($reviews as $review) {
			if ($review['parent_id'] == $parentId) {
				$review['child'] = $this->prepareReviews($reviews, $review['review_id']);
				$res[] = $review;
			}
		}
		
		return $res;
	}
	
	private function strip_data($text)
	{
		$text = htmlspecialchars($text);
		$quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!" );
		$goodquotes = array ("-", "+", "#" );
		$repquotes = array ("\-", "\+", "\#" );
		$text = trim( strip_tags( $text ) );
		$text = str_replace( $quotes, '', $text );
		$text = str_replace( $goodquotes, $repquotes, $text );
		
		return $text;
	}
	
	/**
	 * Добавляем в базу новое review
	 * @param int $customer_id  какой пользователь оставил комментарий
	 * @param int $page_id на какой старнице размещен комментарий
	 * @param string $review текст ревью
	 * @param int $parent_id ответ ли это на вопрос?
	 * @return int результпат выполнения
	 */
	public function create($customer_id, $page_id, $review, $parent_id) {
		
		$review = $this->strip_data($review);
		
		if (empty($review))
		{
			// Зачем нам добавлять пустой комментарий
			return 0;
		}
		
		$sql = Sql::createNewReview();
		
		$params = array (
				':customer_id' => $customer_id,
				':review' => $review,
				':page_id' => $page_id,
				':parent_id' => $parent_id);
		
		return Db::Execute($sql, $params);
	}
}