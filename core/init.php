<?php

/**
 * 
 * Класс начальной инициализации Базы данных
 * В идеале это все должно быть на миграциях
 *
 */
class Init 
{
	public function __construct()
	{
		// Создаем таблицу пользователя и комментария
		Db::Execute(Sql::createCustomerTable());
		Db::Execute(Sql::createCommentTable());		
		// Создаем тестового пользователя
		Db::Execute(Sql::createTestUser());
	}
}