<?php

	# Параметры приложения

	$access_token = '';

	# Версия API

	$version_api = '5.103';

	# Параметры для формирования запроса API

	$group_id = ''; // ID сообщества, информацию об обсуждениях которого нужно получить.
	$topic_id = ''; // ID обсуждения
	$count = 15; // Количество сообщений, которое необходимо получить (но не более 100). По умолчанию — 20.
	$extended = 1; // Если указать в качестве этого параметра 1, то будет возвращена информация о пользователях, являющихся авторами сообщений. По умолчанию 0.
	$sort = 'desc'; // порядок сортировки комментариев: asc — хронологический, desc — антихронологический

	echo '<ul class="list-reviews-vk">';
				
	for($i = 0; $i < $count; $i++){

		$get = curl('https://api.vk.com/method/board.getComments?group_id='.$group_id.'&topic_id='.$topic_id.'&extended='.$extended.'&offset='.$i.'&count=1&sort='.$sort.'&v='.$version_api.'&access_token='.$access_token);
					
		$get_data = json_decode($get, true);

		$user_id = $get_data['response']['items'][0]['from_id']; // ID автора комментария
		$date_comment = $get_data['response']['items'][0]['date']; // Дата комментария в формате unixtime
		$text_comment = $get_data['response']['items'][0]['text']; // Текст комментария
		$attachments_comment = $get_data['response']['items'][0]['attachments']; // Прикрепленные файлы к комментарию
		$photo_user = $get_data['response']['profiles'][0]['photo_50']; // Фото автора комментария (50,100 и т.д.)
		$first_name = $get_data['response']['profiles'][0]['first_name']; // Имя автора комментария
		$last_name = $get_data['response']['profiles'][0]['last_name']; // Фамилия автора комментария
?>

		<li>

			<div class="photo-user-vk">
				<img src="<?php echo $photo_user ; ?>" width="50" height="50">
			</div>

			<div class="text-user-vk">

				<h6><?php echo $first_name." ".$last_name; ?></h6>

				<span class="date-review">Отзыв оставлен: <?php echo gmdate("d-m-Y", $date_comment); ?></span>

				<p><?php echo $text_comment; ?></p>

				<div class="attachments-comment">
					<?php
						if(!empty($attachments_comment)){

							foreach($attachments_comment as $files){
								echo '<a href='.$files['photo']['sizes'][9]['url'].' rel="nofollow" target="_blank">';
									echo '<img src='.$files['photo']['sizes'][7]['url'].'>';
								echo '</a>';
							}

						}
					?>
				</div>

			</div>
							
		</li>

<?php

	}
				
	echo '</ul>';

	function curl($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
?>